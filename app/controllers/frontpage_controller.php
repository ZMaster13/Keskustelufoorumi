<?php

class FrontpageController extends BaseController {

    public static function index() {
        $categories = Category::findAll();

        View::make('frontpage/index.html', array(
            'categories' => $categories
        ));
    }

    public static function newCategory() {
        View::make('frontpage/new_category.html');
    }
}
