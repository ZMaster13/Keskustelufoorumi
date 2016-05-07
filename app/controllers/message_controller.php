<?php

class MessageController extends BaseController {

    public static function index($id) {
        self::check_logged_in();
        
        $message = Message::find($id);
        $topic = Topic::find($message->topic);
        $area = Area::find($topic->area);

        View::make('message/index.html', array(
            'message' => $message,
            'topic' => $topic,
            'area' => $area
        ));
    }
    
    public static function save($id) {
        self::check_logged_in();
        
        $params = $_POST;

        $message = new Message(array(
            'topic' => $id,
            'member' => parent::get_user_logged_in()->id,
            'title' => $params['title'],
            'content' => $params['content'],
            'time' => date('Y-m-d H:i:s')
        ));
        $errors = $message->errors();
        
        if (count($errors) == 0) {
            $message->save();
            Redirect::to('/topic/' . $message->topic, array('info' => 'Viesti lähetetty!'));
        } else {
            Redirect::to('/topic/' . $message->topic, array('errors' => $errors, 'attributes' => $message));
        }
    }
    
    public static function edit($id) {
        $message = Message::find($id);
        
        self::check_logged_in_as($message->member->id);

        View::make('message/edit.html', array(
            'message' => $message
        ));
    }
    
    public static function update($id) {
        $prevMessage = Message::find($id);
        
        self::check_logged_in_as($prevMessage->member);
        
        $params = $_POST;
        
        $attributes = array(
            'id' => $id,
            'topic' => $prevMessage->topic,
            'member' => $prevMessage->member,
            'title' => $params['title'],
            'content' => $params['content'],
            'time' => date('Y-m-d H:i:s'));
        
        $message = new Message($attributes);
        $errors = $message->errors();
        
        if (count($errors) > 0) {
            View::make('message/edit.html', array('errors' => $errors, 'message' => $message));
        }
        else {
            $message->update();
            
            Redirect::to('/topic/' . $message->topic, array('info' => 'Viestin muokkaus onnistui!'));
        }
    }
    
    public static function destroy($id) {
        $message = Message::find($id);
        
        self::check_logged_in_as($message->member->id);
        
        $topic = Message::findTopic($id);
        $message->destroy();
        
        Redirect::to('/topic/' . $topic, array('info' => 'Viesti poistettu!'));
    }
}
