<?php
require_once 'BaseService.php';
require_once __DIR__."/../dao/NotesDao.class.php";

class NotesService extends BaseService{
    public function __construct(){
        parent::__construct(new NotesDao);
    } 

    public function addNote($data){

        $note = [
            'title' => $data['title'],
            'details' => $data['details'],
        ];
        return $this->add($note);//calling the add() method on previous instantiated BaseDao object 
    }
}
?>