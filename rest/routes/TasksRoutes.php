<?php

/**
     * @OA\Post(
     *      path="/addTask",
     *      tags={"tasks"},
     *      summary="Add task data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="Study task data, or exception if task is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="task data payload",
     *          @OA\JsonContent(
     *              required={"title","details","deadline"},
     *              @OA\Property(property="title", type="string", example="Some title", description="Title"),
     *              @OA\Property(property="details", type="string", example="Some details", description="Details"),
     *              @OA\Property(property="deadline", type="string", example="Some deadline", description="Deadline")
     *          )
     *      )
     * )
     */
Flight::route("POST /addTask", function() {
   
   $payload = Flight::request()->data->getData();  //Getting data from the request payload.
   //Making an instance of Userservice
   $tasksService = new TasksService();
   //Add task to db
   $result = $tasksService->addTask($payload);

   if($result) {
      Flight::json(['message' => 'Task added successfully!']);
   } else{
      Fight::json(['message' => 'Failed to add task!']);
   }
});

/**
     * @OA\Get(
     *      path="/getAllTasks",
     *      tags={"tasks"},
     *      summary="Get all tasks",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all tasks in the database"
     *      )
     * )
     */
Flight::route("GET /getAllTasks", function(){

   $tasksService = new TasksService();

   //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
   $result = $tasksService->get_all();
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get all tasks!']);
   }
});

/**
     * @OA\Get(
     *      path="/getTaskById/{id}",
     *      tags={"tasks"},
     *      summary="Get task by id",
     *      @OA\Response(
     *           response=200,
     *           description="Task data, or false if task does not exist"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="Task ID")
     * )
     */
Flight::route("GET /getTaskById/@id", function($id){

   $tasksService = new TasksService();

   $result = $tasksService->get_by_id($id);
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get a task!']);
   }
});

/**
 * @OA\Put(
 *     path="/editTask/{id}",
 *     tags={"tasks"},
 *     summary="Edit task data",
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
 *              @OA\Property(property="title", type="string", example="Some title", description="New title"),
 *              @OA\Property(property="details", type="string", example="Some details", description="New details"),
 *              @OA\Property(property="deadline", type="string", example="Some deadline", description="New deadline")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editTask/@id", function($id){

   $payload = Flight::request()->data->getData();
   $tasksService = new TasksService();

   $result = $tasksService->update($payload,$id);
   
   if($result){
      Flight::json(['message' => 'Task succesfully edited!', 'result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to edit task!']);
   }
});

 /**
     * @OA\Delete(
     *      path="/deleteTask/{id}",
     *      tags={"tasks"},
     *      summary="Delete task by id",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted task data or 500 status code exception otherwise"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Task ID")
     * )
     */
Flight::route("DELETE /deleteTask/@id", function($id){

   Flight::tasks_service()->delete($id);
   Flight::json(['message' => 'Task succesfully deleted!']);
 
 });
?>