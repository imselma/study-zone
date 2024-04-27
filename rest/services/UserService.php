<?php
require_once 'BaseService.php';
require_once __DIR__."/../dao/UserDao.class.php";

class UserService extends BaseService{
    public function __construct(){
        parent::__construct(new UserDao);
    } 

    public function addUser($data){

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = [
            'user_type' => $data['user_type'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hash,
            'university' => $data['university'],
            'department' => $data['department'],
        ];
        return $this->add($user);//calling the add() method on previous instantiated BaseDao object 
    }

    public function login($data){
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