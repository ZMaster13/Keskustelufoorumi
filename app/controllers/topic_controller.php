<?php

class TopicController extends BaseController {

    public static function index($id) {
        self::check_logged_in();

        $topic = Topic::find($id);
        $messages = Message::findAllIn($id);
        $area = Area::find($topic->area);
        
        foreach ($messages as $message) {
            $message->markAsRead(parent::get_user_logged_in()->id);
        }
        
        View::make('topic/index.html', array(
            'topic' => $topic,
            'messages' => $messages,
            'area' => $area
        ));
    }

    public static function newTopic($areaId) {
        self::check_logged_in();

        $area = Area::find($areaId);

        View::make('topic/new.html', array(
            'area' => $area
        ));
    }

    public static function save($id) {
        self::check_logged_in();

        $params = $_POST;

        $topicAttributes = array(
            'area' => $id,
            'member' => parent::get_user_logged_in()->id,
            'name' => $params['name']);
        $topic = new Topic($topicAttributes);
        $topicErrors = $topic->errors();

        $messageAttributes = array(
            'member' => parent::get_user_logged_in()->id,
            'title' => $params['title'],
            'content' => $params['content'],
            'time' => date('Y-m-d H:i:s'));
        $message = new Message($messageAttributes);
        $messageErrors = $message->errors();

        $errors = array_merge($topicErrors, $messageErrors);

        if (count($errors) == 0) {
            $topic->save();
            $message->topic = $topic->id;
            $message->save();
            Redirect::to('/area/' . $topic->area, array('info' => 'Viestiketju luotu!'));
        } else {
            Redirect::to('/area/' . $id . '/new', array('errors' => $errors, 'attributes' => array_merge($topicAttributes, $messageAttributes)));
        }
    }

    public static function destroy($id) {
        $topic = Topic::find($id);
        self::check_logged_in_as($topic->member);

        $area = Topic::findArea($id);
        $topic->destroy();

        Redirect::to('/area/' . $area, array('info' => 'Viestiketju poistettu!'));
    }
}
