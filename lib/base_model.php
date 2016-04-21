<?php

abstract class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();

        foreach ($this->validators as $validator) {
            $errors = array_merge($errors, $this->{$validator}());
        }

        return $errors;
    }

    public function validate_string_length($string, $length) {

        if ($string == '' || $string == null) {
            return true;
        }
        if (strlen($string) < $length) {
            return true;
        }
        
        return false;
    }
    
    public static function getOne($table, $id) {
        $query = DB::connection()->prepare('SELECT * FROM ' . $table . ' WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            return $table::makeOne($row);
        }

        return null;
    }

    public static function getList($table) {
        $query = DB::connection()->prepare('SELECT * FROM ' . $table);
        $query->execute();
        $rows = $query->fetchAll();

        $list = array();

        foreach ($rows as $row) {
            $list[] = $table::makeOne($row);
        }

        return $list;
    }
    
    public static function getListIn($table, $sql, $value) {
        $query = DB::connection()->prepare($sql);
        $query->execute(array('value' => $value));
        $rows = $query->fetchAll();

        $list = array();

        foreach ($rows as $row) {
            $list[] = $table::makeOne($row);
        }

        return $list;
    }
    
    public static function countIn($sql, $id) {
        $query = DB::connection()->prepare($sql);
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        if ($row) {
            return $row['count'];
        }
        
        return 0;
    }
    
    public static function getLatestIn($table, $sql, $id) {
        $query = DB::connection()->prepare($sql);
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        if ($row) {
            return $table::makeOne($row);
        }
        
        return null;
    }

    public static abstract function find($id);
    
    public static abstract function findAll();

    public static abstract function makeOne($row);
}
