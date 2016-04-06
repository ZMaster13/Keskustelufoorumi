<?php

class Topic extends ExtendedBaseModel {
    public $id, $area, $member, $name;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return parent::getList('Topic');
    }

    public static function find($id) {
        return parent::getOne('Topic', $id);
    }

    protected static function makeOne($row) {
        $topic = new Topic(array(
            'id' => $row['id'],
            'area' => $row['area'],
            'member' => $row['member'],
            'name' => $row['name']
        ));
        
        return $topic;
    }
}