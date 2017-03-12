PHPRouter is a simple routing extension for PHP inspired by express framework in Node.JS.

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
$app->method('/', function($params,$body,$header){
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
### $app->method($path, function($params,$body,$header){}); where method is get, post, put, delete or patch

The first parameter is the route path like '/' or '/user'.
Second parameter is the callback function, i.e., the function to be called when processing is done.
The callback function takes url parameters, body of the request and header of the request as parameters.

-------------------------------------------------------
<a name="error"></a>
### $app->error(function(Exception $e){})

Error function takes a callback function as a parameter. The callback function will be passed exception information if any occurs.
  -------------------------------------------------------
<a name="start"></a>
### $app->start();

Start the routing process.
  -------------------------------------------------------
<a name="license"></a>
## License

MIT
