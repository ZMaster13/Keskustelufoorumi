<?php

class UserController extends BaseController{
  public static function login(){
      View::make('member/login.html');
  }
  public static function handle_login(){
    $params = $_POST;

    $member = Member::authenticate($params['username'], $params['password']);

    if(!$member){
      View::make('member/login.html', array('errors' => array('Väärä käyttäjätunnus tai salasana!'), 'username' => $params['username']));
    }else{
      $_SESSION['user'] = $member->id;

      Redirect::to('/', array('info' => 'Tervetuloa takaisin ' . $member->name . '!'));
    }
  }
  
  public static function logout() {
      $_SESSION['user'] = null;
      Redirect::to('/login', array('info' => 'Olet kirjautunut ulos'));
  }
}