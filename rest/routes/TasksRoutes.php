<?php

//Adding the tip
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

// Get all tasks
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

// Get task by id
Flight::route("GET /getTaskById/@id", function($id){

   $tasksService = new TasksService();

   $result = $tasksService->get_by_id($id);
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get a task!']);
   }
});

//Edit task
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

//Delete task
Flight::route("DELETE /deleteTask/@id", function($id){

   Flight::tasks_service()->delete($id);
   Flight::json(['message' => 'Task succesfully deleted!']);
 
 });
?>