<?php

class Message extends ExtendedBaseModel {

    public $id, $topic, $member, $title, $content, $time;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateTitle', 'validateContent');
    }

    public static function find($id) {
        return parent::getOne('Message', $id);
    }

    public static function findAll() {
        return parent::getList('Message');
    }

    public static function findAllIn($id) {
        return parent::getListIn('Message',
                'SELECT * FROM Message '
                . 'WHERE topic = :value '
                . 'ORDER BY Message.time ASC',
                $id);
    }
    
    public static function findTopic($id) {
        $query = DB::connection()->prepare('SELECT * FROM Message '
                . 'WHERE Message.id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        return $row['topic'];
    }

    public static function makeOne($row) {
        $message = new Message(array(
            'id' => $row['id'],
            'topic' => $row['topic'],
            'member' => Member::find($row['member']),
            'title' => $row['title'],
            'content' => $row['content'],
            'time' => Message::formatTime($row['time'])
        ));

        return $message;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Message '
                . '(topic, member, title, content, time) '
                . 'VALUES (:topic, :member, :title, :content, :time) RETURNING id');
        
        $query->execute(array('topic' => $this->topic, 'member' => $this->member,
            'title' => $this->title, 'content' => $this->content, 'time' => $this->time));
        
        
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Message SET '
                . '(topic, member, title, content) = '
                . '(:topic, :member, :title, :content) '
                . 'WHERE id = :id');
        
        $query->execute(array('id' => $this->id, 'topic' => $this->topic, 'member' => $this->member,
            'title' => $this->title, 'content' => $this->content));
    }
    
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Message WHERE id = :id');
        
        $query->execute(array('id' => $this->id));
    }
    
    public function validateTitle() {
        $errors = array();
        
        if (parent::validate_string_length($this->title, 3)) {
            $errors[] = 'Viestin otsikon pitää olla vähintään kolme merkkiä pitkä!';
        }
        
        return $errors;
    }
    
    public function validateContent() {
        $errors = array();
        
        if (parent::validate_string_length($this->content, 1)) {
            $errors[] = 'Viestin sisältö ei saa olla tyhjä!';
        }
        
        return $errors;
    }

    private static function formatTime($time) {
        return $time;
    }
}
