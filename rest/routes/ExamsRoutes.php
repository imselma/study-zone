<?php

//Adding the note
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

// Get all exams
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

// Get exams by id
Flight::route("GET /getExamById/@id", function($id){

    $examsService = new ExamsService();
 
    $result = $examsService->get_by_id($id);
    
    if($result){
       Flight::json(['result' => $result]);
    } else{
       Flight::json(['message' => 'Failed to get an exam!']);
    }
 });

//Edit exam
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

//Delete exam
Flight::route("DELETE /deleteExam/@id", function($id){

    Flight::exams_service()->delete($id);
    Flight::json(['message' => 'Exam succesfully deleted!']);
  
  });
?>