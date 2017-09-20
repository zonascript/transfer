<?php

namespace App\Http\Controllers;

class BanksController extends Controller
{

    
    public function banks()
    {
        \Unirest\Request::verifyPeer(false); 

        $headers = array('content-type' => 'application/json');

        $response = \Unirest\Request::post('https://moneywave.herokuapp.com/banks', $headers);
        $data = json_decode($response->raw_body, TRUE);
        $banks = $data['data'];
        //die();
        
        return view('banks', compact('banks'));
    }
}
