<?php

//Adding the tip
Flight::route("POST /addTip", function() {
   
   $payload = Flight::request()->data->getData();  //Getting data from the request payload.
   //Making an instance of Userservice
   $tipsService = new TipsService();
   //Add tip to db
   $result = $tipsService->addTip($payload);

   if($result) {
      Flight::json(['message' => 'Study tip added successfully!']);
   } else{
      Fight::json(['message' => 'Failed to add a study tip!']);
   }
});

// Get all tips
Flight::route("GET /getAllTips", function(){

   $tipsService = new TipsService();

   //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
   $result = $tipsService->get_all();
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get all tips!']);
   }
});

// Get tips by id
Flight::route("GET /getTipById/@id", function($id){

   $tipsService = new TipsService();

   $result = $tipsService->get_by_id($id);
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get a tip!']);
   }
});

//Edit tip
Flight::route("PUT /editTip/@id", function($id){

   $payload = Flight::request()->data->getData();
   $tipsService = new TipsService();

   $result = $tipsService->update($payload,$id);
   
   if($result){
      Flight::json(['message' => 'Study tip succesfully edited!', 'result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to edit study tip!']);
   }
});

//Delete tip
Flight::route("DELETE /deleteTip/@id", function($id){

  Flight::tips_service()->delete($id);
  Flight::json(['message' => 'Study tip succesfully deleted!']);

});
?>