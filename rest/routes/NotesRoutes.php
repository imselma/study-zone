<?php

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

      $payload = Flight::request()->data->getData();
      $payload['users_id']= Flight::get('user')->id;
      $notesService = new NotesService();
      $result = $notesService->addNote($payload);

      Flight::json([
         'result' => $result
      ]);
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
      $notesService = new NotesService();
      $result = $notesService->get_all();

      Flight::json([
         'result' => $result
      ]);
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

      $notesService = new NotesService();
      $result = $notesService->get_by_id($id);

      Flight::json([
         'result' => $result
      ]);
 });

 /**
     * @OA\Get(
     *      path="/getNoteByUserId",
     *      tags={"notes"},
     *      summary="Get note by user id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Get note data or 500 status code exception otherwise"
     *      )
     * )
     */
    Flight::route("GET /getNoteByUserId", function(){

     $noteService = new NotesService();
     $result = $noteService->getNoteByUserId(Flight::get('user')->id);

     Flight::json([
        'result' => $result
     ]);
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

      $payload = Flight::request()->data->getData();
      $notesService = new NotesService();
      $result = $notesService->update($payload,$id);

      Flight::json([
         'result' => $result
      ]);
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
   
      Flight::notes_service()->delete($id);
  });
?>