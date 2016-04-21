<?php

class Version extends BaseModel {

    public $id, $message, $member, $title, $content, $time;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateTitle', 'validateContent');
    }

    public static function find($id) {
        return parent::getOne('Version', $id);
    }

    public static function findAll() {
        return parent::getList('Version');
    }
    
    public static function findAllIn($id) {
        return parent::getListIn('Version', 'message', $id);
    }

    public static function makeOne($row) {
        $version = new Version(array(
            'id' => $row['id'],
            'message' => $row['message'],
            'member' => Member::find($row['member']),
            'title' => $row['title'],
            'content' => $row['content'],
            'time' => $row['time']
        ));

        return $version;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Version '
                . '(message, member, title, content, time) '
                . 'VALUES (:message, :member, :title, :content, :time) RETURNING id');

        $query->execute(array('message' => $this->message, 'member' => $this->member,
            'title' => $this->title, 'content' => $this->content, 'time' => $this->time));

        $row = $query->fetch();

        $this->id = $row['id'];
    }
    
    public function validateTitle() {
        $errors = array();
        
        if (parent::validate_string_length($this->title, 3)) {
            $errors[] = 'Otsikon pituuden pitää olla vähintään kolme merkkiä!';
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
        
    }
}
