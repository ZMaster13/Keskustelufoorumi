<?php

class Area extends ExtendedBaseModel {
    public $id, $category, $name, $number_of_topics, $number_of_messages, $latest_message;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function find($id) {
        return parent::getOne('Area', $id);
    }
    
    public static function findAll() {
        return parent::getList('Area');
    }
    
    public static function findAllIn($id) {
        return parent::getListIn('Area', 'category', $id);
    }
    
    public static function countTopicsIn($id) {
        $topics = parent::countIn('SELECT COUNT(Topic.id) AS count '
                . 'FROM Area, Topic '
                . 'WHERE Topic.area = Area.id '
                . 'AND Area.id = :id', $id);
        
        return $topics;
    }
    
    public static function countMessagesIn($id) {
        $messages = parent::countIn('SELECT COUNT(Message.id) AS count '
                . 'FROM Area, Topic, Message '
                . 'WHERE Topic.area = Area.id '
                . 'AND Message.topic = Topic.id '
                . 'AND Area.id = :id', $id);
        
        return $messages;
    }
    
    public static function findLatestIn($id) {
        $message = parent::getLatestIn('Message', 'SELECT Message.* '
                . 'FROM Area, Topic, Message, Version '
                . 'WHERE Topic.area = Area.id '
                . 'AND Message.topic = Topic.id '
                . 'AND Version.message = Message.id '
                . 'AND Area.id = :id '
                . 'ORDER BY Version.time DESC '
                . 'LIMIT 1', $id);
        
        return $message;
    }

    public static function makeOne($row) {
        $area = new Area(array(
            'id' => $row['id'],
            'category' => $row['category'],
            'name' => $row['name'],
            'number_of_topics' => Area::countTopicsIn($row['id']),
            'number_of_messages' => Area::countMessagesIn($row['id']),
            'latest_message' => Area::findLatestIn($row['id'])
        ));
        
        return $area;
    }
}