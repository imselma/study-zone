<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('GET /connection-check', function(){
    $dao = new BaseDao("users");
});

/**
 * @OA\Post(
 *      path="/addUser",
 *      tags={"users"},
 *      summary="Add user data to the database.",
 *      @OA\Response(
 *           response=200,
 *           description="User added successfully or failure message."
 *      ),
 *      @OA\RequestBody(
 *          description="User data payload",
 *          @OA\JsonContent(
 *              required={"user_type","first_name","last_name","username","email", "password", "university", "department"},
 *              @OA\Property(property="user_type", type="string", example="user", description="Update user type"),
 *              @OA\Property(property="first_name", type="string", example="Joe", description="User's first name"),
 *              @OA\Property(property="last_name", type="string", example="Smith", description="User's last name"),
 *              @OA\Property(property="username", type="string", example="joes", description="Username"),
 *              @OA\Property(property="email", type="string", example="example@user.com", description="User's email address"),
 *              @OA\Property(property="password", type="string", example="user12345", description="User's password"),
 *              @OA\Property(property="university", type="string", example="International Burch University", description="User's university name"),
 *              @OA\Property(property="department", type="string", example="IT", description="User's study department name")
 *          )
 *      )
 * )
 */
Flight::route("POST /addUser", function() {
   
   $payload = Flight::request()->data->getData();  //Getting data from the request payload.
   //Making an instance of Userservice [baseDao->userDao, baseService->userService, userRoutes->userService]
   $userService = new UserService();
   //Add user to db
   $result = $userService->addUser($payload);

   if($result) {
      Flight::json(['message' => 'User added successfully!']);
   } else{
      Fight::json(['message' => 'Failed to add user!']);
   }
});

/*

/**
     * @OA\Post(
     *      path="/login",
     *      tags={"users"},
     *      summary="Log in user to account.",
     *      @OA\Response(
     *           response=200,
     *           description="User data, or exception if user is not logged properly."
     *      ),
     *      @OA\RequestBody(
     *          description="User data payload",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", example="example@user.com", description="User's email address"),
     *              @OA\Property(property="password", type="string", example="user12345", description="User's password"),
     *          )
     *      )
     * )
     */
/*Flight::route("POST /login", function() {

   $payload = Flight::request()->data->getData();
   $userService = new UserService();

   $fatchedUser = $userService->login($payload);

   if($fatchedUser) {
      Flight::json(['message' => 'Login successfull!',
                    'user' => $fatchedUser]);
   } else {
      Flight::json(['message' => 'Login failed!']);
   }
});*/

/**
     * @OA\Get(
     *      path="/getAllUsers",
     *      tags={"users"},
     *      summary="Get all users.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Array of all users in the database."
     *      )
     * )
     */
Flight::route("GET /getAllUsers", function(){

      $userService = new UserService();
      $result = $userService->get_all();

      Flight::json([
         'result' => $result
      ]);

});

 /**
     * @OA\Get(
     *      path="/getUserById",
     *      tags={"users"},
     *      summary="Get user by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Get user data or 500 status code exception otherwise"
     *      )
     * )
     */
    Flight::route("GET /getUserById", function(){

         $userService = new UserService();
         $result = $userService->getUserById(Flight::get('user')->id);
   
         Flight::json([
            'result' => $result
         ]);
   });


/**
    * @OA\Put(
    *     path="/editUser/{id}",
    *     tags={"users"},
    *     summary="Edit user data.",
    *     security={
    *         {"ApiKey": {}}
    *      },
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
    *              @OA\Property(property="user_type", type="string", example="user", description="Update user type"),
    *              @OA\Property(property="first_name", type="string", example="Joe", description="New user's first name"),
    *              @OA\Property(property="last_name", type="string", example="Doe", description="New user's last name"),
    *              @OA\Property(property="username", type="string", example="joed", description="New username"),
    *              @OA\Property(property="email", type="string", example="example@user.com", description="New user's email address"),
    *              @OA\Property(property="password", type="string", example="user12345", description="New user's password"),
    *              @OA\Property(property="university", type="string", example="International Burch University", description="New user's university name"),
    *              @OA\Property(property="department", type="string", example="IT", description="New user's study department name")
    *         )
    *     )
    * )
    */
Flight::route("PUT /editUser/@id", function($id){
   
      $payload = Flight::request()->data->getData();
      $userService = new UserService();
      $result = $userService->editUser($payload,$id);

      Flight::json([
         'result' => $result
      ]);
});

 /**
     * @OA\Delete(
     *      path="/deleteUser/{id}",
     *      tags={"users"},
     *      summary="Delete user by id.",
     *      security={
     *         {"ApiKey": {}}
     *      },
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

});

?>