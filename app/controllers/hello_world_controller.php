<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function sandbox() {
        $version = new Version(array(
            'title' => 'd',
            'content' => 'a'
        ));
        $errors = $version->errors();

        Kint::dump($errors);
    }

    public static function frontpage() {
        View::make('suunnitelmat/frontpage.html');
    }

    public static function area() {
        View::make('suunnitelmat/area.html');
    }

    public static function topic() {
        View::make('suunnitelmat/topic.html');
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }

    public static function editmessage() {
        View::make('suunnitelmat/editmessage.html');
    }

}
