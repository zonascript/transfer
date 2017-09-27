
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Wallet Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <style>
    @import url('https://fonts.googleapis.com/css?family=Nunito+Sans');
    body {
      font-family: Nunito Sans;
    }

    nav {
      border-radius: 0 !important;
    }

    .menu {
      padding-top: 22px;
      box-sizing: border-box;
    }

    .menu-item {
      color: white;
      text-decoration: none !important;
    }

    .menu ul {
      display: flex;
      list-style: none;
      justify-content: space-around;
      padding: 0;
    }

    .menu li {
      width: 140px;
      height: 50px;
      background-color: #FD8032;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 2px;
    }

    .menu li:hover {
      background-color: #25313F;
      color: white;
    }

    .wallet-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 30px;
    }

    .wallet {
      position: relative;
      width: 300px;
      height: 150px;
      background: #333333;
      margin: 15px;
      cursor: pointer;
      box-shadow: -2px 4px 4px rgba(0, 0, 0, 0.15);
    }

    .wallet:hover {
      box-shadow: none;
    }

    .wallet .currency {
      position: absolute;
      width: 41px;
      height: 21px;
      left: 255px;
      top: 2px;


      font-style: normal;
      font-weight: bold;
      line-height: normal;
      font-size: 17px;
      letter-spacing: 0.68px;

      color: #FFFFFF;
    }

    .wallet-img {
      position: absolute;
      width: 63px;
      height: 77px;
      left: 130px;
      top: 33px;
    }

    .wallet .id {
      position: absolute;
      height: 14px;
      left: 5px;
      top: 130px;
      color: white;
    }

    .wallet .num {
      position: absolute;
      left: 7px;
      top: 5px;
      color: white;
    }

    .wallet .balance {
      position: absolute;
      right: 0;
      top: 125px;
      color: white;
      padding-right: 5px;
    }

    .profile {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: gray;
      margin-top: 4px;
      margin-left: 100px;
      margin-right: 100px;
    }

    .profile-info {
      color: white;
      padding: 5px;
      line-height: 5.5px;
      margin-top: 10px;
    }

    .active-sidebar {
      background-color: #C1D6DD;
    }

    .nav-search {
      margin-right: auto;
      margin-left: auto;
    }

    .navbar-brand {
      font-family: Nunito Sans;
      font-style: normal;
      font-weight: 800;
      line-height: normal;
      font-size: 20px;
      color: #FFFFFF !important;
    }

    .navbar-header img {
      width: 24px;
      height: 24px;
    }

    #sidebar {
      /* margin: 0 !important; */
      width: 200px;
      height: 500px;
      text-align: center;
      border-right: 1px solid rgb(192, 190, 190);
      margin-top: 30px;
      padding: 0;
    }

    .navbar-toggle {
      display: block;
      float: left;
    }

    .navbar {
      height: 50px;
      background: #25313F;
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    i.fa-window-close {
      font-size: 20px;
      color: #fff;
      margin: 20px;
      display: none;
    }

    @media screen and (max-width:768px) {
      #sidebar {
        width: 250px !important;
        height: 200vh;
      padding: 0;
      position: absolute;
      left: -1000px;
      top: 20px;
      }
      i.fa-window-close {
        font-size: 20px;
        color: #fff;
        margin-bottom: 20px;
        display: block;
      }
      .navbar-form .form-control {
        display: inline-block;
        width: auto;
        vertical-align: middle;
        display: none;
      }
      .navbar-nav {
        margin: 7.5px -15px;
        display: none;
      }
      .profile {
        display: none;
      }
      .navbar-form.navbar-right {
        display: none;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-inverse">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="{{ route('transferrules') }}"> <span> <img src="/img/logo.png" alt=""></span> PaysFund</a>
        <button type="button" id="navb" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
          aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <ul class="nav navbar-nav">
        <li><a href="#" style="color:white; font-size:18px;"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ul>
      <ul class="nav navbar-nav pull-right">
        <li><a href="#" style="color:white; font-size:18px;"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ul>
  </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2" style="text-align: left;" id="sidebar" >
        <i class="fa fa-window-close" id="close" aria-hidden="true"></i>
        <ul class="nav nav-stacked">
          <li class="active-sidebar">
            <a href="/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>
          </li>
          <li class="side-items">
            <a href="/wallet-view" class="side-item"><i class="fa fa-google-wallet"></i> Wallet View</a>
          </li>
          <li class="side-items">
            <a href="/transfer-to-wallet" class="side-item"><i class="fa fa-tasks"></i> Wallet Transfer</a>
          </li>
          <li class="side-items">
            <a href="/transfer-to-bank" class="side-item"><i class="fa fa-bank"></i> Bank Transfer</a>
          </li>
          <li class="side-items">
            <a href="/banks" class="side-item"><i class="fa fa-bank"></i> Banks</a>
          </li>
          <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out"></i> Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
          </li>
        </ul>
      </div>

      <div class="col-md-10">
        <div class="row">
          @foreach($wallets as $wallet)
            <div class="col-md-4">
              <div class="thumbnail">
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <i class="fa fa-money"></i> Wallet ID: <i class="fa fa-lock"></i> {{ $wallet->id }}
                  </div>
                  <div class="panel-body">
                    <p class="lead">NGN</p>
                    <img src="http://www.casumobonus.com/wp-content/uploads/2017/05/e-wallet-casumo.png" alt="">
                    <p class="lead">Wallet Code: {{ $wallet->wallet_code}}</p>

                      @foreach($transaction as $transact)
                        @if($transact['uref'] == $wallet->wallet_code)
                          ₦ {{ $transact['balance']}}
                        @endif
                      @endforeach
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript">
      $(document).ready(function() {

        $('#navb').click(function() {

            $('#sidebar').animate({
                left: "0px",
                "z-index": 10000
            }, 200).css(
              "background-color" , "rgb(37, 49, 63)",
              "height" , "200vh"
            );

            $('a.side-item').css(
                "color" , "#fff"
            );
        });

        $('#close').click(function() {

            $('#sidebar').animate({
                left: "-1000px",
                "z-index": 10000
            }, 200);
        });
      });
  </script>
</body>

</html>