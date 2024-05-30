<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
     * @OA\Post(
     *      path="/addExam",
     *      tags={"exams"},
     *      summary="Add exam data to the database.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Exam data, or exception if exam is not added properly."
     *      ),
     *      @OA\RequestBody(
     *          description="exam data payload",
     *          @OA\JsonContent(
     *              required={"title","details","date_time"},
     *              @OA\Property(property="title", type="string", example="CO Final exam", description="Title"),
     *              @OA\Property(property="details", type="string", example="LAB128, Building B", description="Details"),
     *              @OA\Property(property="date_time", type="string", example="13 Jun, 2024 09:00AM", description="Date and Time")
     *          )
     *      )
     * )
     */
Flight::route("POST /addExam", function() {

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData();  //Getting data from the request payload.
      //Making an instance of Userservice
      $examsService = new ExamsService();
      //Add exam to db
      $result = $examsService->addExam($payload);

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
     *      path="/getAllExams",
     *      tags={"exams"},
     *      summary="Get all exams.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Array of all exams in the database."
     *      )
     * )
     */
Flight::route("GET /getAllExams", function(){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $examsService = new ExamsService();
      //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
      $result = $examsService->get_all();

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
     *      path="/getExamById/{id}",
     *      tags={"exams"},
     *      summary="Get exam by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Exam data, or false if exam does not exist."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Exam ID")
     * )
     */
Flight::route("GET /getExamById/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $examsService = new ExamsService();
      $result = $examsService->get_by_id($id);

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
 *     path="/editExam/{id}",
 *     tags={"exams"},
 *     summary="Edit exam data.",
 *     security={
 *         {"ApiKey": {}}
 *      },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Exam ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data.",
 *         @OA\JsonContent(
 *              @OA\Property(property="title", type="string", example="Web Engineering Final Exam", description="New title"),
 *              @OA\Property(property="details", type="string", example="Building B - ITLab128", description="New details")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editExam/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData();
      $examsService = new ExamsService();
      $result = $examsService->update($payload,$id);

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
     *      path="/deleteExam/{id}",
     *      tags={"exams"},
     *      summary="Delete exam by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Deleted exam data or 500 status code exception otherwise."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Task ID")
     * )
     */
Flight::route("DELETE /deleteExam/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      Flight::exams_service()->delete($id);

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user
      ]);
      
   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }
  
  });
?>