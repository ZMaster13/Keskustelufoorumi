<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/sandbox', function() {
    HelloWorldController::sandbox();
});

$routes->get('/frontpage', function() {
    FrontpageController::index();
});

$routes->get('/area/:id', function($id) {
    AreaController::index($id);
});

$routes->get('/topic/:id', function($id) {
    TopicController::index($id);
});
$routes->post('/topic/:id', function($id) {
    TopicController::save($id);
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/message/:id', function($id) {
    MessageController::index($id);
});

$routes->get('/message/edit/:id', function($id) {
    MessageController::edit($id);
});
$routes->post('/message/edit/:id', function($id) {
    MessageController::save($id);
});
