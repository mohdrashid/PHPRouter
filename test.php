<?php
//Including the library
include_once('PHPRouter/router.php');
//Initalizing the PHPRouter class
$app = new PHPRouter();
//Routes
$app->get('/', function($params,$body,$header){
  echo "GET request";
});
$app->get('/user', function($params,$body,$header){
  echo json_encode(array("id"=>"1","name"=>"droidhat.com"));
});
$app->post('/', function($params,$body,$header){
  echo "POST request";
});
$app->put('/', function($params,$body,$header){
  echo "PUT request";
});
$app->patch('/', function($params,$body,$header){
  echo "PATCH request";
});
$app->delete('/', function($params,$body,$header){
  echo "DELETE request";
});
//Error Handler
$app->error(function(Exception $e){
  echo 'path not found';
});
//Starting the router
$app->start();
?>
