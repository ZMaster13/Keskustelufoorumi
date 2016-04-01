<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  
  $routes->get('/frontpage', function() {
    HelloWorldController::frontpage();
  });
  
  $routes->get('/area', function() {
    HelloWorldController::area();
  });
  
  $routes->get('/topic', function() {
    HelloWorldController::topic();
  });
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });
  
  $routes->get('/editmessage', function() {
    HelloWorldController::editmessage();
  });