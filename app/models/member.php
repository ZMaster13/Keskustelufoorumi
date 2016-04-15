<?php

class Member extends ExtendedBaseModel {
    public $id, $name, $password, $salt;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName', 'validatePassword');
    }

    public static function find($id) {
        return parent::getOne('Member', $id);
    }
    
    public static function findAll() {
        return parent::getList('Member');
    }

    public static function makeOne($row) {
        $member = new Member(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password'],
            'salt' => $row['salt']
        ));
        
        return $member;
    }
    
     public function validateName() {
        $errors = array();
        
        if (parent::validate_string_length($this->name, 3)) {
            $errors[] = 'Käyttäjän nimen pitää olla vähintään kolme merkkiä pitkä!';
        }
        
        return $errors;
    }
    
    public function validatePassword() {
        $errors = array();
        
        if (parent::validate_string_length($this->password, 8)) {
            $errors[] = 'Salasanan pitää olla vähintään kahdeksan merkkiä pitkä!';
        }
       // TODO: Check if password contains at least one number.
    }
}