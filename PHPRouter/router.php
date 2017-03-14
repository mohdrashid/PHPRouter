<?php namespace PHPRouter;

require_once "Response.php";

use PHPRouter\Response;
use Exception;

class Router
{
    //Method of Request
  private $method= null;
  //Route Array
  private $routes= [];
  //Callback error function
  private $errorFunction=null;
  //Request Array to store Request related information
  private $request = null;
  //Current Request PATH
  private $currentPath=null;
  //Object of class Response
  private $response=null;
  //Regex Allowed Characters
  private $CharsAllowed = '[a-zA-Z0-9\_\-]+';

  /**
   * Default Constructor: Initalizing all variables
   * @method __construct
   */
  public function __construct()
  {
      if (isset($_SERVER)) {
          if (isset($_SERVER['REQUEST_METHOD'])) {
              $this->method = $_SERVER['REQUEST_METHOD'];
              $this->request["method"]=$_SERVER['REQUEST_METHOD'];
          }
          $this->request["header"]=$this->getHTTPHeaders();
          if (isset($_SERVER['PATH_INFO'])) {
              $this->currentPath = $_SERVER['PATH_INFO'];
          }
      }
      if (isset($_POST)) {
          $this->request["body"]=$_POST;
          $this->request["raw"]=file_get_contents('php://input');
      }
      if (isset($_GET)) {
          $this->request["params"]=$_GET;
      }
      if (isset($_FILES)) {
          $this->request["files"]=$_FILES;
      }
      if (isset($_COOKIE)) {
          $this->request["cookies"]=$_COOKIE;
      }
      $this->response = new Response();
      $this->routes=['GET'=>[],'POST'=>[],'PUT'=>[],'DELETE'=>[],'PATCH'=>[],'ANY'=>[]];
  }

/**
 * Function to get headers related to HTTP,PHP_AUTH and REQUEST from $_SERVER
 * @method getHTTPHeaders
 * @return Array         returns an containing all information related to HTTP,PHP_AUTH and REQUEST from $_SERVER
 */
private function getHTTPHeaders()
{
    $header = [];
    foreach ($_SERVER as $name => $value) {
        if (preg_match('/^HTTP_/', $name)||preg_match('/^PHP_AUTH_/', $name)||preg_match('/^REQUEST_/', $name)) {
            $header[$name] = $value;
        }
    }
    return $header;
}

/**
 * Turns given path into regular expression for comparison in complex routing
 * @method getRegexRepresentation
 * @param  string                 $path Route
 * @return string                       Turns route into a regex
 */
private function getRegexRepresentation($path)
{
    //Check for invalid pattern
  if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $path)) {
      return false;
  }
    // Turn "(/)" into "/?"
    $path = preg_replace('#\(/\)#', '/?', $path);
     //Replace parameters
    $path = preg_replace('/:(' . $this->CharsAllowed . ')/', '(?<$1>' . $this->CharsAllowed . ')', $path);
    $path = preg_replace('/{(' . $this->CharsAllowed . ')}/', '(?<$1>' . $this->CharsAllowed . ')', $path);
    // Add start and end matching
    $patternAsRegex = "@^" . $path . "$@D";
    return $patternAsRegex;
}

  /**
   * Add the given route to 'GET' array for lookup
   * @method get
   * @param  string   $path     Route
   * @param  function $callback Function to be called when the current equates the provided route; The callback must take request array and response object as parameters
   * @return void
   */
  public function get($path, $callback)
  {
      $this->routes['GET'][$this->getRegexRepresentation($path)]=$callback;
  }
  /**
   * Add the given route to 'POST' array for lookup
   * @method post
   * @param  string   $path     Route
   * @param  function $callback Function to be called when the current equates the provided route; The callback must take request array and response object as parameters
   * @return void
   */
  public function post($path, $callback)
  {
      $this->routes['POST'][$this->getRegexRepresentation($path)]=$callback;
  }
  /**
   * Add the given route to 'PUT' array for lookup
   * @method put
   * @param  string   $path     Route
   * @param  function $callback Function to be called when the current equates the provided route; The callback must take request array and response object as parameters
   * @return void
   */
  public function put($path, $callback)
  {
      $this->routes['PUT'][$this->getRegexRepresentation($path)]=$callback;
  }
  /**
   * Add the given route to 'PATCH' array for lookup
   * @method patch
   * @param  string   $path     Route
   * @param  function $callback Function to be called when the current equates the provided route; The callback must take request array and response object as parameters
   * @return void
   */
  public function patch($path, $callback)
  {
      $this->routes['PATCH'][$this->getRegexRepresentation($path)]=$callback;
  }
  /**
   * Add the given route to 'DELETE' array for lookup
   * @method delete
   * @param  string   $path     Route
   * @param  function $callback Function to be called when the current equates the provided route; The callback must take request array and response object as parameters
   * @return void
   */
  public function delete($path, $callback)
  {
      $this->routes['DELETE'][$this->getRegexRepresentation($path)]=$callback;
  }
  /**
   * Add the given route to 'ANY' array for lookup. ANY can be any REQUEST_METHOD
   * @method any
   * @param  string   $path     Route
   * @param  function $callback Function to be called when the current equates the provided route; The callback must take request array and response object as parameters
   * @return void
   */
  public function any($path, $callback)
  {
      $this->routes['ANY'][$this->getRegexRepresentation($path)]=$callback;
  }
  /**
   * Error Handler to set handler to be called when no routes are found
   * @method error
   * @param  function $function A callback function that takes request array and response object
   * @return void
   */
  public function error($function)
  {
      $this->errorFunction=$function;
  }
  /**
   * Function to get appropriate callback for the current PATH_INFO based on REQUEST_METHOD
   * @method getCallback
   * @param  string        $method REQUEST_METHOD as string
   * @return function              The callback function
   */
  public function getCallback($method)
  {
      if (!isset($this->routes[$method])) {
          return null;
      }
      foreach ($this->routes[$method] as $name => $value) {
          if (preg_match($name, $this->currentPath, $matches)||preg_match($name, $this->currentPath."/", $matches)) {
              // Get elements with string keys from matches
        $params = array_intersect_key($matches, array_flip(array_filter(array_keys($matches), 'is_string')));
              foreach ($params as $key=> $value) {
                  $this->request["params"][$key]=$value;
              }
              return $this->routes[$method][$name];
          }
      }
  }
  /**
   * Starts the routing process by matching current PATH_INFO to avaialable routes in array $routes
   * @method start
   * @return function  Returns callback function of the appropriate route or returns callback function of the error handler
   */
  public function start()
  {
      $callback=$this->getCallBack('ANY');
      if ($callback) {
          return $callback($this->request, $this->response);
      }
      $callback=$this->getCallBack($this->method);
      if ($callback) {
          return $callback($this->request, $this->response);
      }
      if (isset($this->errorFunction)) {
          return ($this->errorFunction)(new Exception("Path not found!", 404), $this->response);
      }
  }
}
