<?php

class AreaController extends BaseController {

    public static function index($id) {
        self::check_logged_in();
        
        $area = Area::find($id);
        $topics = Topic::findAllIn($id);
        View::make('area/index.html', array(
            'area' => $area,
            'topics' => $topics
        ));
    }

    public static function newArea($categoryId) {
        self::check_logged_in_as_admin();
        
        $category = Category::find($categoryId);
        View::make('area/new.html', array(
            'category' => $category)
        );
    }

    public static function save($id) {
        self::check_logged_in_as_admin();
        
        $params = $_POST;

        $attributes = array(
            'category' => $id,
            'name' => $params['name'],
            'description' => $params['description']
        );
        $area = new Area($attributes);
        $errors = $area->errors();

        if (count($errors) == 0) {
            $area->save();
            Redirect::to('/frontpage', array('info' => 'Alue luotu!'));
        } else {
            Redirect::to('/category/' . $id . '/new', array('errors' => $errors, 'attributes' => $attributes));
        }
    }
    
    public static function edit($id) {
        self::check_logged_in_as_admin();
        
        $area = Area::find($id);

        View::make('area/edit.html', array(
            'attributes' => $area
        ));
    }
    
    public static function update($id) {
        self::check_logged_in_as_admin();
        
        $params = $_POST;
        
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'description' => $params['description']);
        
        $area = new Area($attributes);
        $errors = $area->errors();
        
        if (count($errors) > 0) {
            View::make('area/edit.html', array('errors' => $errors, 'attributes' => $area));
        }
        else {
            $area->update();
            
            Redirect::to('/area/' . $area->id, array('info' => 'Alueen muokkaus onnistui!'));
        }
    }

    public static function destroy($id) {
        self::check_logged_in_as_admin();
        
        $area = new Area(array('id' => $id));
        $area->destroy();

        Redirect::to('/frontpage', array('info' => 'Alue poistettu!'));
    }

}
