<?php
require_once "BaseDao.class.php";

class ExamsDao extends BaseDao {

    public function __construct(){
        parent::__construct("exams");
    }
}
?>