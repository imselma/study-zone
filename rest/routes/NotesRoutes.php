<?php

//Adding the note
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

// Get all notes
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

// Get notes by id
Flight::route("GET /getNoteById/@id", function($id){

    $notesService = new NotesService();
 
    $result = $notesService->get_by_id($id);
    
    if($result){
       Flight::json(['result' => $result]);
    } else{
       Flight::json(['message' => 'Failed to get a note!']);
    }
 });

 //Edit note
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

 //Delete note
Flight::route("DELETE /deleteNote/@id", function($id){

    Flight::notes_service()->delete($id);
    Flight::json(['message' => 'Note succesfully deleted!']);
  
  });
?>