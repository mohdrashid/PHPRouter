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
class Response
{
  public function __construct() {
  }
  //Send Plaintext
  public static function send($data,$status=null){
    if(isset($status)){
      http_response_code($status);
    }
    echo $data;
  }
  //Send as JSON
  public static function json($data,$status=null){
    if(isset($status)){
      http_response_code($status);
    }
    echo json_encode($data);
  }
  //Send only status
  public static function status($status){
    if(isset($status)){
      http_response_code($status);
    }
  }
}
?>
