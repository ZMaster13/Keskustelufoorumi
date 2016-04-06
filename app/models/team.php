<?php

class Team extends ExtendedBaseModel {
    public $id, $name, $permissions;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return parent::getList('Team');
    }

    public static function find($id) {
        return parent::getOne('Team', $id);
    }

    protected static function makeOne($row) {
        $team = new Team(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'permissions' => $row['permissions']
        ));
        
        return $team;
    }
}