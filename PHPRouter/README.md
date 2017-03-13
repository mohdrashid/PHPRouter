PHPRouter is a simple routing extension for PHP inspired by express framework in Node.JS. Complex routing methods are still under development.

<a name="install"></a>
## Installation
Download the zip folder and extract to the directory where your project is located in.


<a name="usage"></a>
## Usage
Include the router in the project like

```php
include_once('PHPRouter/router.php');
```

PHP router supports <b>GET</b>, <b>POST</b>, <b>PUT</b>, <b>DELETE</b> and <b>PATCH</b> requests. The callback will returns url parameters, body and headers in order.
A typical route looks initialization looks like:
```php
//where method is get,post,put,delete or patch
$app->method('/', function($request,$response){
  echo "GET request";
});
```

<a name="example"></a>
## Example

```php
<?php
//Including the library
include_once('PHPRouter/router.php');
//Initalizing the PHPRouter class
$app = new PHPRouter();
$app->get('/', function($request,$response){
  $response->send("GET request");
});
$app->get('/user', function($request,$response){
  $response->json(array("id"=>"1","name"=>"droidhat.com"),200);
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
```
<a name="api"></a>
## API

  * <a href="#method"><code>$app-><b>get()</b></code></a>
  * <a href="#method"><code>$app-><b>post()</b></code></a>
  * <a href="#method"><code>$app-><b>put()</b></code></a>
  * <a href="#method"><code>$app-><b>delete()</b></code></a>
  * <a href="#method"><code>$app-><b>patch()</b></code></a>
  * <a href="#error"><code>$app-><b>error()</b></code></a>
  * <a href="#start"><code>$app-><b>start()</b></code></a>

-------------------------------------------------------
<a name="method"></a>
### $app->method($path, function($request,$response){}); where method is get, post, put, delete or patch

The first parameter is the route path like '/' or '/user'.
Second parameter is the callback function, i.e., the function to be called when processing is done.
The callback function takes <a href="#request">request</a> array and <a href="#response">response</a> object as parameters.

---------------------------------------------------------
<a name="request"></a>
### Request Array

Contains headers(HTTP information, Request information and PHP_AUTH) information, body parameter, url parameters, files and cookies informations in array format.

<br>
<b>Array indexes</b><br>
<b>"raw"</b>: Body in raw format<br>
<b>"body"</b>: Body in associative array format like $_POST<br>
<b>"header"</b>: HTTP,REQUEST and PHP_AUTH information<br>
<b>"method"</b>: The type of HTTP REQUEST<br>
<b>"params"</b>: URL parameters like $_GET<br>
<b>"files"</b>: FIles if any available like $_FILES<br>
<b>"cookies"</b>: Cookies if any available like $_COOKIE<br>

<b>Usage</b><br>
$request["body"] to access body parameters


-------------------------------------------------------
<a name="response"></a>
### Response Object

response is an object of class Response. It has methods such as send, json and status.

<br>
<b>Fucntions</b><br>
1. <code><b>send($message,$status)</b></code>: $message is the message that you want to output to the requester and $status is an optional field in case you want to send status also.<br>
2. <code><b>json($message,$status)</b></code>: $message is the message that you want to output to the requester and $status is an optional field in case you want to send status also. The difference is that here $message should be a PHP array that will be converted the function to JSON.<br>
3. <code><b>status($status)</b></code>: send HTTP status only.

<b>Usage</b><br>
$response->send("Hello World",200); to output to the requester "hello" world with a status of <b>200</b><br>

-------------------------------------------------------
<a name="error"></a>
### $app->error(function(Exception $e){})

Error function takes a callback function as a parameter. The callback function will be passed exception information if any occurs.<br>

---------------------------------------------------------
<a name="start"></a>
### $app->start();

Start the routing process.<br>

-------------------------------------------------------
<a name="license"></a>
## License

MIT
