@extends('layouts.user')

@section('title')
      View Wallet
@endsection
@section('content')

<style>

i.can{
        color: #00a65a;
        
    }

    i.cannot{
      color: #dd4b39;
    }

i.sent{
        color: #00a65a;
        filter: blur(10px);
        -webkit-filter: blur(10px);
        z-index:-1
        
    }

    em.sent{
        opacity: 0.5
        z-index:-1
        
    }

    i.received{
      color: #dd4b39;
    }
</style>

<link rel="stylesheet" href="/css/walletview.css">

            <div class="col-md-4 col-sm-4">
              
              @if (!empty($permit))
                  <div class="col-sm-12 text-center">
                      <p>Wallet Name</p>
                      <h2>{{ $wallet->wallet_name }}</h2>
                  </div>   
                   <br>
                   <div class="col-sm-12 text-center">
                      <p>Wallet ID</p>
                      <h2>{{ $wallet->wallet_code }}</h2>
                   </div> 
                  </br>
                   <div class="col-sm-12 text-center">
                        <p>Currency Type</p>
                        <h2>Nigeria Naira</h2>
                   </div>
                    <br><br>
                   <div class="col-sm-12 text-center">
                        <p>Balance</p>
                        <h2>{{ $wallet->balance }}</h2>
                   </div>
              
                                 

            </div>

          <div class="col-md-8 col-sm-8">
					<div class="orange-box"><h4 class="title" align="center"> {{ ucfirst($wallet->wallet_name) }} TRANSACTION HISTORY</h4></div><br>
          <div class="table-responsive">
              @if(count($history))          
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Transaction Type</th>
                  <th>State</th>
									<th>Transaction Amount</th>
									<th>Transaction Date</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
              

          <div class="orange-box"><h4 class="title" align="center"> {{ $wallet->wallet_name }}'s Beneficiaries</h4></div>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Bank</th>
                  <th>Account Number</th>
                   <th>Wallet</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($beneficiaries as $beneficiary)
                <tr>
                  <td>{{ $beneficiary->name }}</td>
                  <td>{{ $beneficiary->bank_name }}</td>
                  <td>{{ $beneficiary->account_number }}</td>
                  <td>{{ $beneficiary->wallet_id }}</td>
                  <td>{{ $beneficiary->created_at->toFormattedDateString() }}</td>
                </tr>
              @endforeach
              
                </tr>               
              </tbody>
            </table>

            {{ $beneficiaries->links() }}
          </div>
          

          <div class="col-sm-12">  
		  	  
            	<a href="/fund/{{$wallet->id}}" class="btn btn-dark">Fund</a>
			
 
            <a href="/transfer-to-bank/{{$wallet->id}}" class="btn btn-dark ">Transfer To Beneficiary</a>
			

      <a href="/transfer-to-wallet" class="btn btn-dark ">Transfer to Another Wallet </a>

		 <div class="container">
    <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add Beneficiary</button>
  
  <!--Add Beneficiary  Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ADD Beneficiary</h4>
        </div>
        <div class="modal-body">
          <p>Please fill out details</p>
          <form action="/addbeneficiary/{{ $wallet->id }}" method="post" role="form" class="submit-topup">
                {{ csrf_field() }}
                <!-- text input -->
                <div class="form-group">                  
                  <label>Beneficiary Name</label>
                  <input type="text" required name="name" class="form-control input-defaulted" placeholder="Name">
                </div>

                 <div class="form-group">                   
                  <label>Bank</label>
                  <select name="bank_id" required class="form-control input-defaulted" >
                    <option>Select Bank</option>

                  @foreach(App\Http\Controllers\BanksController::getAllBanks() as $key => $bankcode)
                  <option value="{{$bankcode->id}}||{{$bankcode->bank_name}}">{{ $bankcode->bank_name }}</option>
                  @endforeach
                  </select>
                </div>

                 <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" required name="account_number" class="form-control input-defaulted" placeholder="Account Number">
                 </div>

                  <div class="form-group ">
                    <button type="submit" class="btn btn-success pull-right" name="button"> Add</button><br>
                  </div>
              </form>
          </div>
      </div>
    </div>
  
  

			

          </div>
		</div>

    @else

              <p> You do not have permission to view this wallet</p>

     @endif
    
  @endsection
