<?php

/**
     * @OA\Post(
     *      path="/addTip",
     *      tags={"tips"},
     *      summary="Add study tip data to the database.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Study tip data, or exception if study tip is not added properly."
     *      ),
     *      @OA\RequestBody(
     *          description="Tip data payload",
     *          @OA\JsonContent(
     *              required={"category","details","title"},
     *              @OA\Property(property="category", type="string", example="Learn faster", description="Category"),
     *              @OA\Property(property="details", type="string", example="Chunk your learning into bite-sized pieces. Master one concept before moving on. Small steps lead to big gains", description="Details"),
     *              @OA\Property(property="title", type="string", example="Learn piece by piece", description="Title")
     *          )
     *      )
     * )
     */
    Flight::route("POST /addTip", function() {

      $payload = Flight::request()->data->getData();
      $payload['users_id']= Flight::get('user')->id;
      $userService = new UserService();
      $user = $userService->getUserById($payload['users_id']);
      if($user['user_type'] != 'admin'){
            Flight::halt(500, "you are not the admin user!");
      }else {
            $tipsService = new TipsService();
            $result = $tipsService->addTip($payload);

            Flight::json([
            'result' => $result
            ]);    
      }
});

/**
     * @OA\Get(
     *      path="/getAllTips",
     *      tags={"tips"},
     *      summary="Get all tips.",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all tips in the database."
     *      )
     * )
     */
Flight::route("GET /getAllTips", function(){

      $tipsService = new TipsService();
      $result = $tipsService->get_all();

      Flight::json(['result' => $result], 200);

});

/**
     * @OA\Get(
     *      path="/getTipById/{id}",
     *      tags={"tips"},
     *      summary="Get tip by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Study tip data, or false if study tip does not exist."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Tip ID")
     * )
     */
Flight::route("GET /getTipById/@id", function($id){

      $tipsService = new TipsService();
      $result = $tipsService->get_by_id($id);

      Flight::json([
         'result' => $result
      ]);
});

/**
 * @OA\Put(
 *     path="/editTip/{id}",
 *     tags={"tips"},
 *     summary="Edit study tip data.",
 *     security={
 *         {"ApiKey": {}}
 *      },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Tip ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data.",
 *         @OA\JsonContent(
 *              @OA\Property(property="category", type="string", example="Focus", description="New category"),
 *              @OA\Property(property="details", type="string", example="Create a dedicated workspace free from distractions. Use tools like noise-cancelling headphones or ambient music to drown out background noise", description="New details"),
 *              @OA\Property(property="title", type="string", example="Space around matters", description="New title")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editTip/@id", function($id){

      $payload = Flight::request()->data->getData();
      $tipsService = new TipsService();
      $result = $tipsService->update($payload,$id);

      Flight::json([
         'result' => $result
      ]);
});


 /**
     * @OA\Delete(
     *      path="/deleteTip/{id}",
     *      tags={"tips"},
     *      summary="Delete tip by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Deleted tip data or 500 status code exception otherwise."
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Tip ID")
     * )
     */
Flight::route("DELETE /deleteTip/@id", function($id){

      Flight::tips_service()->delete($id);
});
?>