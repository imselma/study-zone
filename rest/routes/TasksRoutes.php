<?php

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
     *              required={"title","details","deadline"},
     *              @OA\Property(property="title", type="string", example="Web - milestone #4", description="Title"),
     *              @OA\Property(property="details", type="string", example="Finish backen and add QopenAPI and JWT.", description="Details"),
     *              @OA\Property(property="deadline", type="string", example="14 May, 2024", description="Deadline")
     *          )
     *      )
     * )
     */
Flight::route("POST /addTask", function() {

      $payload = Flight::request()->data->getData();
      $payload['users_id']= Flight::get('user')->id;
      $tasksService = new TasksService();
      $result = $tasksService->addTask($payload);
   

      Flight::json([
         'result' => $result
      ]);
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

      $tasksService = new TasksService();
      $result = $tasksService->get_all();
   
      Flight::json([
         'result' => $result
      ]);
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

      $tasksService = new TasksService();
      $result = $tasksService->get_by_id($id);

      Flight::json([
         'result' => $result
      ]);
});

/**
     * @OA\Get(
     *      path="/getTaskByUserId",
     *      tags={"tasks"},
     *      summary="Get task by user id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Get task data or 500 status code exception otherwise"
     *      )
     * )
     */
    Flight::route("GET /getTaskByUserId", function(){

      $taskService = new TasksService();
      $result = $taskService->getTaskByUserId(Flight::get('user')->id);
 
      Flight::json([
         'result' => $result
      ]);
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

      $payload = Flight::request()->data->getData();
      $tasksService = new TasksService();
      $result = $tasksService->update($payload,$id);

      Flight::json([
         'result' => $result
      ]);
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

      Flight::tasks_service()->delete($id);
 });
?>