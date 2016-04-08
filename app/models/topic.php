<?php

class Topic extends ExtendedBaseModel {
    public $id, $area, $member, $name, $number_of_messages, $latest_message;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function find($id) {
        return parent::getOne('Topic', $id);
    }
    
    public static function findAll() {
        return parent::getList('Topic');
    }
    
    public static function findAllIn($id) {
        return parent::getListIn('Topic', 'area', $id);
    }
    
    public static function countMessagesIn($id) {
        $messages = parent::countIn('SELECT COUNT(Message.id) AS count '
                . 'FROM Topic, Message '
                . 'WHERE Message.topic = Topic.id '
                . 'AND Topic.id = :id', $id);
        
        return $messages;
    }
    
    public static function findLatestIn($id) {
        $message = parent::getLatestIn('Message', 'SELECT Message.* '
                . 'FROM Topic, Message, Version '
                . 'WHERE Message.topic = Topic.id '
                . 'AND Version.message = Message.id '
                . 'AND Topic.id = :id '
                . 'ORDER BY Version.time DESC '
                . 'LIMIT 1', $id);
        
        return $message;
    }

    public static function makeOne($row) {
        $topic = new Topic(array(
            'id' => $row['id'],
            'area' => $row['area'],
            'member' => $row['member'],
            'name' => $row['name'],
            'number_of_messages' => Topic::countMessagesIn($row['id']),
            'latest_message' => Topic::findLatestIn($row['id'])
        ));
        
        return $topic;
    }
}