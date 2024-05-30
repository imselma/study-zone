<?php
require_once 'BaseService.php';
require_once __DIR__."/../dao/AuthDao.class.php";

class AuthService extends BaseService{
    public function __construct(){
        parent::__construct(new AuthDao);
    } 

    public function loginByAuth($data){
       $email = $data['email'];
       $password = $data['password'];
       
       // Fetch user by email
       $user = $this->get_by_email($email);
       // Check if user exists with the provided email
       if (!empty($user)) {
        // Loop through each user to find the one with matching password
        foreach ($user as $user) {
            if (password_verify($password, $user['password'])){
            return $user; // User found and password is correct
           }
        }
      }
    } 
}
?>