<?php

class Category extends BaseModel {

    public $id, $name, $areas;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName');
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

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Category (name) VALUES (:name) RETURNING id');
        $query->execute(array('name' => $this->name));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function destroy() {
        foreach (Area::findAllIn($this->id) as $area) {
            $area->destroy();
        }

        $query = DB::connection()->prepare('DELETE FROM Category WHERE id = :id');

        $query->execute(array('id' => $this->id));
    }

    public function validateName() {
        $errors = array();

        if (parent::validate_string_length($this->name, 3, 50)) {
            $errors[] = 'Kategorian nimen pituuden pitää olla 3-50 merkkiä!';
        }

        return $errors;
    }

}
