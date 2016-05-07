<?php

class Topic extends BaseModel {

    public $id, $area, $member, $name, $number_of_messages, $latest_message;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName');
    }

    public static function find($id) {
        return parent::getOne('Topic', $id);
    }

    public static function findAll() {
        return parent::getList('Topic');
    }

    public static function findAllIn($id) {
        return parent::getListIn('Topic', 'SELECT * FROM Topic '
                        . 'WHERE area = :value', $id);
    }

    public static function findByTitle($title) {
        return parent::getListIn('Topic', 'SELECT * FROM Topic '
                        . 'WHERE name LIKE :value', $title);
    }

    public static function findByWriter($writer) {
        return parent::getListIn('Topic', 'SELECT Topic.* FROM Topic, Member '
                        . 'WHERE Topic.member = Member.id '
                        . 'AND Member.name LIKE :value', $writer);
    }

    public static function findByTime($time) {
        return parent::getListIn('Topic', 'SELECT DISTINCT Topic.* FROM Topic, Message '
                        . 'WHERE message.topic = Topic.id '
                        . 'AND Message.time >= :value', $time);
    }

    public static function countMessagesIn($id) {
        $messages = parent::countIn('SELECT COUNT(Message.id) AS count '
                        . 'FROM Topic, Message '
                        . 'WHERE Message.topic = Topic.id '
                        . 'AND Topic.id = :id', $id);

        return $messages;
    }

    public static function findLatestIn($id) {
        $message = parent::getLatestIn('Message', 'SELECT Message.* '
                        . 'FROM Topic, Message '
                        . 'WHERE Message.topic = Topic.id '
                        . 'AND Topic.id = :id '
                        . 'ORDER BY Message.time DESC '
                        . 'LIMIT 1', $id);

        return $message;
    }

    public static function findArea($id) {
        $query = DB::connection()->prepare('SELECT * FROM Topic '
                . 'WHERE Topic.id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        return $row['area'];
    }

    public static function makeOne($row) {
        $topic = new Topic(array(
            'id' => $row['id'],
            'area' => $row['area'],
            'member' => Member::find($row['member']),
            'name' => $row['name'],
            'number_of_messages' => Topic::countMessagesIn($row['id']),
            'latest_message' => Topic::findLatestIn($row['id'])
        ));

        return $topic;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Topic '
                . '(area, member, name) '
                . 'VALUES (:area, :member, :name) RETURNING id');

        $query->execute(array('area' => $this->area,
            'member' => $this->member, 'name' => $this->name));


        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function destroy() {
        foreach (Message::findAllIn($this->id) as $message) {
            $message->destroy();
        }

        $query = DB::connection()->prepare('DELETE FROM Topic WHERE id = :id');

        $query->execute(array('id' => $this->id));
    }

    public function validateName() {
        $errors = array();

        if (parent::validate_string_length($this->name, 3, 50)) {
            $errors[] = 'Viestiketjun nimen pituuden pitää olla 3-50 merkkiä!';
        }

        return $errors;
    }

}
