<?php

class Area extends ExtendedBaseModel {
    public $id, $category, $name;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return parent::getList('Area');
    }

    public static function find($id) {
        return parent::getOne('Area', $id);
    }

    protected static function makeOne($row) {
        $area = new Area(array(
            'id' => $row['id'],
            'category' => $row['category'],
            'name' => $row['name']
        ));
        
        return $area;
    }
}