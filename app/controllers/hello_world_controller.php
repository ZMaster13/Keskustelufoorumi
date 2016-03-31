<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function frontpage() {
        View::make('suunnitelmat/frontpage.html');
    }
    
    public static function topic() {
        View::make('suunnitelmat/topic.html');
    }
    
    public static function messages() {
        View::make('suunnitelmat/messages.html');
    }
    
    public static function login() {
        View::make('suunnitelmat/login.html');
    }
}
