<?php namespace PHPRouter;

class Response
{
    public function __construct()
    {
    }
  //Send Plaintext
  public static function send($data, $status=null)
  {
      if (isset($status)) {
          http_response_code($status);
      }
      return print($data);
  }
  //Send as JSON
  public static function json($data, $status=null)
  {
      if (isset($status)) {
          http_response_code($status);
      }
      return print(json_encode($data));
  }
  //Send only status
  public static function status($status)
  {
      if (isset($status)) {
          http_response_code($status);
          return 1;
      }
      return 0;
  }
}
