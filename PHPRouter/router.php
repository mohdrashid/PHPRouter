<?php
/*
{
"Name": "PHPRouter",
"Author": "Mohammed Rashid",
"Website": "https://www.droidhat.com",
"Version": "1.1",
"Release Date": "12 March 2017",
"Last Update": "13 March 2017"
}
*/
include_once('PHPRouter/response.php');
class PHPRouter
{
  private $method= null;
  private $routes= null;
  private $errorFunction=null;
  private $request = null, $currentPath=null;
  private $response=null;
  //Function to get headers related to HTTP,Authentication and REQUEST
  private function getHTTPHeaders() {
     $header = [];
     foreach ($_SERVER as $name => $value) {
       if (preg_match('/^HTTP_/',$name)||preg_match('/^PHP_AUTH_/',$name)||preg_match('/^REQUEST_/',$name)) {
         $header[$name] = $value;
       }
     }
     return $header;
   }
  //Constructor
  public function __construct() {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->response = new Response();
    if(isset($_SERVER['PATH_INFO'])){
      $this->currentPath = $_SERVER['PATH_INFO'];
    }
    $this->routes=array('GET'=>array(),'POST'=>array(),'PUT'=>array(),'DELETE'=>array(),'PATCH'=>array());
    $this->request=array("body"=>$_POST,
    "raw"=>file_get_contents('php://input'),
    "header"=>$this->getHTTPHeaders(),
    "method"=>$_SERVER['REQUEST_METHOD'],
    "params"=>$_GET,
    "files"=>$_FILES,
    "cookies"=>$_COOKIE
  );
  }

  //Register get requests
  public function get($path,$callback){
    $this->routes['GET'][$path]=$callback;
  }
  //Register post requests
  public function post($path,$callback){
    $this->routes['POST'][$path]=$callback;
  }
  //Register put requests
  public function put($path,$callback){
    $this->routes['PUT'][$path]=$callback;
  }
  //Register put requests
  public function patch($path,$callback){
    $this->routes['PATCH'][$path]=$callback;
  }
  //Register delete requests
  public function delete($path,$callback){
    $this->routes['DELETE'][$path]=$callback;
  }
  //Error Handling
  public function error($function){
    $this->errorFunction=$function;
  }
  //Start router
  public function start(){
    //var_dump($this->routes);
    if(isset($this->routes[$this->method][$this->currentPath]))
      return $this->routes[$this->method][$this->currentPath]($this->request,$this->response);
    else if(isset($this->routes[$this->method][$this->currentPath.'/']))
      return $this->routes[$this->method][$this->currentPath.'/']($this->request,$this->response);
    if(isset($this->errorFunction))
      return ($this->errorFunction)(new Exception("Path not found!",400),$this->response);
    echo "Error Path not Found!";
  }
}
?>
