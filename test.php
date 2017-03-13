<?php
//Including the library
include_once('PHPRouter/router.php');
//Initalizing the PHPRouter class
$app = new PHPRouter();
//Routes
$app->get('/', function($request,$response){
  $response->send("GET request");
});
$app->get('/user', function($request,$response){
  $response->json(array("id"=>"1","name"=>"DroidHat","url"=>"http://www.droidhat.com"),200);
});
$app->post('/', function($request,$response){
  $response->send("POST request");
});
$app->put('/', function($request,$response){
  $response->send("PUT request");
});
$app->patch('/', function($request,$response){
  $response->send("PATCH request");
});
$app->delete('/', function($request,$response){
  $response->send("DELETE request");
});
//Error Handler
$app->error(function(Exception $e,$response){
  $response->send('path not found',404);
});
//Starting the router
$app->start();
?>
