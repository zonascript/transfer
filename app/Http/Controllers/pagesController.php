<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class pagesController extends Controller
{
  use AuthenticatesUsers;

  protected $redirectTo = '/dashboard';
  
  public function home () {
    return view('home-page');
  }

  public function signin(Request $request) 
  {
    $data['ref'] = str_replace('http://', '', str_replace('https://', '', URL::previous()));
    $data['host'] = str_replace('http://', '', str_replace('https://', '', $request->server('HTTP_HOST')));

    return $this->showLoginForm($data); // Does the same thing as above
    //return view ('sign-in');
  }

  public function userdashboard(){
    return view('dashboard');
  }

  public function success(){
    return view('success');
  }

  public function failed(){
    return view('failed');
  }

  public function balance () {
    return view ('balance');
  }

  public function transfer () {
    return view ('transfer');
  }

  public function bank_transfer (){
    return view ('transfer-to-bank');
  }

  public function viewAccounts(){
      return view('view-accounts');
  }

  public function webAnalytics() {
    return view ('web-analytics');
  }

  public function viewWallet() {
    return view ('wallet-view');
  }

  //all other page functions can be added
  /*
  pubic function <function name> {
    {all the logic}
    return view('<blade name>');
  }*/
}
