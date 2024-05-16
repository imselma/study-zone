<?php

/**
     * @OA\Post(
     *      path="/addExam",
     *      tags={"exams"},
     *      summary="Add exam data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="Exam data, or exception if exam is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="exam data payload",
     *          @OA\JsonContent(
     *              required={"title","details","date_time"},
     *              @OA\Property(property="title", type="string", example="Some title", description="Title"),
     *              @OA\Property(property="details", type="string", example="Some details", description="Details"),
     *              @OA\Property(property="date_time", type="string", example="Some date_time", description="Date and Time")
     *          )
     *      )
     * )
     */
Flight::route("POST /addExam", function() {
   
   $payload = Flight::request()->data->getData();  //Getting data from the request payload.

   //Making an instance of Userservice
   $examsService = new ExamsService();
   //Add exam to db
   $result = $examsService->addExam($payload);
   
   if($result) {
      Flight::json(['message' => 'Exam added successfully!']);
   } else{
      Fight::json(['message' => 'Failed to add exam!']);
   }
});

/**
     * @OA\Get(
     *      path="/getAllExams",
     *      tags={"exams"},
     *      summary="Get all exams",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all exams in the database"
     *      )
     * )
     */
Flight::route("GET /getAllExams", function(){

   $examsService = new ExamsService();

   //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
   $result = $examsService->get_all();
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get all exams!']);
   }
});

/**
     * @OA\Get(
     *      path="/getExamById/{id}",
     *      tags={"exams"},
     *      summary="Get exam by id",
     *      @OA\Response(
     *           response=200,
     *           description="Exam data, or false if exam does not exist"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="Exam ID")
     * )
     */
Flight::route("GET /getExamById/@id", function($id){

    $examsService = new ExamsService();
 
    $result = $examsService->get_by_id($id);
    
    if($result){
       Flight::json(['result' => $result]);
    } else{
       Flight::json(['message' => 'Failed to get an exam!']);
    }
 });

/**
 * @OA\Put(
 *     path="/editExam/{id}",
 *     tags={"exams"},
 *     summary="Edit exam data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Exam ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data",
 *         @OA\JsonContent(
 *              @OA\Property(property="title", type="string", example="Some title", description="New title"),
 *              @OA\Property(property="details", type="string", example="Some details", description="New details"),
 *              @OA\Property(property="date_time", type="string", example="Some date_time", description="New Date and Time")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editExam/@id", function($id){

    $payload = Flight::request()->data->getData();
    $examsService = new ExamsService();
 
    $result = $examsService->update($payload,$id);
    
    if($result){
       Flight::json(['message' => 'Exam succesfully edited!', 'result' => $result]);
    } else{
       Flight::json(['message' => 'Failed to edit exam!']);
    }
 });

/**
     * @OA\Delete(
     *      path="/deleteExam/{id}",
     *      tags={"exams"},
     *      summary="Delete exam by id",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted exam data or 500 status code exception otherwise"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Task ID")
     * )
     */
Flight::route("DELETE /deleteExam/@id", function($id){

    Flight::exams_service()->delete($id);
    Flight::json(['message' => 'Exam succesfully deleted!']);
  
  });
?>