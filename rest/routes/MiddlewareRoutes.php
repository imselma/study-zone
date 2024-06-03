<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('/*', function() {
    if(
        strpos(Flight::request()->url, '/authLogin') === 0 ||
        strpos(Flight::request()->url, '/addUser') === 0 ||
        strpos(Flight::request()->url, '/getAllTips') === 0 ||
        strpos(Flight::request()->url, '/connection-check') === 0){

            return TRUE;
        } else {
            try{
                $token = Flight::request()->getHeader("Authentication");
                if(!$token){
                   Flight::halt(401, "Missing authentication header!");
                }
                $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another
          
                //Seting the token and user from decoded token globaly, so that we are able to use it in all other routes.
                Flight::set('user', $decoded_token->user);
                Flight::Set('jwt_token',$token );
                return TRUE;
             }catch(\Exception $e){
                Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
             }
        }
});


//Middleware for errors 
Flight::map('error', function($e) {

    file_put_contents('logs.txt', $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    Flight::halt($e->getCode(), $e->getMessage());
    $code = $e->getCode() > 0 ? $e->getCode() : 500;
    Flight::halt($code, $e->getMessage());
});