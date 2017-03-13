<?php namespace PHPRouter;
require_once('response.php');
class Router
{
  //Method of Request
  private $method= null;
  //Route Array
  private $routes= [];
  //Callback error function
  private $errorFunction=null;
  private $request = null, $currentPath=null;
  private $response=null;
  private $CharsAllowed = '[a-zA-Z0-9\_\-]+';

  //Constructor
  public function __construct() {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->response = new Response();
    if(isset($_SERVER['PATH_INFO'])){
      $this->currentPath = $_SERVER['PATH_INFO'];
    }
    $this->routes=['GET'=>[],'POST'=>[],'PUT'=>[],'DELETE'=>[],'PATCH'=>[],'ANY'=>[]];
    $this->request=["body"=>$_POST,
    "raw"=>file_get_contents('php://input'),
    "header"=>$this->getHTTPHeaders(),
    "method"=>$_SERVER['REQUEST_METHOD'],
    "params"=>$_GET,
    "files"=>$_FILES,
    "cookies"=>$_COOKIE
  ];
}

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
//Regex Checker
private function Regex($path){
  //Check for invalid pattern
  if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $path))
    return false;
    // Turn "(/)" into "/?"
    $path = preg_replace('#\(/\)#', '/?', $path);
     //Replace parameters
    $path = preg_replace('/:(' . $this->CharsAllowed . ')/','(?<$1>' . $this->CharsAllowed . ')',$path);
    $path = preg_replace('/{(' . $this->CharsAllowed . ')}/','(?<$1>' . $this->CharsAllowed . ')', $path);
    // Add start and end matching
    $patternAsRegex = "@^" . $path . "$@D";
    return $patternAsRegex;
  }

  //Register get requests
  public function get($path,$callback){
    $this->routes['GET'][$this->Regex($path)]=$callback;
  }
  //Register post requests
  public function post($path,$callback){
    $this->routes['POST'][$this->Regex($path)]=$callback;
  }
  //Register put requests
  public function put($path,$callback){
    $this->routes['PUT'][$this->Regex($path)]=$callback;
  }
  //Register put requests
  public function patch($path,$callback){
    $this->routes['PATCH'][$this->Regex($path)]=$callback;
  }
  //Register delete requests
  public function delete($path,$callback){
    $this->routes['DELETE'][$this->Regex($path)]=$callback;
  }
  //Register any requests
  public function any($path,$callback){
    $this->routes['ANY'][$this->Regex($path)]=$callback;
  }
  //Error Handling
  public function error($function){
    $this->errorFunction=$function;
  }
  //Function to get appropriate callback for the route
  public function getCallBack($method){
    if(!isset($this->routes[$method]))
      return null;
    foreach($this->routes[$method] as $name => $value){
      if (preg_match($name,$this->currentPath,$matches)||preg_match($name,$this->currentPath."/",$matches)) {
        // Get elements with string keys from matches
        $params = array_intersect_key($matches,array_flip(array_filter(array_keys($matches), 'is_string')));
        foreach($params as $key=> $value)
          $this->request["params"][$key]=$value;
        return $this->routes[$method][$name];
      }
    }
  }
  //Start router
  public function start(){
    $callback=$this->getCallBack('ANY');
    if($callback)
      return $callback($this->request,$this->response);
    $callback=$this->getCallBack($this->method);
    if($callback)
        return $callback($this->request,$this->response);
    if(isset($this->errorFunction))
      return ($this->errorFunction)(new Exception("Path not found!",400),$this->response);
    $this->response.send("Error, Path not Found!",404);
  }
}
?>
