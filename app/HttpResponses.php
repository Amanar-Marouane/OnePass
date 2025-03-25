<?php

namespace App;

trait HttpResponses
{
    protected function success($data = null, $message = null, $code = 200, $cookies = []){
       $response = response([
         "status"=> "Request has been sent successfully",
         "message" => $message,
         "data" => $data,
       ],$code);

       foreach($cookies as $key => $value){
         $response->withCookie(cookie($key, $value, 1440, null, null, true));
       }
       return $response;
    }

    protected function error($message = null, $code = null, $cookies = []){
     $response = response([
       "status"=> "Error Has Occurred",
       "message" => $message,
     ],$code);

     foreach($cookies as $key => $value){
       $response->withCookie(cookie($key, $value, 1440, null, null, true));
     }
     return $response;
  }
}
