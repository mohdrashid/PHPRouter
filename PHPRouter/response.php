<?php namespace PHPRouter;

class Response
{
    /**
   * Default Constructor
   * @method __construct
   */
    public function __construct()
    {
    }
  /**
   * Send Plaintext
   * @method send
   * @param  string $data   Plaintext message to be output
   * @param  int    $status HTTP code
   * @return int            Indication if printing was successful
   */
  public function send($data, $status=null)
  {
      if (isset($status)) {
          http_response_code($status);
      }
      return print($data);
  }
  /**
   * Send as JSON
   * @method jsin
   * @param  Array  $data   PHP array to be output in JSON format
   * @param  int    $status HTTP code
   * @return int            Indication if printing was successful
   */
  public function json($data, $status=null)
  {
      if (isset($status)) {
          http_response_code($status);
      }
      return print(json_encode($data));
  }
  /**
   * Return HTTP status only
   * @method status
   * @param  int $status HTTP code
   * @return int         indication if printing was successful
   */
  public static function status($status)
  {
      if (isset($status)) {
          http_response_code($status);
          return 1;
      }
      return 0;
  }
}
