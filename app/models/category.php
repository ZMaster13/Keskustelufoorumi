<?php

class Category extends ExtendedBaseModel {
    public $id, $name;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return parent::getList('Category');
    }

    public static function find($id) {
        return parent::getOne('Category', $id);
    }

    protected static function makeOne($row) {
        $category = new Category(array(
            'id' => $row['id'],
            'name' => $row['name']
        ));
        
        return $category;
    }
}