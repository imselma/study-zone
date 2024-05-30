<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
     * @OA\Post(
     *      path="/addTask",
     *      tags={"tasks"},
     *      summary="Add task data to the database.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Study task data, or exception if task is not added properly."
     *      ),
     *      @OA\RequestBody(
     *          description="Task data payload.",
     *          @OA\JsonContent(
     *              required={"title","details","deadline","user_id"},
     *              @OA\Property(property="title", type="string", example="Web - milestone #4", description="Title"),
     *              @OA\Property(property="details", type="string", example="Finish backen and add QopenAPI and JWT.", description="Details"),
     *              @OA\Property(property="deadline", type="string", example="14 May, 2024", description="Deadline"),
     *              @OA\Property(property="users_id", type="string", example="16", description="Update task's id")
     *          )
     *      )
     * )
     */
Flight::route("POST /addTask", function() {

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData();  //Getting data from the request payload.
      //Making an instance of Userservice
      $tasksService = new TasksService();
      //Add task to db
      $result = $tasksService->addTask($payload);

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
     *      path="/getAllTasks",
     *      tags={"tasks"},
     *      summary="Get all tasks.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Array of all tasks in the database."
     *      )
     * )
     */
Flight::route("GET /getAllTasks", function(){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $tasksService = new TasksService();
      //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
      $result = $tasksService->get_all();
   
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
     *      path="/getTaskById/{id}",
     *      tags={"tasks"},
     *      summary="Get task by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Task data, or false if task does not exist."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Task ID")
     * )
     */
Flight::route("GET /getTaskById/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $tasksService = new TasksService();
      $result = $tasksService->get_by_id($id);

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
    *     path="/editTask/{id}",
    *     tags={"tasks"},
    *     summary="Edit task data.",
    *      security={
    *         {"ApiKey": {}}
    *      },
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="Task ID",
    *         @OA\Schema(type="integer", example=1)
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         description="Request body containing current data",
    *         @OA\JsonContent(
    *              @OA\Property(property="title", type="string", example="OS assignemnt 5", description="New title"),
    *              @OA\Property(property="details", type="string", example="Semaphores and conditional variables", description="New details"),
    *              @OA\Property(property="deadline", type="string", example="21 May, 2024", description="New deadline")
    *         )
    *     )
    * )
    */
Flight::route("PUT /editTask/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData();
      $tasksService = new TasksService();
      $result = $tasksService->update($payload,$id);

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
     *      path="/deleteTask/{id}",
     *      tags={"tasks"},
     *      summary="Delete task by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Deleted task data or 500 status code exception otherwise."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Task ID")
     * )
     */
Flight::route("DELETE /deleteTask/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      Flight::tasks_service()->delete($id);

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user
      ]);
      
   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }
 
 });
?>