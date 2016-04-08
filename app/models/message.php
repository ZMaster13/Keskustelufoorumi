<?php

class Message extends ExtendedBaseModel {

    public $id, $topic, $member, $latest_version;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function find($id) {
        return parent::getOne('Message', $id);
    }

    public static function findAll() {
        return parent::getList('Message');
    }

    public static function findAllIn($id) {
        return parent::getListIn('Message', 'topic', $id);
    }

    public static function findLatestIn($id) {
        $version = parent::getLatestIn('Version', 'SELECT * '
                        . 'FROM Message, Version '
                        . 'WHERE Version.message = Message.id '
                        . 'AND Message.id = :id '
                        . 'ORDER BY Version.time DESC '
                        . 'LIMIT 1', $id);

        return $version;
    }

    public static function makeOne($row) {
        $message = new Message(array(
            'id' => $row['id'],
            'topic' => $row['topic'],
            'member' => Member::find($row['member']),
            'latest_version' => Message::findLatestIn($row['id'])
        ));

        return $message;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Message (topic, member) VALUES (:topic, :member) RETURNING id');
        $query->execute(array('topic' => $this->topic, 'member' => $this->member));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

}
