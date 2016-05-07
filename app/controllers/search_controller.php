<?php

class SearchController extends BaseController {

    public static function search() {
        parent::check_logged_in();

        View::make('search/index.html');
    }

    public static function searchByTitle() {
        parent::check_logged_in();

        $params = $_POST;
        $topics = Topic::findByTitle($params['title']);
        SearchController::showResults($topics);
    }

    public static function searchByWriter() {
        parent::check_logged_in();

        $params = $_POST;
        $topics = Topic::findByWriter($params['writer']);
        SearchController::showResults($topics);
    }

    public static function searchByTime() {
        parent::check_logged_in();

        $params = $_POST;

        try {
            $timestamp = $params['time'];
            $topics = Topic::findByTime($timestamp);
            SearchController::showResults($topics);
        } catch (Exception $e) {
            View::make('search/index.html', array(
                'errors' => array('Syötä aika oikeassa muodossa!')
            ));
        }
    }

    private static function showResults($topics) {
        parent::check_logged_in();

        View::make('area/index.html', array(
            'area' => new Area(array('id' => -1)),
            'topics' => $topics
        ));
    }

}
