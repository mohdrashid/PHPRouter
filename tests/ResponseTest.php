<?php namespace PHPRouter\Tests;
//Including the library
require '..\PHPRouter\Response.php';
use PHPRouter\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
     public function testSend()
     {
       $response=new Response();
       $status1=$response->send("Send It");
       $status2=$response->send("Send It",200);
       $this->assertEquals(1, $status1);
       $this->assertEquals(1, $status2);
     }

     public function testJSON()
     {
       $response=new Response();
       $status1=$response->json(["id"=>"1"]);
       $status2=$response->json(["id"=>"1"],200);
       $this->assertEquals(1, $status1);
       $this->assertEquals(1, $status2);
     }

     public function testStatus()
     {
       $response=new Response();
       $status=$response->status(200);
       $this->assertEquals(1, $status);
     }
}
