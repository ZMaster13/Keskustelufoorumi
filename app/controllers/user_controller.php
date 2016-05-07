<?php

class UserController extends BaseController {

    public static function login() {
        self::check_not_logged_in();
        
        View::make('member/login.html');
    }

    public static function handle_login() {
        self::check_not_logged_in();

        $params = $_POST;

        $member = Member::authenticate($params['username'], $params['password']);

        if (!$member) {
            View::make('member/login.html', array('errors' => array('Väärä käyttäjätunnus tai salasana!'), 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $member->id;

            Redirect::to('/', array('info' => 'Tervetuloa takaisin ' . $member->name . '!'));
        }
    }

    public static function logout() {
        self::check_logged_in();
        
        $_SESSION['user'] = null;
        Redirect::to('/login', array('info' => 'Olet kirjautunut ulos'));
    }

    public static function listUsers() {
        self::check_logged_in_as_admin();

        $members = Member::findAll();
        View::make('member/list.html', array(
            'members' => $members
        ));
    }
    
    public static function newUser() {
        self::check_logged_in_as_admin();

        View::make('member/new.html');
    }
    
    public static function save() {
        self::check_logged_in_as_admin();

        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'password' => $params['password'],
            'confirmPassword' => $params['confirmPassword']
        );
        $member = new Member($attributes);
        $errors = $member->errors();

        if (count($errors) == 0) {
            $member->save();
            Redirect::to('/users', array('info' => 'Käyttäjä luotu!'));
        } else {
            Redirect::to('/user/create', array('errors' => $errors, 'attributes' => $attributes));
        }
    }
    
    public static function edit($id) {
        self::check_logged_in_as($id);

        $member = Member::find($id);
        View::make('member/edit.html', array(
            'member' => $member,
            'previousName' => $member->name
        ));
    }
    
    public static function update($id) {
        self::check_logged_in_as($id);

        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'password' => $params['password'],
            'confirmPassword' => $params['confirmPassword']
        );

        $member = new Member($attributes);
        $errors = $member->errors();

        if (count($errors) > 0) {
            $previousName = Member::find($id)->name;
            View::make('member/edit.html', array('errors' => $errors, 'member' => $member, 'previousName' => $previousName));
        } else {
            $member->update();

            Redirect::to('/frontpage', array('info' => 'Käyttäjän muokkaus onnistui!'));
        }
    }
    
    public static function destroy($id) {
        self::check_logged_in_as_admin();

        $member = new Member(array('id' => $id));
        $member->destroy();

        Redirect::to('/users', array('info' => 'Käyttäjä poistettu!'));
    }
}
