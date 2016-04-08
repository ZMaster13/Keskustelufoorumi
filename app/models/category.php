<?php

class Category extends ExtendedBaseModel {
    public $id, $name, $areas;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function find($id) {
        return parent::getOne('Category', $id);
    }
    
    public static function findAll() {
        return parent::getList('Category');
    }

    public static function makeOne($row) {
        $category = new Category(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'areas' => Area::findAllIn($row['id'])
        ));
        
        return $category;
    }
}