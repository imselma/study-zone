<?php
require_once "BaseDao.class.php";

class NotesDao extends BaseDao {

    public function __construct(){
        parent::__construct("notes");
    }

    public function getNoteByUserId($user_id) {
        return $this->query("SELECT * FROM notes WHERE users_id = :users_id", ["users_id" => $user_id]);
    }
}
?>