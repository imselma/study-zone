<?php
require_once "BaseDao.class.php";

class TasksDao extends BaseDao {

    public function __construct(){
        parent::__construct("tasks");
    }
}
?>