<?php

class Team extends ExtendedBaseModel {
    public $id, $name, $permissions;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName');
    }

    public static function find($id) {
        return parent::getOne('Team', $id);
    }
    
    public static function findAll() {
        return parent::getList('Team');
    }

    public static function makeOne($row) {
        $team = new Team(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'permissions' => $row['permissions']
        ));
        
        return $team;
    }
    
    public function validateName() {
        $errors = array();
        
        if (parent::validate_string_length($this->name, 3)) {
            $errors[] = 'Ryhmän nimen pitää olla vähintään kolme merkkiä pitkä!';
        }
        
        return $errors;
    }
}