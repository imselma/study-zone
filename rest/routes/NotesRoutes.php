<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
     * @OA\Post(
     *      path="/addNote",
     *      tags={"notes"},
     *      summary="Add note data to the database.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Note data, or exception if note is not added properly."
     *      ),
     *      @OA\RequestBody(
     *          description="Note data payload",
     *          @OA\JsonContent(
     *              required={"title","details"},
     *              @OA\Property(property="title", type="string", example="Grocery list", description="Title"),
     *              @OA\Property(property="details", type="string", example="Bread, eggs, pottatoes", description="Details")
     *          )
     *      )
     * )
     */
Flight::route("POST /addNote", function() {

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData();  //Getting data from the request payload.
 
      $notesService = new NotesService();
      $result = $notesService->addNote($payload);

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
     *      path="/getAllNotes",
     *      tags={"notes"},
     *      summary="Get all notes.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Array of all notes in the database."
     *      )
     * )
     */
Flight::route("GET /getAllNotes", function(){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $notesService = new NotesService();
      //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
      $result = $notesService->get_all();

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
     *      path="/getNoteById/{id}",
     *      tags={"notes"},
     *      summary="Get note by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Note data, or false if note does not exist."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Note ID")
     * )
     */
Flight::route("GET /getNoteById/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $notesService = new NotesService();
      $result = $notesService->get_by_id($id);

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
 *     path="/editNote/{id}",
 *     tags={"notes"},
 *     summary="Edit note data.",
 *     security={
 *         {"ApiKey": {}}
 *      },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Note ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data.",
 *         @OA\JsonContent(
 *              @OA\Property(property="title", type="string", example="Morning routine products", description="New title"),
 *              @OA\Property(property="details", type="string", example="Face wash gel", description="New details")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editNote/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      $payload = Flight::request()->data->getData();
      $notesService = new NotesService();
      $result = $notesService->update($payload,$id);

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
     *      path="/deleteNote/{id}",
     *      tags={"notes"},
     *      summary="Delete note by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Deleted note data or 500 status code exception otherwise."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Note ID")
     * )
     */
Flight::route("DELETE /deleteNote/@id", function($id){

   try{
      $token = Flight::request()->getHeader("Authentication");
      if(!$token){
         Flight::halt(401, "Missing authentication header!");
      }
      $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256')); //If I add something it would fail because I am trying to decode jwt token signed with one secret, with another

      Flight::notes_service()->delete($id);

      Flight::json([
         'jwt_decoded' => $decoded_token,
         'user' => $decoded_token->user
      ]);

   }catch(\Exception $e){
      Flight::halt(401, $e->getMessage()); //401 -> means unauthenticated user
   }
  
  });
?>