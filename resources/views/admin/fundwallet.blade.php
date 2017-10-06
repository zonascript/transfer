<style media="screen">
    .inner-holder {
        background: #222d32;
        color: #b8c7ce;
        padding: 13px 15px;
        border-radius: 3px;
    }
    .beneficiary-container {
      padding: 30px;
    }

    .btn-primary {
        color: #b8c7ce;
        background-color: transparent !important;
        /*border-color: white !important;*/
        border: none !important;
        font-size: 12.5px;
    }

    i.fa.fa-plus {
      color: white;
      font-weight: 500;
    }

    .btn-success {
        background-color: #00a65a;
        border-color: #04864a;
        margin-left: 30px;
        padding: 10px !important;
    }

    i.fa {
      color: #b8c7ce;
    }

    .fa-trash-o:hover {
      color: #b32913;
    }

    .fa-eye:hover {
      color: #fff;
    }

    .beneficiary-row.row {
      margin-bottom: 30px;
    }

    h5.beneficiary-name {
      margin-bottom: 20px;
    }

    @media screen and (max-width:768px) {

      .single-beneficiary-holder.col-md-3 {
          margin-bottom: 20px;
          padding: 0px;
      }
    }
</style>

@extends('layouts.admin')

@section('content')


<div class="container-fluid">

  <div class="beneficiary-container">

      <div class="beneficiary-row row">
        <div class="single-beneficiary-holder col-md-6">
            <div class="inner-holder">
                <h5 class="beneficiary-name"><b>Fund: </b> Wallet Name</h5>
                <form action="fundWallet" method="POST">
                  {{ csrf_field() }}
                  <input type="text" name="fname"  class="form-control input-defaulted" placeholder="First Name" required>     
                  <br><input type="text" name="lname"  class="form-control" placeholder="Last Name" required>       
                  <br><input type="text" name="phone"  class="form-control" placeholder="+2348031234567" required>       
                  <br><input type="email" name="emailaddr"  class="form-control" placeholder="Email Address" required>       
                  <br><input type="number" name="card_no"  class="form-control" placeholder="Card No." required>       
                  <br><input type="number" name="cvv"  class="form-control" placeholder="CVV" required>                  
                  <br><input type="number" name="expiry_year" class="form-control " placeholder="Expiry Year" required>
                  <br><input type="number" name="expiry_month" class="form-control" placeholder="Expiry Month" required>
                  <br><input type="number" name="pin" class="form-control" placeholder="PIN" required>
                  <br><input type="number" name="amount" class="form-control" placeholder="Amount" required>
                  <br><button type="submit" class="btn btn-info" name="button">Fund</button>
                  <button type="button" class="btn btn-danger" name="button">Cancel</button>
                </form>                
            </div>
        </div>
      </div>


    </div>
   @if(!empty($transMsg))
   <script type="text/javascript">
        $(document).ready(function() {
            $('#myModal').modal();
        });
    </script>

    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Otp</h4>
        </div>
        <div class="modal-body">
          <p>{{$transMsg}}</p>

          <form action="" method="POST">
            <input type="hidden" value="{{$transRef}}">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

</div>
@endif


@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>