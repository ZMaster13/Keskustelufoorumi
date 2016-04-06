<?php

class Version extends ExtendedBaseModel {
    public $id, $message, $member, $title, $content, $time;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return parent::getList('Version');
    }

    public static function find($id) {
        return parent::getOne('Version', $id);
    }

    protected static function makeOne($row) {
        $version = new Version(array(
            'id' => $row['id'],
            'message' => $row['message'],
            'member' => $row['member'],
            'title' => $row['title'],
            'content' => $row['content'],
            'time' => $row['time']
        ));
        
        return $version;
    }
}