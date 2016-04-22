<?php

class TeamController extends BaseController {

    public static function listTeams() {
        self::check_logged_in();

        $teams = Team::findAll();
        View::make('team/list.html', array(
            'teams' => $teams
        ));
    }

    public static function showTeam($id) {
        self::check_logged_in();

        $team = Team::find($id);
        $otherMembers = Member::findAllNotIn($id);
        View::make('team/index.html', array(
            'team' => $team,
            'otherMembers' => $otherMembers
        ));
    }

    public static function newTeam() {
        self::check_logged_in_as_admin();

        View::make('team/new.html');
    }

    public static function save() {
        self::check_logged_in_as_admin();

        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'admin' => isset($params['isAdmin'])
        );
        $team = new Team($attributes);
        $errors = $team->errors();

        if (count($errors) == 0) {
            $team->save();
            Redirect::to('/groups', array('info' => 'Ryhmä luotu!'));
        } else {
            Redirect::to('/group/create', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        self::check_logged_in_as_admin();
        
        $team = Team::find($id);

        self::check_logged_in_as_admin();

        View::make('team/edit.html', array(
            'team' => $team
        ));
    }

    public static function update($id) {
        self::check_logged_in_as_admin();

        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'admin' => isset($params['isAdmin'])
        );

        $team = new Team($attributes);
        $errors = $team->errors();

        if (count($errors) > 0) {
            View::make('team/edit.html', array('errors' => $errors, 'team' => $team));
        } else {
            $team->update();

            Redirect::to('/groups', array('info' => 'Ryhmän muokkaus onnistui!'));
        }
    }

    public static function destroy($id) {
        self::check_logged_in_as_admin();

        $team = new Team(array('id' => $id));
        $team->destroy();

        Redirect::to('/groups', array('info' => 'Ryhmä poistettu!'));
    }

    public static function add($teamId, $memberId) {
        self::check_logged_in_as_admin();

        Team::addMember($teamId, $memberId);

        Redirect::to('/group/' . $teamId, array('info' => 'Käyttäjä lisätty ryhmään onnistuneesti!'));
    }

    public static function remove($teamId, $memberId) {
        self::check_logged_in_as_admin();

        Team::removeMember($teamId, $memberId);

        Redirect::to('/group/' . $teamId, array('info' => 'Käyttäjä poistettu ryhmästä onnistuneesti!'));
    }

    public function validateName() {
        $errors = array();

        if (parent::validate_string_length($this->name, 3)) {
            $errors[] = 'Ryhmän nimen pituuden pitää olla vähintään kolme merkkiä!';
        }

        return $errors;
    }

}
