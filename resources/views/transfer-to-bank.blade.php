@extends('layouts.user')

@section('title')
      Transfer to Bank
@endsection
@section('content')

<link rel="stylesheet" href="/css/form.css">
            <div class="col-md-6 col-sm-6">
            <img src="https://ferpay.com/fpmedia/panel/svg/ewallet-transfer-bank.svg" width="300" height="300">
                  @if(session('failed'))
                  
                  <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>{{ session('failed') }}</strong> 
                  </div>
                  @elseif(session('status'))
                  
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>{{ session('status') }}</strong> Alert body ...
                  </div>
                  
                  
                  @endif
                  <h4 class="intro text-center">Transfer To Beneficiary </h4>
                  <form class="input-form" action="" method="POST">
                  {{csrf_field()}}

                    <div class="form-group">
                        <select class="form-control cus-input" name="beneficiary_id">
                          <option>Select Beneficiary</option>
                            @foreach($wallet->beneficiary as $key => $beneficiaries)
                              <option value="{{$beneficiaries->id}}">{{$beneficiaries->name}}</option>
                            @endforeach
                        </select>
                    </div>
                          
                          <div class="form-group">
                            @foreach($wallet->beneficiary as $key => $beneficiaries)
                              <input type="text" class="form-control cus-input" name="amount" id="amount" value="{{$beneficiaries->account_number" readonly>
                            @endforeach
                          </div>     
                 
                          <div class="form-group">
                              <input type="number" class="form-control cus-input" name="amount" id="amount" placeholder="Amount">
                          </div>

                            <div class="form-group">
                              <select class="form-control cus-input" name="wallet_id" id="wallet_id">
                                  <option value="{{ $wallet->id }}">{{ $wallet->wallet_name }}</option>
                                
                              </select>
                            </div>

                            <div class="form-group">
                              <input type="text" class="form-control cus-input" name="narration" id="narration" placeholder="Narration">
                            </div>
                            
                        <div class="form-group center-block">    
                          <button id="" type="submit" class="btn btn-primary pull-right">Transfer</button>
                        </div>


                  </form>
            </div>

  @endsection
