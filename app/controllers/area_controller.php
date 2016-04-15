<?php

class AreaController extends BaseController {

    public static function index($id) {
        $area = Area::find($id);
        $topics = Topic::findAllIn($id);
        View::make('area/index.html', array(
            'area' => $area,
            'topics' => $topics
        ));
    }

    public static function newArea($categoryId) {
        $category = Category::find($categoryId);
        View::make('area/new.html', array(
            'category' => $category)
        );
    }

    public static function save($id) {
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

    public static function destroy($id) {
        $area = new Area(array('id' => $id));
        $area->destroy();

        Redirect::to('/frontpage', array('info' => 'Alue poistettu!'));
    }

}
