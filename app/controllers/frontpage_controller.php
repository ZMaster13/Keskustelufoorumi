<?php

class FrontpageController extends BaseController {

    public static function index() {
        self::check_logged_in();
        
        $categories = Category::findAll();

        View::make('frontpage/index.html', array(
            'categories' => $categories
        ));
    }
}
