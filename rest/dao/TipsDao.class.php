<?php
require_once "BaseDao.class.php";

class TipsDao extends BaseDao {

    public function __construct(){
        parent::__construct("study_tips");
    }
}
?>