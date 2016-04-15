<?php

abstract class ExtendedBaseModel extends BaseModel {
    public function __construct($attributes) {
        parent::__construct($attributes);
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
