<?php

class Message extends ExtendedBaseModel {
    public $id, $topic, $member;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return parent::getList('Message');
    }

    public static function find($id) {
        return parent::getOne('Message', $id);
    }

    protected static function makeOne($row) {
        $message = new Message(array(
            'id' => $row['id'],
            'topic' => $row['topic'],
            'member' => $row['member']
        ));
        
        return $message;
    }
}