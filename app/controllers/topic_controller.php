<?php

class TopicController extends BaseController {

    public static function index($id) {
        $topic = Topic::find($id);
        $messages = Message::findAllIn($id);
        $area = Area::find($topic->area);
        View::make('topic/index.html', array(
            'topic' => $topic,
            'messages' => $messages,
            'area' => $area
        ));
    }
    
    public static function newTopic($areaId) {
        $area = Area::find($areaId);
        
        View::make('topic/new.html', array(
            'area' => $area
        ));
    }
    
    public static function save($id) {
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
            Redirect::to('/area/' . $topic->area, array('info' => 'Viestialue luotu!'));
        } else {
            Redirect::to('/area/' . $id . '/new', array('errors' => $errors, 'attributes' => array_merge($topicAttributes, $messageAttributes)));
        }
    }
    
    public static function destroy($id) {
        $topic = new Topic(array('id' => $id));
        $area = Topic::findArea($id);
        $topic->destroy();
        
        Redirect::to('/area/' . $area, array('info' => 'Viestiketju poistettu!'));
    }
}
