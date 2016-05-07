<?php

class Team extends BaseModel {

    public $id, $name, $admin, $members, $numberOfMembers;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName');
    }

    public static function find($id) {
        return parent::getOne('Team', $id);
    }

    public static function findAll() {
        return parent::getList('Team');
    }

    public static function makeOne($row) {
        $members = Member::findAllIn($row['id']);
        $numberOfMembers = sizeof($members);

        $team = new Team(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'admin' => $row['admin'],
            'members' => $members,
            'numberOfMembers' => $numberOfMembers
        ));

        return $team;
    }

    public static function addMember($teamId, $memberId) {
        $query = DB::connection()->prepare('INSERT INTO MemberTeam (member, team) '
                . 'VALUES (:member, :team)');
        $query->execute(array('member' => $memberId, 'team' => $teamId));
    }

    public static function removeMember($teamId, $memberId) {
        $query = DB::connection()->prepare('DELETE FROM MemberTeam '
                . 'WHERE member = :member AND team = :team');
        $query->execute(array('member' => $memberId, 'team' => $teamId));
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Team (name, admin) '
                . 'VALUES (:name, :admin) RETURNING id');

        $query->execute(array('name' => $this->name, 'admin' => $this->convertBoolean($this->admin)));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Team SET '
                . '(name, admin) = '
                . '(:name, :admin) '
                . 'WHERE id = :id');

        $query->execute(array('id' => $this->id, 'name' => $this->name, 'admin' => $this->convertBoolean($this->admin)));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM MemberTeam WHERE team = :id');
        $query->execute(array('id' => $this->id));

        $query2 = DB::connection()->prepare('DELETE FROM Team WHERE id = :id');
        $query2->execute(array('id' => $this->id));
    }

    public function validateName() {
        $errors = array();

        if (parent::validate_string_length($this->name, 3, 50)) {
            $errors[] = 'Ryhmän nimen pitää olla 3-50 merkkiä pitkä!';
        }

        return $errors;
    }

    private function convertBoolean($bool) {
        $booleanString = "";
        if ($bool) {
            $booleanString = "true";
        } else {
            $booleanString = "false";
        }
        return $booleanString;
    }

}
