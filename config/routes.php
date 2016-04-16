<?php

$routes->get('/', function() {
    FrontpageController::index();
});
$routes->get('/frontpage', function() {
    FrontpageController::index();
});
$routes->post('/frontpage', function() {
    CategoryController::save();
});
$routes->get('/frontpage/new', function() {
    CategoryController::newCategory();
});
$routes->post('/frontpage/new', function() {
    CategoryController::newCategory();
});

$routes->post('/category/:id', function($id) {
    AreaController::save($id);
});
$routes->get('/category/:id/new', function($id) {
    AreaController::newArea($id);
});
$routes->post('/category/:id/new', function($id) {
    AreaController::newArea($id);
});
$routes->post('/category/:id/destroy', function($id) {
    CategoryController::destroy($id);
});

$routes->get('/area/:id', function($id) {
    AreaController::index($id);
});
$routes->post('/area/:id', function($id) {
    TopicController::save($id);
});
$routes->get('/area/:id/new', function($id) {
    TopicController::newTopic($id);
});
$routes->post('/area/:id/new', function($id) {
    TopicController::newTopic($id);
});
$routes->post('/area/:id/destroy', function($id) {
    AreaController::destroy($id);
});

$routes->get('/topic/:id', function($id) {
    TopicController::index($id);
});
$routes->post('/topic/:id', function($id) {
    MessageController::save($id);
});
$routes->post('/topic/:id/destroy', function($id) {
    TopicController::destroy($id);
});

$routes->get('/message/:id', function($id) {
    MessageController::index($id);
});

$routes->get('/message/:id/edit', function($id) {
    MessageController::edit($id);
});
$routes->post('/message/:id/edit', function($id) {
    MessageController::update($id);
});

$routes->post('/message/:id/destroy', function($id) {
    MessageController::destroy($id);
});


$routes->get('/login', function() {
    UserController::login();
});
$routes->post('/login', function() {
    UserController::handle_login();
});

$routes->get('/logout', function(){
  UserController::logout();
});