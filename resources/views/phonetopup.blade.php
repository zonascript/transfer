@extends('layouts.user') @section('title') Phone Top Up @endsection @section('content')
<style>
i.can {
    color: #00a65a;
  }
  i.cannot {
    color: #dd4b39;
  }
  i.sent {
    color: #00a65a;
    filter: blur(10px);
    -webkit-filter: blur(10px);
    z-index: -1
  }
  em.sent {
    opacity: 0.5 z-index:-1
  }
  i.received {
    color: #dd4b39;
  }
  first {
    float: right;
    margin: 0 0 10px 10px;
  }
  form group {
    height: 400;
  }
  table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 899;
  }
  td,
  th {
    border: px solid #dddddd;
    text-align: center;
    padding: 5px;
  }
  tr:nth-child(even) {
    width: 100;
    background-color: #dddddd;
  }
  /* 
	#container {
		width:200;
	}
	#box1	{
		background:#fff; border:0px solid #000;
        { box-shadow: 1px 1px 1px #999; }
		float:left; min-height:230px; margin-right:10px;
	}
	#box2 	{
		background:#fff; border:0px solid #000;
		float:right; min-height:230px; width:30px;
	} */
}
</style>

<link rel="stylesheet" href="walletview.css">
<link rel="stylesheet" href="user.css">
<link rel="stylesheet" href="form.css">
<link rel="stylesheet" href="/css/walletview.css">


<div class="row">
<div class="col-md-6">
  <div class="panel panel-primary">
    <div class="panel-heading"><h2>Wallet Balance &#8358; {{ number_format($wallet->balance),2}}</h2></div>
  <div class="panel-body text-center">
    <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#walletTopUp">Fund Wallet</button>
  </div>
</div>
  
</div>

<div class="col-md-6">
  <div class="panel panel-primary">
    <div class="panel-heading"> <h2> Current Balance: &#8358;{{ number_format($topupbalance),2}} </h2></div>
  <div class="panel-body text-center">
    <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#Purchase">Purchase</button>
  </div>
</div>
  
</div>
</div>

<br>

 <div class="">
    
      <div >
      <div class="orange-box">
        <h4 class="title" align="center">Group Airtime Top Up</h4>
      </div>
      <br>
      <form class="form form-inline" action="{{ route('topup.phone.group')}}" method="POST" role="form">
                    {{csrf_field()}}
      <select class="form-control" name="department">
            <option>Select group</option>
            @foreach($phones as $contact)
              <option value="{{ $contact->department }}">{{ $contact->department }}</option>
            @endforeach
          </select>   
          <input class="form-control" type="number" placeholder="Enter Amount to be shared">  
          <button class="btn btn-success" type="submit" >Top Up Group</button>  
                 
      </from>
      
    </div>
    <br>
    <hr><br>






    <ul class="nav nav-pills nav-justified ">
        <li class="active"><a data-toggle="pill" href="#contactlist">Contact List</a></li>
        <li><a data-toggle="pill" href="#fundhistory">Funding History</a></li>
        <li><a data-toggle="pill" href="#topuphistory">Topup History</a></li>
    </ul>


<div class="tab-content">
        <div id="contactlist" class="tab-pane fade in active">

          <div class="orange-box">
      <h4 class="title" align="center">CONTACT LIST</h4>
    </div>

    <br>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-5"></div>
      <form method="GET" action="" accept-charset="UTF-8" id="conatcts-form">
        <div class="col-md-2">

          <select class="form-control" name="department">
            <option>All Depts</option>
            @foreach($phones as $contact)
              <option value="{{ $contact->department }}">{{ $contact->department }}</option>
            @endforeach
          </select>

        </div>
        <div class="col-md-3">
          <div class="input-group custom-search-form">
            <input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search tags">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit" id="search-users-btn">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
                @if (Input::has('search') && Input::get('search') != '')
                        <a href="" class="btn btn-danger" type="button" >
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    @endif

            </span>
          </div>
        </div>
      </form>
    </div>
    <br>

    <div class="table table-responsive">
      <table class="table" id="contact-table">
        <thead>
          <tr>
            <th><input type="checkbox" onClick="toggle(this)" /> Select All Contact</th>
            <td>Name</td>
            <td>Phone Number</td>
            <td>Network</td>
            <td>Title</td>
            <td>Department</td>
            <td>Weekly Limit</td>
            <td>Enter Amount<br>(airtime)</td>
            <td colspan="2">Action</td>
          </tr>
        </thead>
        <tbody>
          <form class="send-airtime" action="{{ route('topup.phone.multiple')}}" method="POST" role="form">
            {{ csrf_field() }} @if(count($phones) > 0) @foreach($phones as $phone)
            <tr class="contact-fn">

              <td><input type="checkbox" name="checked[]" value="{{$phone->id}}" class="checkbox"></td>
              <td class="firstName" data-user="{{ $phone->id }}">{{ $phone->firstname }} {{ $phone->lastname }}</td>
              <td class="phone">{{ $phone->phone }}</td>
              <td class="phoneRef">{{ $phone->netw }}</td>
              <td class="amount">{{ $phone->title }}</td>
              <td class="amount">{{ $phone->department }}</td>
              <td class="max-tops">{{ $phone->weekly_max }}</td>
              <td><input class="form-control input-airtime-amount" type="number" min="50" name="amount[{{$phone->id}}]" placeholder="Enter Amount"
                /></td>
              <td>

                <a class="airtime btn btn-success" data-id="{{ $phone->id }}" data-toggle="modal" data-target="#airtimeModal">
                   Airtime
                </a>
              </td>
              <td>
                <a class="btn btn-primary" data-id="{{ $phone->id }}" data-toggle="modal" data-target="#dataModal">
                    Data
                </a>
                </td>

              
            </tr>
            @endforeach @else
            <tr>
              <td></td>
              <td>No Phone Number Added</td>
              <td></td>
              <td></td>
            </tr>

            @endif

        </tbody>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><button type="submit" class="btn btn-success">Top up all</button></td>
          <td></td>
        </tr>
        </form>
      </table>
  </div>

        </div>
        <div id="fundhistory" class="tab-pane fade">

          <div class="orange-box">
      <h4 class="title" align="center">Fund Transfer History</h4>
</div>
<br>
<div class="table-responsive">
  <table class="table table-hover table-condensed" id="topuphistory">
   <thead>
     <tr>
        <th>S/N</th>
        <th>Payer</th>
        <th>Bank</th>
        <th>Wallet</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Narration</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      @php($count = 1)
      @foreach($walletfundhistory as $key => $walletfundhistories)
      <tr>
        <td>{{$count}}</td>
        <td>{{$walletfundhistories->user->username}}</td>
        <td>{{$walletfundhistories->bank->bank_name}}</td>
        <td>{{$walletfundhistories->wallet->wallet_name}}</td>
        <td>{{$walletfundhistories->amount}}</td>
        <td><i class="fa {{$walletfundhistories->status ? 'fa-check-circle can ' : 'fa-times-circle cannot'}}" aria-hidden="true"></i></td>
        <td>{{$walletfundhistories->narration}}</td>
        <td>{{$walletfundhistories->created_at}}</td>
      </tr>
      @php($count++)
      @endforeach
    </tbody>
  </table>
</div>

        </div>

        <div id="topuphistory" class="tab-pane fade">

            <div class="orange-box">
        <h4 class="title" align="center">TOPUP HISTORY</h4>
      </div>
      </th><br>
      


        <div class="table table-responsive">
          <table id="datatable" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Phone</th>
                <th>Name</th>
                <th>Network</th>
                <th>Amount</th>
                <th>Ref</th>
                <th>User</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>

              @if(count($topuphistory) > 0) @foreach($topuphistory as $hist)
              <tr>
                <th>{{ $hist->phone }}</th>
                <th>{{ $hist->firstname }} {{ $hist->lastname }}</th>
                <th>{{ $hist->netw }}</th>
                <td class="phone">{{ $hist->amount }}</td>
                <td class="phoneRef">{{ $hist->ref }}</td>
                <td class="amount">{{ $hist->username }}</td>
                <td class="amount">{{ $hist->status }}</td>
                <td class="amount">{{ $hist->created_at }}</td>

              </tr>
              @endforeach @else
              <tr>
                <td></td>
                <td>No Topup Transactions yet</td>
                <td></td>
                <td></td>
              </tr>
              @endif

              </tr>
              
            </tbody>
          </table> <br><br>
    
          
        </div>








<!---Modal for wallet top Up-->
                    <div class="modal fade" id="walletTopUp">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    
                                    <h4 class="modal-title text-center">Top up wallet</h4>
            
                                </div>
                                <div class="modal-body">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Card Details</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <form action="{{config('app.url')}}/phonetopup/fund" method="POST" role="form form-horizontal">
                                            {{csrf_field()}}
                                            <!-- text input -->
                                            <div class="container-fluid">
                                                <fieldset>
                                                    <input type="hidden" name="wallet_code" value="{{$wallet->wallet_code}}">
                                                    <input type="hidden" name="wallet_name" value="{{$wallet->wallet_name}}">
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
                                                                  @php($year = date('Y'))
                                                                  <select class="form-control" name="expiry_year">
                                                                      @for($i = $year; $i < $year + 6; $i++)
                                                                              <option value="{{$i}}">{{$i}}</option>
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
                                                                    <input class="form-control" autocomplete="off" maxlength="3" pattern="" title="Three digits at back of your card" required="" type="text" name="cvv">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Pin</label>
                                                                <div class="controls">
                                                                    <input class="form-control" autocomplete="off" maxlength="4" pattern="" title="pin" required="" type="text" name="pin">
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
                        </div>
                    </div>
                   
<!--row ends-->

<div class="container">
                            <!-- Trigger the modal with a button -->
                            <!-- Modal -->
                            <div class="modal fade" id="Purchase" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Transfer To Service Provider</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{config('app.url')}}/topup/wallet" method="post" accept-charset="utf-8">
                                                <div class="modal-body" style="padding: 5px;">
                                                 
                                                    <input name="wallet_id" value="{{$wallet->id}}" type="hidden">
                                                 
                                                        {{csrf_field()}}
                                                   
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="narration" placeholder="Narration" type="text" required />
                                                       
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                                            <input class="form-control" name="amount" placeholder="Amount" type="number" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-footer" style="margin-bottom:-14px;">
                                                    <button type="submit" class="btn btn-success">Purchase</button>
                                                    <button style="float: right;" type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
</div>
<center>
  <br> 

 
    



        <!-- Modal -->
        <div class="modal fade" id="airtimeModal" tabindex="-1" role="dialog" aria-labelledby="airtimeModal" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Topup <span class="phoneToTopUp"></span> with Airtime </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
              </div>
              <div class="modal-body">

                <div class="form-row">
                  <form class="send-airtime" action="{{ route('topup.phone.user')}}" method="POST" role="form">
                    {{csrf_field()}}
                    <input type="hidden" name="current_id" class="current_user">
                    <input type="hidden" name="Airtime" class="Airtime">

                    <div class="form-group col-md-6">
                      <label for="Firstname" class="col-form-label">Name</label>
                      <input type="text" class="form-control firstName" name="firstName">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="Phone" class="col-form-label">Phone</label>
                      <input type="text" class="form-control phone" name="phone">
                    </div>

                    <hr />
                    <div class="form-group col-md-12">
                      <label for="Lastname" class="col-form-label">Amount</label>
                      <input type="text" name="amount" class="form-control" placeholder="Please Enter Amount" required>
                    </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary btn-send">Send Airtime</button>
                </div>

                </form>
              </div>

            </div>
          </div>
        </div>


        <div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="dataModal" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Topup <span class="phoneToTopUp"></span> with Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
              </div>
              <div class="modal-body">

                <div class="form-row">
                  <form class="send-data" action="{{ route('topup.data.user')}}" method="POST" role="form">
                    {{csrf_field()}}
                    <input type="hidden" name="current_id" class="current_user">
                    <input type="hidden" name="Data" class="Data">

                    <div class="form-group col-md-6">
                      <label for="Firstname" class="col-form-label">Name</label>
                      <input type="text" class="form-control firstName" name="firstName">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="Phone" class="col-form-label">Phone</label>
                      <input type="text" class="form-control phone" name="phone">
                    </div>

                    <hr />


                    <select name="amount" class="form-control">

          <optgroup label="MTN">
            <option value="200">250MB (#200)</option>
            <option value="300">500MB (#300)</option>
             <option value="550">1GB (#550) </option>
            <option value="850">2GB  (#850)</option>
             <option value="1100">3GB (#1100)</option>
            <option value="1650">5GB (#1650)</option>
          </optgroup>

          <optgroup label="9MOBILE">
            <option value="250">250MB (#250)</option>
            <option value="350">500MB (#350)</option>
             <option value="650">1GB (#650)</option>
            <option value="1000">1.5GB (#1000)</option>
             <option value="1900">3GB (#1900)</option>
            <option value="3100">5GB (#3100)</option>
          </optgroup>

          <optgroup label="GLO">
            <option value="900">1.6GB/3.2GB</option>
            <option value="1800">3.75GB/7.5GB</option>
             <option value="2250">5GB/10GB</option>
            <option value="2650">6GB/12GB</option>
             <option value="3550">8GB/16GB</option>
            <option value="4450">12GB/24GB </option>
          </optgroup>

          <optgroup label="AIRTEL">
            <option value="950">1.5GB</option>
            <option value="1900">3.5GB</option>
             <option value="2375">5GB</option>
            <option value="3325">7GB</option>
             <option value="1100">3GB</option>
            <option value="1650">5GB</option>
          </optgroup>

        </select>






                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary btn-send">Send Data</button>
                </div>

                </form>
              </div>

            </div>
          </div>
        </div>


      </div>
    </div>
  </div>

  </div>
  </div>

  </div>

  @endsection @section('add_js')
  <script type="text/javascript">
    $(function () {
      $('.modal#airtimeModal').on('show.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        var row = btn.parents('tr');
        $(this).find('form input.phoneRef').val(row.find('td.phoneRef').html());
        $(this).find('form input.current_user').val(row.find('td.firstName').data('user'));
        $(this).find('form input.firstName').val(row.find('td.firstName').html());
        $(this).find('form input.netw').val(row.find('td.netw').html());
        // $(this).find('form input.lastName').val(row.find('td.firstName').data('lastName'));
        $(this).find('form input.phone').val(row.find('td.phone').html());
        $(this).find('form .phoneToTopUp').val(row.find('td.phone').html());
      });
    });
    $('.modal#airtimeModal').on('click', 'button.btn-send', function () {
      console.log('Clcked');
      $('.modal#airtimeModal').find('form.send-airtime').submit();
    })
//   $('.airtime').click(function() {
//     // get the invoice ID
//     var id = $(this).data('id');
//     // set up a GET route using the invoice ID and retrieve the result for that invoice
//     $.get('/topup/phone/' + id, function(response, status) {
//         // display the results in the modal
//         $('#airtimeModal .modal-body').html(response.data);
//     });
// });
  </script>

  <script type="text/javascript">
    $(function () {
      $('.modal#dataModal').on('show.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        var row = btn.parents('tr');
        $(this).find('form input.phoneRef').val(row.find('td.phoneRef').html());
        $(this).find('form input.current_user').val(row.find('td.firstName').data('user'));
        $(this).find('form input.firstName').val(row.find('td.firstName').html());
        $(this).find('form input.netw').val(row.find('td.netw').html());
        // $(this).find('form input.lastName').val(row.find('td.firstName').data('lastName'));
        $(this).find('form input.phone').val(row.find('td.phone').html());
        $(this).find('form .phoneToTopUp').val(row.find('td.phone').html());
      });
    });
    $('.modal#dataModal').on('click', 'button.btn-send', function () {
      console.log('Clcked');
      $('.modal#dataModal').find('form.send-data').submit();
    })
//   $('.airtime').click(function() {
//     // get the invoice ID
//     var id = $(this).data('id');
//     // set up a GET route using the invoice ID and retrieve the result for that invoice
//     $.get('/topup/phone/' + id, function(response, status) {
//         // display the results in the modal
//         $('#airtimeModal .modal-body').html(response.data);
//     });
// });
  </script>
  
   <script type="text/javascript" >


function toggle(source) {
  checkboxes = document.getElementsByName('checked[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>


  <script>
        $("#department").change(function () {
            $("#contacts-form").submit();
        });
        
        $(".select-all").click(function (){
          if ($(".select-all").is(':checked')){
              $(".checkbox").each(function (){
                $(this).prop("checked", true);
                });
              }else{
                $(".checkbox").each(function (){
                      $(this).prop("checked", false);
                });
                      }
              });
        $(".checkbox").click(function (){
            if ($(this).prop('checked')==false){
              $(".select-all").prop("checked", false);
            }
          });
    </script>

    

@endsection
