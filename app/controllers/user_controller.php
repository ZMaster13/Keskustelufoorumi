<?php

class UserController extends BaseController {

    public static function login() {
        self::check_not_logged_in();
        
        View::make('member/login.html');
    }

    public static function handle_login() {
        self::check_not_logged_in();

        $params = $_POST;

        $member = Member::authenticate($params['username'], $params['password']);

        if (!$member) {
            View::make('member/login.html', array('errors' => array('Väärä käyttäjätunnus tai salasana!'), 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $member->id;

            Redirect::to('/', array('info' => 'Tervetuloa takaisin ' . $member->name . '!'));
        }
    }

    public static function logout() {
        self::check_logged_in();
        
        $_SESSION['user'] = null;
        Redirect::to('/login', array('info' => 'Olet kirjautunut ulos'));
    }

}
