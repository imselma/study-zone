<?php
require_once "BaseDao.class.php";

class AuthDao extends BaseDao {

    public function __construct(){
        parent::__construct("users");
    }
    

    public function getUserById($id) {
        return $this->query_unique("SELECT * FROM users WHERE id = :id", ["id" => $id]);
    }
    
}
?>