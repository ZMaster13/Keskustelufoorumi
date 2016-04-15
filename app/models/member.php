<?php

class Member extends ExtendedBaseModel {

    public $id, $name, $password, $teams, $isAdmin;

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

    public static function makeOne($row) {
        $id = $row['id'];
        $teams = Member::getTeams($id);
        $isAdmin = Member::isAdmin($teams);
        
        $member = new Member(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password'],
            'teams' => $teams,
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
    
    public static function isAdmin($teams) {
        foreach ($teams as $team) {
            if ($team->admin) {
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
