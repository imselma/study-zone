<?php
require_once 'BaseService.php';
require_once __DIR__."/../dao/TasksDao.class.php";

class TasksService extends BaseService{
    public function __construct(){
        parent::__construct(new TasksDao);
    } 

    public function addTask($data){

        $task = [
            'title' => $data['title'],
            'details' => $data['details'],
            'deadline' => $data['deadline'],
        ];
        return $this->add($task);//calling the add() method on previous instantiated BaseDao object 
    }

}
?>