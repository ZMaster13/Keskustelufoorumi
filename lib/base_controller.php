<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            $user = Member::find($user_id);

            return $user;
        }

        return null;
    }

    public static function check_logged_in() {
        if (!self::is_logged_in()) {
            Redirect::to('/login', array('message' => 'Kirjaudu sisään'));
        }
    }

    public static function check_not_logged_in() {
        if (self::is_logged_in()) {
            Redirect::to('/frontpage');
        }
    }

    public static function is_logged_in() {
        return isset($_SESSION['user']);
    }

    public static function check_logged_in_as_admin() {
        self::check_logged_in();
        if (!self::is_logged_in_as_admin()) {
            Redirect::to('/frontpage');
        }
    }

    public static function is_logged_in_as_admin() {
        if (!self::get_user_logged_in()->isAdmin) {
            return false;
        }
        return true;
    }

    public static function check_logged_in_as($userId) {
        self::check_logged_in();
        
        if (self::get_user_logged_in()->id != $userId && !self::is_logged_in_as_admin()) {
            Redirect::to('/frontpage');
        }
    }

}
