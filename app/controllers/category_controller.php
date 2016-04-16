<?php

class CategoryController extends BaseController {
    public static function save() {
        self::check_logged_in_as_admin();
        
        $params = $_POST;

        $attributes = array(
            'name' => $params['name']);
        $category = new Category($attributes);
        $errors = $category->errors();

        if (count($errors) == 0) {
            $category->save();
            Redirect::to('/frontpage', array('info' => 'Kategoria luotu!'));
        } else {
            Redirect::to('/frontpage/new', array('errors' => $errors, 'attributes' => $attributes));
        }
    }
    
    public static function newCategory() {
        self::check_logged_in_as_admin();
        
        View::make('category/new.html');
    }

    public static function destroy($id) {
        self::check_logged_in_as_admin();
        
        $category = new Category(array('id' => $id));
        $category->destroy();

        Redirect::to('/frontpage', array('info' => 'Kategoria poistettu!'));
    }
}
