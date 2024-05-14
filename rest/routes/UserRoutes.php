<?php

Flight::route('GET /connection-check', function(){
    $dao = new BaseDao("users");
});

/**
     * @OA\Post(
     *      path="/addUser",
     *      tags={"users"},
     *      summary="Add user data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="User data, or exception if user is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="User data payload",
     *          @OA\JsonContent(
     *              required={"first_name","last_name","email", "password", "university", "department"},
     *              @OA\Property(property="first_name", type="string", example="Some first name", description="User first name"),
     *              @OA\Property(property="last_name", type="string", example="Some last name", description="User last name"),
     *              @OA\Property(property="email", type="string", example="example@user.com", description="example@user.com"),
     *              @OA\Property(property="password", type="string", example="user12345", description="Some password"),
     *              @OA\Property(property="university", type="string", example="Some university", description="University name")
     *              @OA\Property(property="department", type="string", example="Some department", description="Department name")
     *          )
     *      )
     * )
     */

Flight::route("POST /addUser", function() {
   
   $payload = Flight::request()->data->getData();  //Getting data from the request payload.
   //Making an instance of Userservice [baseDao->userDao, baseService->userService, userRoutes->userService]
   $userService = new UserService();
   //Add user to db
   $result = $userService->adduser($payload);

   if($result) {
      Flight::json(['message' => 'User added successfully!']);
   } else{
      Fight::json(['message' => 'Failed to add user!']);
   }
});

/**
     * @OA\Post(
     *      path="/login",
     *      tags={"users"},
     *      summary="Login user to account",
     *      @OA\Response(
     *           response=200,
     *           description="User data, or exception if user is not logged properly"
     *      ),
     *      @OA\RequestBody(
     *          description="User data payload",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", example="example@user.com", description="example@user.com"),
     *              @OA\Property(property="password", type="string", example="user12345", description="Some password"),
     *          )
     *      )
     * )
     */

Flight::route("POST /login", function() {

   $payload = Flight::request()->data->getData();
   $userService = new UserService();

   $fatchedUser = $userService->login($payload);

   if($fatchedUser) {
      Flight::json(['message' => 'Login successfull!',
                    'user' => $fatchedUser]);
   } else {
      Flight::json(['message' => 'Login failed!']);
   }
});

/**
     * @OA\Get(
     *      path="/getAllUsers",
     *      tags={"users"},
     *      summary="Get all users",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all users in the database"
     *      )
     * )
     */
Flight::route("GET /getAllUsers", function(){

   $userService = new UserService();

   //get_all() is inherited from BaseService. TipsService extends BaseService, which contains the get_all() method, you don't need to define it again in TipsService again like the POST method where you are sending some data.
   $result = $userService->get_all();
   
   if($result){
      Flight::json(['result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to get all users!']);
   }
});

/**
 * @OA\Put(
 *     path="/editUser/{id}",
 *     tags={"users"},
 *     summary="Edit user data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing current data",
 *         @OA\JsonContent(
 *              @OA\Property(property="first_name", type="string", example="Some first name", description="New user first name"),
 *              @OA\Property(property="last_name", type="string", example="Some last name", description="New user last name"),
 *              @OA\Property(property="email", type="string", example="example@user.com", description="example@user.com"),
 *              @OA\Property(property="password", type="string", example="user12345", description="New password"),
 *              @OA\Property(property="university", type="string", example="Some university", description="New university name")
 *              @OA\Property(property="department", type="string", example="Some department", description="New department name")
 *         )
 *     )
 * )
 */
Flight::route("PUT /editUser/@id", function($id){

   $payload = Flight::request()->data->getData();
   $userService = new UserService();

   $result = $userService->update($payload,$id);
   
   if($result){
      Flight::json(['message' => 'User succesfully edited!', 'result' => $result]);
   } else{
      Flight::json(['message' => 'Failed to edit users!']);
   }
});

 /**
     * @OA\Delete(
     *      path="/deleteUser/{id}",
     *      tags={"users"},
     *      summary="Delete user by id",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted user data or 500 status code exception otherwise"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="User ID")
     * )
     */
Flight::route("DELETE /deleteUser/@id", function($id){

   $userService = new UserService();

   Flight::user_service()->delete($id);
   Flight::json(['message' => 'User succesfully deleted!']);
});

?>