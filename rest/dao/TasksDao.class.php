<?php
require_once "BaseDao.class.php";

class TasksDao extends BaseDao {

    public function __construct(){
        parent::__construct("tasks");
    }

    public function getTaskByUserId($user_id) {
        return $this->query("SELECT * FROM tasks WHERE users_id = :users_id", ["users_id" => $user_id]);
    }
}
?>