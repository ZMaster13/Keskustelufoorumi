<?php

class MessageController extends BaseController {
    public static function index($id) {
        $versions = Version::findAllIn($id);
        $message = Message::find($id);
        $topic = Topic::find($message->topic);
        $area = Area::find($topic->area);
        
        View::make('message/index.html', array(
            'versions' => $versions,
            'message' => $message,
            'topic' => $topic,
            'area' => $area
        ));
    }
    
    public static function edit($id) {
        $message = Message::find($id);
        
        View::make('message/edit.html', array(
            'message' => $message
        ));
    }
    
    public static function save($id) {
        $params = $_POST;
        
        $message = Message::find($id);
        
        $version = new Version(array(
            'message' => $id,
            'member' => 1, // TODO
            'title' => $params['title'],
            'content' => $params['content'],
            'time' => date('Y-m-d H:i:s')
        ));
        $version->save();
        
        $message->latest_version = $version;
        
        Redirect::to('/topic/' . $message->topic, array('message' => 'Viesti lähetetty!'));
    }
}