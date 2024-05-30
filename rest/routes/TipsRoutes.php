<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
/**
     * @OA\Post(
     *      path="/addTip",
     *      tags={"tips"},
     *      summary="Add study tip data to the database.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Study tip data, or exception if study tip is not added properly."
     *      ),
     *      @OA\RequestBody(
     *          description="Tip data payload",
     *          @OA\JsonContent(
     *              required={"category","details","title"},
     *              @OA\Property(property="category", type="string", example="Learn faster", description="Category"),
     *              @OA\Property(property="details", type="string", example="Chunk your learning into bite-sized pieces. Master one concept before moving on. Small steps lead to big gains", description="Details"),
     *              @OA\Property(property="title", type="string", example="Learn piece by piece", description="Title")
     *          )
     *      )
     * )
     */
Flight::route("POST /addTip", function() {

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData(); 
      $tipsService = new TipsService();
      $result = $tipsService->addTip($payload);

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user,
         'result' => $result
      ]);
   
   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }
});

/**
     * @OA\Get(
     *      path="/getAllTips",
     *      tags={"tips"},
     *      summary="Get all tips.",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all tips in the database."
     *      )
     * )
     */
Flight::route("GET /getAllTips", function(){

      $tipsService = new TipsService();
      $result = $tipsService->get_all();

      Flight::json(['result' => $result], 200);

});

/**
     * @OA\Get(
     *      path="/getTipById/{id}",
     *      tags={"tips"},
     *      summary="Get tip by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Study tip data, or false if study tip does not exist."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Tip ID")
     * )
     */
Flight::route("GET /getTipById/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $tipsService = new TipsService();
      $result = $tipsService->get_by_id($id);

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user,
         'result' => $result
      ]);
      
   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }
});

/**
 * @OA\Put(
 *     path="/editTip/{id}",
 *     tags={"tips"},
 *     summary="Edit study tip data.",
 *     security={
 *         {"ApiKey": {}}
 *      },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Tip ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data.",
 *         @OA\JsonContent(
 *              @OA\Property(property="category", type="string", example="Focus", description="New category"),
 *              @OA\Property(property="details", type="string", example="Create a dedicated workspace free from distractions. Use tools like noise-cancelling headphones or ambient music to drown out background noise", description="New details"),
 *              @OA\Property(property="title", type="string", example="Space around matters", description="New title")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editTip/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData();
      $tipsService = new TipsService();
      $result = $tipsService->update($payload,$id);

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user,
         'result' => $result
      ]);

   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }
});


 /**
     * @OA\Delete(
     *      path="/deleteTip/{id}",
     *      tags={"tips"},
     *      summary="Delete tip by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Deleted tip data or 500 status code exception otherwise."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Tip ID")
     * )
     */
Flight::route("DELETE /deleteTip/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      Flight::tips_service()->delete($id);

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user
      ]);

   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }

});
?>