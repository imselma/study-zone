<?php

/**
     * @OA\Post(
     *      path="/addNote",
     *      tags={"notes"},
     *      summary="Add note data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="Note data, or exception if note is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="note data payload",
     *          @OA\JsonContent(
     *              required={"title","details"},
     *              @OA\Property(property="title", type="string", example="Some title", description="Title"),
     *              @OA\Property(property="details", type="string", example="Some details", description="Details")
     *          )
     *      )
     * )
     */
Flight::route("POST /addNote", function() {
   
   $payload = Flight::request()->data->getData();  //Getting data from the request payload.

   //Making an instance of Userservice
   $notesService = new NotesService();
   //Add note to db
   $result = $notesService->addNote($payload);
   
   if($result) {
      Flight::json(['message' => 'Task added successfully!']);
   } else{
      Fight::json(['message' => 'Failed to add task!']);
   }
});

/**
     * @OA\Get(
     *      path="/getAllNotes",
     *      tags={"notes"},
     *      summary="Get all notes",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all notes in the database"
     *      )
     * )
     */
Flight::route("GET /getAllNotes", function(){

   $notesService = new NotesService();

   //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
   $result = $notesService->get_all();
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get all notes!']);
   }
});

/**
     * @OA\Get(
     *      path="/getNoteById/{id}",
     *      tags={"notes"},
     *      summary="Get note by id",
     *      @OA\Response(
     *           response=200,
     *           description="Note data, or false if note does not exist"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="Note ID")
     * )
     */
Flight::route("GET /getNoteById/@id", function($id){

    $notesService = new NotesService();
 
    $result = $notesService->get_by_id($id);
    
    if($result){
       Flight::json(['result' => $result]);
    } else{
       Flight::json(['message' => 'Failed to get a note!']);
    }
 });

/**
 * @OA\Put(
 *     path="/editNote/{id}",
 *     tags={"notes"},
 *     summary="Edit note data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Note ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data",
 *         @OA\JsonContent(
 *              @OA\Property(property="title", type="string", example="Some title", description="New title"),
 *              @OA\Property(property="details", type="string", example="Some details", description="New details")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editNote/@id", function($id){

    $payload = Flight::request()->data->getData();
    $notesService = new NotesService();
 
    $result = $notesService->update($payload,$id);
    
    if($result){
       Flight::json(['message' => 'Note succesfully edited!', 'result' => $result]);
    } else{
       Flight::json(['message' => 'Failed to edit study note!']);
    }
 });

 /**
     * @OA\Delete(
     *      path="/deleteNote/{id}",
     *      tags={"tasks"},
     *      summary="Delete note by id",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted note data or 500 status code exception otherwise"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Task ID")
     * )
     */
Flight::route("DELETE /deleteNote/@id", function($id){

    Flight::notes_service()->delete($id);
    Flight::json(['message' => 'Note succesfully deleted!']);
  
  });
?>