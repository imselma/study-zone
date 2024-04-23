<?php
require_once "BaseDao.class.php";

class NotesDao extends BaseDao {

    public function __construct(){
        parent::__construct("notes");
    }
}
?>