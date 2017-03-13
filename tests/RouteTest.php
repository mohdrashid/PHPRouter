<?php
//Including the library
require_once('../PHPRouter/router.php');
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
       $app = new PHPRouter\Router();
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
       $app = new PHPRouter\Router();
       //Defining Routes
       $app->any('/test2/:id', function ($request, $response) {
         $this->assertEquals(123, $request["params"]["id"]);
         return $response->json(["id"=>$request["params"]["id"]]);
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
       $app = new PHPRouter\Router();
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
       $app = new PHPRouter\Router();
       //Defining Routes
       $app->any('/employee', function ($request, $response) {
         return $response->send("ANY request");
       });
       $app->error(function (Exception $e, $response) {
         return $response->send("Error",404);
       });
       $this->assertEquals(1, $app->start());
     }

}
