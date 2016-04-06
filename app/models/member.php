<?php

class Member extends ExtendedBaseModel {
    public $id, $name, $password, $salt;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return parent::getList('Member');
    }

    public static function find($id) {
        return parent::getOne('Member', $id);
    }

    protected static function makeOne($row) {
        $member = new Member(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password'],
            'salt' => $row['salt']
        ));
        
        return $member;
    }
}