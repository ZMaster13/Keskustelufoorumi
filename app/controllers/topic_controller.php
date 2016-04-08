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
    
    public static function save($id) {
        $params = $_POST;
        
        $message = new Message(array(
            'topic' => $id,
            'member' => 1 // TODO
        ));
        $message->save();
        
        $version = new Version(array(
            'message' => $message->id,
            'member' => 1, // TODO
            'title' => $params['title'],
            'content' => $params['content'],
            'time' => date('Y-m-d H:i:s')
        ));
        $version->save();
        
        $message->latest_version = $version;
        
        Redirect::to('/topic/' . $id, array('message' => 'Viesti lähetetty!'));
    }
}