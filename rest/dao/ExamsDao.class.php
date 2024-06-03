<?php
require_once "BaseDao.class.php";

class ExamsDao extends BaseDao {

    public function __construct(){
        parent::__construct("exams");
    }

    public function getExamByUserId($user_id) {
        return $this->query("SELECT * FROM exams WHERE users_id = :users_id", ["users_id" => $user_id]);
    }
}
?>