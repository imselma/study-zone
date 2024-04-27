<?php

Flight::route('GET /connection-check', function(){
    $dao = new BaseDao("users");
});

//Adding the user (User register)
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

//User login
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

// Get all users
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

//Edit user
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

//Delete user
Flight::route("DELETE /deleteUser/@id", function($id){

   $userService = new UserService();

   Flight::user_service()->delete($id);
   Flight::json(['message' => 'User succesfully deleted!']);
});

?>