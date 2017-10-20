
@extends('layouts.admin')
@section('title', 'Topup Admin Dashboard ')
@section('subtitle', 'Manage Phone/Data Topup')
@section('content')
 <!-- Content Wrapper. Contains page content -->

<div class="col-sm-12">
    <div class="box">
            <div class="box-header">
              <div class="box-header">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                Add New Phone</button>
        </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              
              <h3 class="profile-username text-center">Mobile Topup Wallet</h3>

              <p class="text-center"><strong>₦ {{number_format($topupbanlance, 2) }}</strong></p>
              <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#PurchaseTopUp">Purchase</button>
              
              <hr>

              <h3 class="profile-username text-center">Mobile Topup Wallet</h3>

              <p class="text-center"><strong>₦ {{number_format($topupbanlance, 2) }}</strong></p>
             <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#walletTopUp">Top Up Wallet</button>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
        
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#contacts" data-toggle="tab">Contacts</a></li>
              <li><a href="#history" data-toggle="tab">Transaction History</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="contacts">
                
                 <table id="datatable" class="table table-bordered table-hover">
                    <thead>
                            <tr>
                                <td>Name</td>
                                <td>Title</td>
                                <td>Dept</td>
                                <td>Phone</td>
                                <td>Email</td>
                                <td>Weekly Max</td>
                                <td>Nos of Topups this week</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($phones) > 0)
                              @foreach($phones as $phone)
                                <tr>
                                    <td>{{ $phone->firstName }} {{ $phone->lastName }}</td>
                                    <td>@isset($phone->title){{ $phone->title }}@else Not Set @endisset</td>
                                    <td>@isset($phone->department){{ $phone->department }}@else Not Set @endisset</td>
                                    <td>{{ $phone->phoneNumber }}</td>
                                    <td>@isset($phone->email){{ $phone->email }}@else Not Set @endisset</td>
                                    <td>{{ $phone->max_tops }}</td>
                                    <td>{{ $phone->amount }}</td>
                                </tr>
                              @endforeach
                            @else
                               <tr>
                                  <td></td>
                                  <td>No Phone Number Added</td>
                                  <td></td>
                                  <td></td>
                              </tr>
                            @endif
                        </tbody>
                </table>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="history">

               <table class="table">
                    <thead>
                        <tr>
                          <th>Name</th>
                          <th>Account Number</th>
                          <th>Account Name</th>
                          <th>Bank</th>
                          <th colspan="2" >Action</th>
                        </tr>
                    </thead>
                    
                </table>
                
              </div>
              
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
 
    
</div>
</div>
</div>
                <div class="row">
                    
                        <div class="container">
                            <!-- Trigger the modal with a button -->
                            <!-- Modal -->
                            <div class="modal fade" id="PurchaseTopUp" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Transfer To Service Provider</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="#" method="post" accept-charset="utf-8">
                                                <div class="modal-body" style="padding: 5px;">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="text" placeholder="Account number" type="text" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="Amount" placeholder="Amount" type="text" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-footer" style="margin-bottom:-14px;">
                                                    <input type="submit" class="btn btn-success" value="Save" />
                                                    <button style="float: right;" type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <!-- Trigger the modal with a button -->
                            <!-- Modal -->
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Add New Phone Number</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('contacts.store') }}" method="post" accept-charset="utf-8">
                                                {{csrf_field()}}
                                                <div class="modal-body" style="padding: 5px;">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="firstname" placeholder="First Name" type="text" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="lastname" placeholder="Last Name" type="text" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="phone" placeholder="Phone Number" type="text" required />
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="email" placeholder="Email" type="text" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="title" placeholder="Title" type="text" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="department" placeholder="Department" type="text" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                           <select class="form-control" name="network">
                                                            <option selected value="">Choose Network</option>
                                                            <option value="15">MTN</option>
                                                            <option value="6">GLO</option>
                                                            <option value="1">AIRTEL</option>
                                                            <option value="2">9Mobile</option>
                                                            <option value="4">Visa</option>
                                                           </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="max_tops" placeholder="Weekly Maximum Topups" type="text" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-footer" style="margin-bottom:-14px;">
                                                    <input type="submit" class="btn btn-success" value="Save" />
                                                    <button style="float: right;" type="button" class="btn btn-default btn-close" data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!---Modal for wallet top Up-->
                    <div class="modal fade" id="walletTopUp">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title text-center">Phone Wallet Top Up</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Card Details</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <form action="#" method="POST" role="form form-horizontal">
                                            <!-- text input -->
                                            <div class="container-fluid">
                                                <fieldset>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="cc_name">First Name</label>
                                                                <div class="controls">
                                                                    <input name="fname" class="form-control" id="cc_name" title="First Name" required type="text">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="cc_name">Last Name</label>
                                                                <div class="controls">
                                                                    <input name="lname" class="form-control" id="cc_name" title="last name" required type="text">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phone Number</label>
                                                        <div class="controls">
                                                            <input name="phone" class="form-control" autocomplete="off" maxlength="20" required="" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email Address</label>
                                                        <div class="controls">
                                                            <input name="emailaddr" class="form-control" autocomplete="off" required="" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Card Number</label>
                                                        <div class="controls">
                                                            <input name="card_no" class="form-control" autocomplete="off" maxlength="20" required="" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Card Expiry Date</label>
                                                        <div class="controls">
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <select class="form-control" name="expiry_month">
                                                                       <option value="01">January</option>
                                                                       <option value="02">February</option>
                                                                       <option value="03">March</option>
                                                                       <option value="04">April</option>
                                                                       <option value="05">May</option>
                                                                       <option value="06">June</option>
                                                                       <option value="07">July</option>
                                                                       <option value="08">August</option>
                                                                       <option value="09">September</option>
                                                                       <option value="10">October</option>
                                                                       <option value="11">November</option>
                                                                       <option value="12">December</option>
                                                                   </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                  <select class="form-control" name="expiry_year">
                                                                    @for ($i = 2017;$i <2040;$i++)
                                                                       <option>{{$i}}</option>
                                                                    @endfor  
                                                                   </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Card CVV</label>
                                                                <div class="controls">
                                                                    <input class="form-control" autocomplete="off" maxlength="3" pattern="\d{3}" title="Three digits at back of your card" required="" type="text" name="cvv">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Pin</label>
                                                                <div class="controls">
                                                                    <input class="form-control" autocomplete="off" maxlength="4" pattern="\d{4}" title="pin" required="" type="text" name="pin">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Amount</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">₦</div>
                                                                    <input name="amount" type="text" class="form-control" id="amount" placeholder="Amount">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label></label>
                                                        <div class="controls">
                                                            <button type="submit" class="btn btn-primary">Top up</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <!--row ends-->
                            <!---Modal for wallet top Up-->
                            <div class="modal fade" id="walletTopUp">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title text-center">Wallet Top Up</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Wallet Details</h3>
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body">
                                                <form role="form">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Account</label>
                                                        <input class="form-control" value="Transfer Rule Sms" type="text" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Wallet Id</label>
                                                        <input class="form-control" value="X846945532" type="text" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Top-up Amount</label>
                                                        <input id="amountToPay" class="form-control" type="text">
                                                    </div>
                                                    <input type="button" class="btn btn-block btn-success" name="" id="topu1p" value="Top-Up">
                                                </form>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
            </section>
@endsection
