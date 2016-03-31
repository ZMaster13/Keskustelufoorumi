<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  
  $routes->get('/frontpage', function() {
    HelloWorldController::frontpage();
  });
  
  $routes->get('/topic', function() {
    HelloWorldController::topic();
  });
  
  $routes->get('/messages', function() {
    HelloWorldController::messages();
  });
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });