    <nav class="navbar navbar-inverse navbar-fixed-top">
       <div class="navbar-header"><br>
        &nbsp;&nbsp;&nbsp;<a href="{{url('/')}}">  <img src="/img/HNGlogo.png" alt=""></a>
        <button type="button" class="navbar-toggle collapsed" style="color:#fff; background:#39689C;" data-toggle="collapse" data-target="#navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>    
            
          </div>
         
          <div class="collapse navbar-collapse" id="navbar">
          <form class="navbar-form navbar-right navform">
          <ul>
         <!-- Optionally, you can add icons to the links -->
       <a href="{{ url('/admin/managewallet') }}"><i class="fa fa-briefcase"></i> <span>Manage Wallet</span></a>
      <!--<li><a href="{{ url('/admin/analytics') }}"><i class="fa fa-line-chart"></i> <span>Transaction Analytics<span></a></li>-->
      <a href="{{ url('/admin/smswallet') }}"><i class="fa fa-envelope"></i> <span>SMS Wallet</span></a></a>
      <a href="{{ url('/admin/phonetopup') }}"><i class="fa fa-money"></i> <span>Phone Top-up Wallet</span></a></a>
      
   
       
                  </ul>

             @if(Auth::user()->first_name !== null || Auth::user()->last_name !== null)
                  {{ Auth::user()->first_name.' '.Auth::User()->last_name }}
             @else
                  {{ Auth::user()->username }}
             @endif
            <i class="fa fa-user-circle-o"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           </form>
           </div>
</nav>
