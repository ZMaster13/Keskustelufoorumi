<?php

class Version extends ExtendedBaseModel {

    public $id, $message, $member, $title, $content, $time;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function find($id) {
        return parent::getOne('Version', $id);
    }

    public static function findAll() {
        return parent::getList('Version');
    }

    public static function makeOne($row) {
        $version = new Version(array(
            'id' => $row['id'],
            'message' => $row['message'],
            'member' => $row['member'],
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

    private static function formatTime($time) {
        
    }

}
