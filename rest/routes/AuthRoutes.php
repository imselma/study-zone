<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
     * @OA\Post(
     *      path="/authLogin",
     *      tags={"auth"},
     *      summary="Log in user to account using email and password.",
     *      @OA\Response(
     *           response=200,
     *           description="User data and JWT."
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", example="example@user.com", description="User's email address"),
     *              @OA\Property(property="password", type="string", example="user12345", description="User's password"),
     *          )
     *      )
     * )
     */ 


Flight::route("POST /authLogin", function() {

      $payload = Flight::request()->data->getData();
      $authService = new AuthService();
   
      $fatchedUser = $authService->loginByAuth($payload);
   
      if(!$fatchedUser) {
         Flight::halt(500, 'Login failed due to wrong credentials!');
      }
   
      unset($fatchedUser['password']);
   
      $jwt_payload = [
         'user' => $fatchedUser,
         "issued_at" => time(),
         "exp" => time() + (60 * 60 * 24) //Valid one day
      ];
   
      //create token
      $token = JWT::encode(
         $jwt_payload, //payload
         Config::JWT_SECRET(), //secret created in config
         'HS256' //algorithm used to create token (SHA256)
      );
   
      Flight::json(array_merge($fatchedUser, ['token' => $token]));
});
   

/*Implementing stateless authentication. Not keeping state of our sessions, only way to authenticate users is
for every request we will send the authentication header with the jwt token and we will check the token, try decode it, check if it valid and only if it is valid we can execute the request.*/

/*User needs to be authenticated to triger some routes. In order to force that we use security and it will be secured using authentication mechanism*/

/**
     * @OA\Post(
     *      path="/authLogout",
     *      tags={"auth"},
     *      summary="Logout user from account.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Success response or exception if unable to verify."
     *      ),
     * )
     */ 

Flight::route('POST /authLogout', function() {

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user
      ]);
   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }
});
