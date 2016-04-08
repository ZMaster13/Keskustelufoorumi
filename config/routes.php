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
  
  $routes->get('/editmessage', function() {
    HelloWorldController::editmessage();
  });