<?php

/**
     * @OA\Post(
     *      path="/addTip",
     *      tags={"tips"},
     *      summary="Add study tip data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="Study tip data, or exception if study tip is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="Tip data payload",
     *          @OA\JsonContent(
     *              required={"category","details","title"},
     *              @OA\Property(property="category", type="string", example="Some category", description="Category"),
     *              @OA\Property(property="details", type="string", example="Some details", description="Details"),
     *              @OA\Property(property="title", type="string", example="Some title", description="Title")
     *          )
     *      )
     * )
     */
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

/**
     * @OA\Get(
     *      path="/getAllTips",
     *      tags={"tips"},
     *      summary="Get all tips",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all tips in the database"
     *      )
     * )
     */
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

/**
     * @OA\Get(
     *      path="/getTipById/{id}",
     *      tags={"tips"},
     *      summary="Get tip by id",
     *      @OA\Response(
     *           response=200,
     *           description="Study tip data, or false if study tip does not exist"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="Tip ID")
     * )
     */
Flight::route("GET /getTipById/@id", function($id){

   $tipsService = new TipsService();

   $result = $tipsService->get_by_id($id);
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get a tip!']);
   }
});

/**
 * @OA\Put(
 *     path="/editTip/{id}",
 *     tags={"tips"},
 *     summary="Edit study tip data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Tip ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data",
 *         @OA\JsonContent(
 *              @OA\Property(property="category", type="string", example="Some category", description="New category"),
 *              @OA\Property(property="details", type="string", example="Some details", description="New details"),
 *              @OA\Property(property="title", type="string", example="Some title", description="New title")
 *         )
 *     )
 * )
 */
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

 /**
     * @OA\Delete(
     *      path="/deleteTip/{id}",
     *      tags={"tips"},
     *      summary="Delete tip by id",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted tip data or 500 status code exception otherwise"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Tip ID")
     * )
     */
Flight::route("DELETE /deleteTip/@id", function($id){

  Flight::tips_service()->delete($id);
  Flight::json(['message' => 'Study tip succesfully deleted!']);

});
?>