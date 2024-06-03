<?php

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

      $payload = Flight::request()->data->getData();
      $payload['users_id']= Flight::get('user')->id;
      $examsService = new ExamsService();
      $result = $examsService->addExam($payload);

      Flight::json([
         'result' => $result
      ]);
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

      $examsService = new ExamsService();
      $result = $examsService->get_all();

      Flight::json([
         'result' => $result
      ]);
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

      $examsService = new ExamsService();
      $result = $examsService->get_by_id($id);

      Flight::json([
         'result' => $result
      ]);
 });

/**
     * @OA\Get(
     *      path="/getExamByUserId",
     *      tags={"exams"},
     *      summary="Get exam by user id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Get exam data or 500 status code exception otherwise"
     *      )
     * )
     */
    Flight::route("GET /getExamByUserId", function(){

     $examService = new ExamsService();
     $result = $examService->getExamByUserId(Flight::get('user')->id);

     Flight::json([
        'result' => $result
     ]);
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

      $payload = Flight::request()->data->getData();
      $examsService = new ExamsService();
      $result = $examsService->update($payload,$id);

      Flight::json([
         'result' => $result
      ]); 
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
      Flight::exams_service()->delete($id);
  
  });
?>