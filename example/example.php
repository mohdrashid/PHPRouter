<?php
  //Including the library
  require_once "..\PHPRouter\Router.php";

  use PHPRouter\Router;
  //Initalizing the PHPRouter class
  $app = new Router();
  /*****
  Routes
  ******/
  //All HTTP request for /employee will come here
  $app->any('/employee', function ($request, $response) {
      $response->send("ANY request");
  });
  //All GET request for /user will come here
  $app->get('/user', function ($request, $response) {
      $response->json(["id"=>"1","name"=>"DroidHat","url"=>"http://www.droidhat.com"], 200);
  });
  //All POST request for /:id will come here; where id is any alphanumeral
  $app->post('/:id', function ($request, $response) {
      $response->json(["id"=>($request["params"]["id"])], 200);
  });
  //All POST request for /:id/:name will come here; where id and name are any alphanumeral
  $app->post('/:id/:name', function ($request, $response) {
      $response->json(["id"=>($request["params"]["id"]),"name"=>($request["params"]["name"])], 200);
  });
  $app->put('/', function ($request, $response) {
      $response->send("PUT request");
  });
  $app->patch('/', function ($request, $response) {
      $response->send("PATCH request");
  });
  $app->delete('/', function ($request, $response) {
      $response->send("DELETE request");
  });
  //Error Handler
  $app->error(function (Exception $e, $response) {
      $response->send('path not found', 404);
  });
  //Starting the router
  $app->start();
