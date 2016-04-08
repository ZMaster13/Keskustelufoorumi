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
}