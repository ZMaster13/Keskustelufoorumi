<?php

class Member extends BaseModel {

    public $id, $name, $password, $isAdmin;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName', 'validatePassword');
    }

    public static function find($id) {
        return parent::getOne('Member', $id);
    }

    public static function findAll() {
        return parent::getList('Member');
    }

    public static function findAllIn($id) {
        return parent::getListIn('Member', 'SELECT Member.* FROM Member, Team, MemberTeam '
                        . 'WHERE MemberTeam.member = Member.id '
                        . 'AND MemberTeam.team = Team.id '
                        . 'AND Team.id = :value', $id);
    }

    public static function findAllNotIn($id) {
        return parent::getListIn('Member', 'SELECT * FROM Member '
                        . 'WHERE Member.id NOT IN ('
                        . 'SELECT Member.id FROM Member, Team, MemberTeam '
                        . 'WHERE MemberTeam.member = Member.id '
                        . 'AND MemberTeam.team = Team.id '
                        . 'AND Team.id = :value)', $id);
    }

    public static function makeOne($row) {
        $id = $row['id'];
        $isAdmin = Member::isAdmin($id);

        $member = new Member(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password'],
            'isAdmin' => $isAdmin
        ));

        return $member;
    }

    public static function getTeams($id) {
        $query = DB::connection()->prepare(
                'SELECT Team.* FROM Member, MemberTeam, Team '
                . 'WHERE MemberTeam.team = Team.id '
                . 'AND MemberTeam.member = Member.id '
                . 'AND Member.id = :id'
        );
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();

        $list = array();

        foreach ($rows as $row) {
            $list[] = Team::makeOne($row);
        }

        return $list;
    }

    public function authenticate($name, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Member WHERE name = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $name, 'password' => $password));
        $row = $query->fetch();

        if ($row) {
            return Member::makeOne($row);
        }

        return null;
    }

    public static function isAdmin($id) {
        $query = DB::connection()->prepare(
                'SELECT Team.admin FROM Team, MemberTeam '
                . 'WHERE Team.id = MemberTeam.team '
                . 'AND MemberTeam.member = :id'
        );

        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();

        if ($rows == null) {
            return false;
        }
        foreach ($rows as $row) {
            if ($row['admin']) {
                return true;
            }
        }

        return false;
    }

    public function validateName() {
        $errors = array();

        if (parent::validate_string_length($this->name, 3)) {
            $errors[] = 'Käyttäjän nimen pitää olla vähintään kolme merkkiä pitkä!';
        }

        return $errors;
    }

    public function validatePassword() {
        $errors = array();

        if (parent::validate_string_length($this->password, 8)) {
            $errors[] = 'Salasanan pitää olla vähintään kahdeksan merkkiä pitkä!';
        }
        // TODO: Check if password contains at least one number.
    }

}
