<?php

class Member extends BaseModel {

    public $id, $name, $password, $confirmPassword, $isAdmin;

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
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Member (name, password) '
                . 'VALUES (:name, :password) RETURNING id');

        $query->execute(array('name' => $this->name, 'password' => $this->password));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Member SET '
                . '(name, password) = '
                . '(:name, :password) '
                . 'WHERE id = :id');

        $query->execute(array('id' => $this->id, 'name' => $this->name, 'password' => $this->password));
    }
    
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM MemberTeam WHERE member = :id');
        $query->execute(array('id' => $this->id));

        $query2 = DB::connection()->prepare('DELETE FROM Member WHERE id = :id');
        $query2->execute(array('id' => $this->id));
    }

    public function validateName() {
        $errors = array();

        if (parent::validate_string_length($this->name, 3, 50)) {
            $errors[] = 'Käyttäjän nimen pitää olla 3-50 merkkiä pitkä!';
        }

        return $errors;
    }

    public function validatePassword() {
        $errors = array();

        if (parent::validate_string_length($this->password, 8, 50)) {
            $errors[] = 'Salasanan pitää olla 8-50 merkkiä pitkä!';
        }
        if ($this->password != $this->confirmPassword) {
            $errors[] = 'Annetut salasanat eivät täsmänneet!';
        }
        // TODO: Check if password contains at least one number.
        
        return $errors;
    }

}
