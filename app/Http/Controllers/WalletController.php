<?php
namespace App\Http\Controllers;

use DateTime;
use App\Wallet;
use App\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Restriction;
use App\CardWallet;
use App\Beneficiary;
use App\Rule;
use App\Transaction;
use URL;
use App\User;
use App\InternetBanking;
use App\BankTransaction;
use RestrictionController;
use App\Http\Controllers\RestrictionController as Restrict;
use App\Notifications\WalletTransaction as WalletTransactionNotify;
use App\Notifications\BankTransaction as BankTransactionNotify;
use App\Events\FundWallet;

class WalletController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cache');
    }

    public function makeUserAdmin(){
        $user = User::withTrashed()->where('email', 'johnobi@gmail.com')->first();
        $user->is_admin = 1;
        $user->deleted_at = null;
        $user->save();
        dd($user);
    }


    //get token for new transaction
    public function getToken()
    {
        $api_key = env('API_KEY');
        $secret_key = env('API_SECRET');
        \Unirest\Request::verifyPeer(false);
        $headers = array('content-type' => 'application/json');
        $query = array('apiKey' => $api_key, 'secret' => $secret_key);
        $body = \Unirest\Request\Body::json($query);
        $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/merchant/verify', $headers, $body);
        $response = json_decode($response->raw_body, true);
        $status = $response['status'];
        if (!$status == 'success') {
            echo 'INVALID TOKEN';
        } else {
            $token = $response['token'];
            return $token;
        }
    }

    public function fundWalletWithCard(Request $request, CardWallet $cardWallet)
    {
        $token = $this->getToken();
        // dd($request);
        $headers = array('content-type' => 'application/json', 'Authorization' => $token);
        $query = array(
            "firstname" => $request->fname,
            "lastname" => $request->lname,
            "email" => $request->emailaddr,
            "phonenumber" => $request->phone,
            "recipient" => "wallet",
            "recipient_id" => $request->wallet_code,
            "card_no" => $request->card_no,
            "cvv" => $request->cvv,
            "pin" => $request->pin, //optional required when using VERVE card
            "expiry_year" => $request->expiry_year,
            "expiry_month" => $request->expiry_month,
            "charge_auth" => "PIN", //optional required where card is a local Mastercard
            "apiKey" => env('API_KEY'),
            "amount" => $request->amount,
            "fee" => 0,
            "medium" => "web",
            //"redirecturl" => "https://google.com"
        );
        $body = \Unirest\Request\Body::json($query);

        $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/transfer', $headers, $body);
        
        $response = json_decode($response->raw_body, TRUE);
        // dd($response);
        if($response['status'] == 'success') {
            $response = $response['data']['transfer'];
            $transMsg = $response['flutterChargeResponseMessage'];
            $transRef = $response['flutterChargeReference'];
            
            $transaction = new CardWallet;
            $transaction->firstName = $response['firstName'];
            $transaction->lastName = $response['lastName'];
            $transaction->status = $response['status'];
            $transaction->wallet_name = $request->wallet_name;
            $transaction->phoneNumber = $response['phoneNumber'];
            $transaction->amount = $response['amountToSend'];
            $transaction->ref = $transRef;

            $transaction->save();

            return back()->with('status', $transMsg);
        }
        else{
            return back()->with('error', $response['message']);
        }
    }


    public function otp(Request $request, CardWallet $cardWallet)
    {
        \Unirest\Request::verifyPeer(false);

            $headers = array('content-type' => 'application/json');
            $query = array(
                'transactionRef'=>$request->ref,
                'otp' => $request->otp
            );
            $body = \Unirest\Request\Body::json($query);

            $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/transfer/charge/auth/card', $headers, $body);
            $response = json_decode($response->raw_body, true);
            if($response['status'] == 'success') {
                event(new FundWallet($cardWallet));
                Session::flash('success',$response);
                return redirect('admin/managewallet');

            }

            return back()->with('error', $response['message']);
    }

   //transfer from wallet to wallet
    public function transfer(Request $request, Wallet $wallet, CardWallet $fund) {
        $validator = $this->validateWalletTransfer($request->all());

        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            Session::flash('form-errors', $messages);
            return redirect()->to(URL::previous());
        } else {

           //checks for permissions
                $permit = Restriction::where('wallet_id', $wallet->id)
                        ->where('uuid', Auth::user()->id)
                        ->first();
                
                if($permit == null){
                    Session::flash('error', 'You do not have access to this wallet');
                    return redirect('/dashboard');

                }
                     $restrict = new Restrict($permit, $request);
                     $errors = $restrict->transferToWallet();
                if(count($errors) != 0){
                    Session::flash('errors', $errors);
                    return back();
                }
                //end of permission checks

                $recipient_wallet = Wallet::find($request->recipientWallet);

                $token = $this->getToken();
                $headers = array('content-type' => 'application/json', 'Authorization' => $token);
                $query = array(
                    "sourceWallet" =>  $wallet->wallet_code,
                    "recipientWallet" => $recipient_wallet->wallet_code,
                    "amount" => $request->amount,
                    "currency" => "NGN",
                    "lock" => $wallet->lock_code
                );

                $body = \Unirest\Request\Body::json($query);
                $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/wallet/transfer', $headers, $body);
                $response_arr = json_decode($response->raw_body, true);
                // print_r($response_arr);
                
                $status = $response_arr['status'];
                $r_data = $response_arr['data'];  

                if ($status == 'success') {
                    
                    //logic to persist wallet transaction details
                    $w_transaction = new WalletTransaction;
                    $w_transaction->source_wallet = $request->sourceWallet;
                    $w_transaction->amount = $request->amount;
                    $w_transaction->recipient_wallet = $request->recipientWallet;
                    $w_transaction->status = true;
                    $w_transaction->save();
                    //end of logic

                    //update wallet balance
                   // event(new WalletToWallet($transactions));
                    event(new FundWallet($fund));
                    $transaction = WalletTransaction::latest()->first();
                   // \LogUserActivity::addToLog($transaction->source->wallet_name.' transferred '.$transaction->amount.' to '.$transaction->destination->wallet_name);

                    //lgoic to display transaction details
                    $data= [];
                    $data['username'] = auth()->user()->username;
                    $data['source_wallet'] = $transaction->source->wallet_name;
                    $data['recipient_wallet'] = $transaction->destination->wallet_name;
                    $data['amount'] = $transaction->amount;
                    $data['time'] = $transaction->created_at->toDateTimeString();

                    $this->sendWalletTransactionNotifications($w_transaction);
                    
                    return redirect('wallet-transfer-success')->with('status', $data);
                } else {
                    $response = $r_data;    
                    Session::flash('error', $response);              
                    return back();
                }
        }
    }
    
    //validate transfer from wallet to bank
    public function validateTransferToAccount(Request $request, Wallet $wallet, BankTransaction $bank)
    {
        $data = [];
        $data['wallet_id'] = $request->wallet_id;
        $data['validate_id'] = $request->validate_id;

        if($data['validate_id'] == 50)
        {
            return redirect("/transfer/beneficiary/$wallet->id");
        }
        return back()->with('error', 'you entered a wrong ID');
    }
    //transfer from wallet to bank
    public function transferAccount(Request $request, Wallet $wallet, BankTransaction $bank)
    {
        $validator = $this->validateBeneficiary($request->all());
        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            Session::flash('form-errors', $messages);
            return redirect()->to(URL::previous());
        } else {
                $token = $this->getToken();
                $headers = array('content-type' => 'application/json', 'Authorization' => $token);
                $beneficiary = Beneficiary::find($request->beneficiary_id);
                $query = array(
                    "lock" => $wallet->lock_code,
                    "amount" => $request->amount,
                    "bankcode" => $beneficiary->bank_id,
                    "accountNumber" => $beneficiary->account_number,
                    "currency" => "NGN",
                    "senderName" => Auth::user()->username,
                    "narration" => $request->narration, //Optional
                    "ref" => $request->reference, // No Refrence from request
                    "walletUref" => $wallet->wallet_code
                );

                //checks for permissions
                $permit = Restriction::where('wallet_id', $wallet->id)
                        ->where('uuid', Auth::user()->id)
                        ->first();
                
                if($permit == null){
                    Session::flash('error', 'You do not have access to this wallet');
                    return redirect('/dashboard');
                }    
                 $restrict = new Restrict($permit, $request);
                     $errors = $restrict->transferToBank();
                if(count($errors) != 0){
                    Session::flash('errors', $errors);
                    return back();
                }
                //end of permission checks

                //Api call to moneywave for transaction
                $body = \Unirest\Request\Body::json($query);
                $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/disburse', $headers, $body);
                $response = json_decode($response->raw_body, true);
                $status = $response['status'];
                //end of Api call

                if ($status == 'success') {

                    //data to be parsed to display transaction details
                    $data = $response['data']['data'];
                    $data['senderName'] = Auth::user()->username;
                    $data['walletCodeSender'] = $wallet->wallet_code;
                    $data['receiverName'] = $beneficiary->name;
                    $data['beneficiaryAccount'] = $beneficiary->account_number;
                    $data['amount'] = $request->amount;
                    $data['narration'] = $request->narration;
                    //end of data prep

                    //logic to persist transaction details
                    $transaction = new BankTransaction;
                    $transaction->wallet_id = $wallet->id;
                    $transaction->amount = $request->amount;
                    $transaction->uuid =  Auth::user()->id;
                    $transaction->beneficiary_id = $beneficiary->id;
                    $transaction->transaction_reference = $data['uniquereference'];
                    $transaction->transaction_status = true;
                    $transaction->narration = $request->narration;
                    $transaction->save();
                    //end of logic for saving transactions


                    event(new FundWallet($bank));
                    $this->sendBankTransactionNotifications($transaction);
                    $transactions = BankTransaction::latest()->first();
                    //\LogUserActivity::addToLog(auth()->user()->name.'transferred '.$transactions->amount.' from '. $transactions->source->wallet_name.' to '.$transactions->beneficiary->name);
                    
                    return redirect('success')->with('status',$data);
                } else {
                    Session::flash('error',$response['message']);
                    return back();
                }
        }
    }

    //internet banking
    public function payWithInternetBanking(Request $request, Wallet $wallet)
    {
        $validator = $this->validateInternetBanking($request->all());

        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            Session::flash('form-errors', $messages);
            return redirect()->to(URL::previous());
        } else {

           //checks for permissions
                $permit = Restriction::where('wallet_id', $wallet->id)
                        ->where('uuid', Auth::user()->id)
                        ->first();
                
                if($permit == null){
                    Session::flash('error', 'You do not have access to this wallet');
                    return redirect('/dashboard');

                }
                     $restrict = new Restrict($permit, $request);
                     $errors = $restrict->canFundWallet();
                if(count($errors) != 0){
                    Session::flash('errors', $errors);
                    return back();
                }
                //end of permission checks
                
                $token = $this->getToken();

                $headers = array('content-type' => 'application/json','Authorization'=>$token);
                    $query = array(
                             "amount"=>$request->amount,
                             "apiKey"=>env('API_KEY'),
                             "charge_with"=>"ext_account",
                             "charge_auth"=>"INTERNETBANKING",
                             "sender_account_number" => $request->account_number,
                             "firstname"=>$request->firstname,
                             "lastname"=>$request->lastname,
                             "phonenumber" => $request->phoneNumber,
                             "email"=>$request->email,
                             "medium"=>"web",
                             $bank_detail = explode('||', request('bank_id')),
                            "sender_bank" => $bank_detail[0],
                             "recipient"=>"wallet",
                             "recipient_id" => $request->wallet_code,
                             //"redirecturl"=>"google.com"
                    );

                $body = \Unirest\Request\Body::json($query);

                $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/transfer', $headers, $body);

                $response = json_decode($response->raw_body, true);
                
                var_dump($response);
                die();
                if($response['status'] == 'success')
                {
                    $data = $response['data']['transfer'];
                    $transaction = new InternetBanking;
                    $transaction->name = $data['firstName'].' '.$data['lastName'];
                    $transaction->phone = $data['phoneNumber'];
                    $transaction->status = $data['status'];
                    $transaction->ip = $data['ip'];
                    $transaction->amount = $data['netDebitAmount'];
                    $transaction->reference = $data['flutterChargeReference'];
                    $transaction->wallet_code = $wallet->wallet_code;
                    $transaction->account_number = $data['account']['accountNumber'];
                    $transaction->account_name = $data['account']['accountName'];
                    $transaction->source = $data['source'];
                    $transaction->type = $data['type'];
                    $transaction->uuid = Auth::user()->id;
                    $bank_detail = explode('||', request('bank_id'));
                    $transaction->bank_name = $bank_detail[1];
                    $transaction->save();

                    return back()->with('status', $data['flutterChargeResponseMessage']);
                }
                    var_dump($response);
                    die();
        }
    }

    public function otpForInternetBanking()
    {
        $token = $this->getToken();

        $headers = array('content-type' => 'application/json','Authorization'=>$token);
        $query = array('transactionRef'=> 'MWV-1508998934097',
        'authType' => 'OTP/ACCOUNT_CREDIT', 
        'authValue' =>'123456'//could be the  OTP value,  this is applicable based on the response from charge request
        ) ;

        $body = \Unirest\Request\Body::json($query);

        $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/transfer/charge/auth/account', $headers, $body);
        var_dump($response);

    }

    
    public function storeWalletDetailsToDB($wallet_data, $lock_code, $wallet_name, $wallet_type)
    {
        $wallet = new Wallet;
        $moneywave_wallet_id = $wallet_data['id'];
        $balance_one = $wallet_data['balance_1'];
        $enabled = $wallet_data['enabled'];
        $wallet_code = $wallet_data['uref'];
        $merchant_id = $wallet_data['merchantId'];
        $currency_id = $wallet_data['currencyId'];
        $balance = $wallet_data['balance'];
        $updatedAt = $wallet_data['updatedAt'];
        $createdAt = $wallet_data['createdAt'];
        $wallet->moneywave_wallet_id = $moneywave_wallet_id;
        $wallet->balance_one = $balance_one;
        $wallet->balance = $balance;
        $wallet->merchant_id = $merchant_id;
        $wallet->currency_id = $currency_id;
        $wallet->enabled = $enabled;
        $wallet->lock_code = $lock_code;
        $wallet->wallet_code = $wallet_code;
        $wallet->uuid = Auth::user()->id;
        $wallet->wallet_name = $wallet_name;
        $wallet->type = $wallet_type;

        if ($wallet->save()) {
            return back()->with('success', 'Wallet creation successful');
        } else {
            return back()->with('error', 'Could not create wallet');
        }
    }

    public function createWalletAdmin($data) {
        $token = $this->getToken();
        $headers = array('content-type' => 'application/json', 'Authorization' => $token);

        $query = array(
            'name' => $data->wallet_name,
            'lock_code' => $data->lock_code,
            'user_ref' => $data->user_ref,
            'currency' => "NGN"
        );

        $body = \Unirest\Request\Body::json($query);
        $response = \Unirest\Request::post('https://moneywave.herokuapp.com/v1/wallet', $headers, $body);
        $response = json_decode($response->raw_body, true);
        $status = $response['status'];
        $data = $response['data'];
        return (!is_array($data)) ? true : $data;
    }
    
    public function logTransaction($data){
        $transaction = new Transaction;
        $transaction->wallet_code = $data['wallet_code'];
        $transaction->amount_transfered = $data['amount_transfered'];
        $transaction->transaction_status = $data['transaction_status'];
        $transaction->payer_uuid = $data['payer_uuid'];
        $transaction->payee_uuid = $data['payee_uuid'];
        $transaction->payee_account_number = $data['payee_account_number'];
        $transaction->bank_id = $data['bank_id'];
        $transaction->payee_wallet_code = $data['payee_wallet_code'];
        $transaction->transaction_reference = $data['transaction_reference'];
        $transaction->created_at = new DateTime();
        
        $transaction->save();
    }

    public function notifyMe(){
        $transaction = BankTransaction::first();
        // dd($transaction);
        $this->sendBankTransactionNotifications($transaction);
    }

    public function sendBankTransactionNotifications($transaction){
        Auth::user()->notify(new BankTransactionNotify($transaction));
        $admins = User::where('is_admin', true)->get();
        foreach($admins as $key => $admin){
            $admin->notify(new BankTransactionNotify($transaction));
        }
    }

    public function sendWalletTransactionNotifications($transaction){
        Auth::user()->notify(new WalletTransactionNotify($transaction, Auth::user()));
        $admins = User::where('is_admin', true)->get();
        foreach($admins as $key => $admin){
            $admin->notify(new WalletTransactionNotify($transaction, Auth::user()));
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validateWallet(array $data) {
        return Validator::make($data, [
            'wallet_name' => 'required|string|max:255',
            'lock_code' => 'required|string|max:100',
            'currency_id' => 'required|numeric',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatebeneficiary(array $data)
    {
        return Validator::make($data, [
            'wallet_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'beneficiary_id' => 'required|numeric',
        ],
        
        ['beneficiary_id.numeric' => 'Select a beneficiary']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validateWalletTransfer(array $data)
    {
        return Validator::make($data, [
            'sourceWallet' => 'required|numeric',
            'recipientWallet' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);
    }

    /**
     * Get a validator for an incoming internet banking request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validateInternetBanking(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'amount' => 'required|numeric',
            'phoneNumber' => 'required|numeric',
            'email' => 'required|email',
            'bank_id' => 'required|string',
            //'bank_name' => 'required|string',
            //'account_number' => 'required|string',

        ]);
    }


    
}
