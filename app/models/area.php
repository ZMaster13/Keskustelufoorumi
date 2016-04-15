<?php

class Area extends ExtendedBaseModel {
    public $id, $category, $name, $description, $number_of_topics, $number_of_messages, $latest_message;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName');
    }

    public static function find($id) {
        return parent::getOne('Area', $id);
    }
    
    public static function findAll() {
        return parent::getList('Area');
    }
    
    public static function findAllIn($id) {
        return parent::getListIn('Area',
                'SELECT * FROM Area '
                . 'WHERE category = :value',
                $id);
    }
    
    public static function countTopicsIn($id) {
        $topics = parent::countIn('SELECT COUNT(Topic.id) AS count '
                . 'FROM Area, Topic '
                . 'WHERE Topic.area = Area.id '
                . 'AND Area.id = :id', $id);
        
        return $topics;
    }
    
    public static function countMessagesIn($id) {
        $messages = parent::countIn('SELECT COUNT(Message.id) AS count '
                . 'FROM Area, Topic, Message '
                . 'WHERE Topic.area = Area.id '
                . 'AND Message.topic = Topic.id '
                . 'AND Area.id = :id', $id);
        
        return $messages;
    }
    
    public static function findLatestIn($id) {
        $message = parent::getLatestIn('Message', 'SELECT Message.* '
                . 'FROM Area, Topic, Message '
                . 'WHERE Topic.area = Area.id '
                . 'AND Message.topic = Topic.id '
                . 'AND Area.id = :id '
                . 'ORDER BY Message.time DESC '
                . 'LIMIT 1', $id);
        
        return $message;
    }

    public static function makeOne($row) {
        $area = new Area(array(
            'id' => $row['id'],
            'category' => $row['category'],
            'name' => $row['name'],
            'description' => $row['description'],
            'number_of_topics' => Area::countTopicsIn($row['id']),
            'number_of_messages' => Area::countMessagesIn($row['id']),
            'latest_message' => Area::findLatestIn($row['id'])
        ));
        
        return $area;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Area (category, name, description) '
                . 'VALUES (:category, :name, :description) RETURNING id');
        $query->execute(array('category' => $this->category,
            'name' => $this->name, 'description' => $this->description));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function destroy() {
        foreach (Topic::findAllIn($this->id) as $topic) {
            $topic->destroy();
        }

        $query = DB::connection()->prepare('DELETE FROM Area WHERE id = :id');

        $query->execute(array('id' => $this->id));
    }
    
    public function validateName() {
        $errors = array();
        
        if (parent::validate_string_length($this->name, 3)) {
            $errors[] = 'Alueen nimen pituuden pitää olla vähintään kolme merkkiä!';
        }
        
        return $errors;
    }
    
    public function validateDescription() {
        $errors = array();
        
        if (parent::validate_string_length($this->description, 3)) {
            $errors[] = 'Alueen kuvauksen pituuden pitää olla vähintään kolme merkkiä!';
        }
        
        return $errors;
    }
}