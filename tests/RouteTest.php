<?php namespace Tests;

//Including the library
require '..\PHPRouter\Router.php';
use PHPRouter\Router;
use Exception;
class RouteTest extends \PHPUnit_Framework_TestCase
{
     public function testRouteAny()
     {
       //Setting Global Variables
       $_SERVER=[
                "REQUEST_METHOD"=>"GET",
                "REQUEST_URI"=>"/PHPRouter/example/example.php/employee",
                "PATH_INFO"=>"/test"
                ];

       //Initalizing the PHPRouter class
       $app = new Router();
       //Defining Routes
       $app->any('/test', function ($request, $response) {
           return $response->send("ANY request");
       });
       $this->assertEquals(1, $app->start());
     }

     public function testRouteGetWithParams()
     {
       //Setting Global Variables
       $_SERVER=[
                "REQUEST_METHOD"=>"GET",
                "REQUEST_URI"=>"/PHPRouter/example/example.php/employee",
                "PATH_INFO"=>"/test2/123"
                ];

       //Initalizing the PHPRouter class
       $app = new Router();
       //Defining Routes
       $app->any('/test2/:id', function ($request, $response) {
         $this->assertEquals(123, $request["params"]["id"]);
         $status = $response->json(["id"=>$request["params"]["id"]]);
         $this->assertEquals(1, $status);
       });
       $app->start();
     }

     public function testRoutePostWithBody()
     {
       //Setting Global Variables
       $_SERVER=[
                "REQUEST_METHOD"=>"POST",
                "REQUEST_URI"=>"/PHPRouter/example/example.php/employee",
                "PATH_INFO"=>"/test"
                ];
      $_POST["id"]=123;
       //Initalizing the PHPRouter class
       $app = new Router();
       //Defining Routes
       $app->post('/test', function ($request, $response) {
         $this->assertEquals(123, $request["body"]["id"]);
         return $response->json(["id"=>$request["body"]["id"]]);
       });
       $app->start();
     }

     public function testRouteExceptionWithErrorCatching()
     {
       //Employee2 route is not specified
       $_SERVER=[
                "REQUEST_METHOD"=>"POST",
                "REQUEST_URI"=>"/PHPRouter/example/example.php/employee",
                "PATH_INFO"=>"/employee2"
                ];

       //Initalizing the PHPRouter class
       $app = new Router();
       //Defining Routes
       $app->any('/employee', function ($request, $response) {
         return $response->send("ANY request");
       });
       $app->error(function (Exception $e, $response) {
         $this->assertEquals(404, $e->getCode());
       });
       $app->start();

     }
}
