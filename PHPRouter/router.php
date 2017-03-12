<?php
/*
Name: PHPRouter,
Author: Mohammed Rashid,
Website: https://www.droidhat.com,
Version: 1.0,
Release Date: 12 March 2017
*/
class PHPRouter
{
  public $method= null;
  public $routes= null;
  public $errorFunction=null;
  public $body = null, $headers=null;
  //Constructor
  public function __construct() {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->request = null;
    if(isset($_SERVER['PATH_INFO'])){
      $this->request = $_SERVER['PATH_INFO'];
    }
    $this->routes=array('GET'=>array(),'POST'=>array(),'PUT'=>array(),'DELETE'=>array(),'PATCH'=>array());
    $this->body=file_get_contents('php://input');
    $this->headers=apache_request_headers();
  }
  //Register get requests
  public function get($path,$callback){
    if($path=="/"){
      $this->routes['GET'][""]=$callback;
    }
    $this->routes['GET'][$path]=$callback;
  }
  //Register post requests
  public function post($path,$callback){
    if($path=="/"){
      $this->routes['POST'][""]=$callback;
    }
    $this->routes['POST'][$path]=$callback;
  }
  //Register put requests
  public function put($path,$callback){
    if($path=="/"){
      $this->routes['PUT'][""]=$callback;
    }
    $this->routes['PUT'][$path]=$callback;
  }
  //Register put requests
  public function patch($path,$callback){
    if($path=="/"){
      $this->routes['PATCH'][""]=$callback;
    }
    $this->routes['PATCH'][$path]=$callback;
  }
  //Register delete requests
  public function delete($path,$callback){
    if($path=="/"){
      $this->routes['DELETE'][""]=$callback;
    }
    $this->routes['DELETE'][$path]=$callback;
  }
  //Error Handling
  public function error($function){
    $this->errorFunction=$function;
  }
  //Start router
  public function start(){
    //var_dump($this->routes);
    if(isset($this->routes[$this->method][$this->request]))
      return $this->routes[$this->method][$this->request]($_GET,$this->body,$this->headers);
    if(isset($this->errorFunction))
      return ($this->errorFunction)(new Exception("Path not found!",400));
    echo "Error Path not Found!";
  }
}
?>
