<?php
require_once 'BaseService.php';
require_once __DIR__."/../dao/ExamsDao.class.php";

class ExamsService extends BaseService{
    public function __construct(){
        parent::__construct(new ExamsDao);
    } 

    public function addExam($data){

        $exam = [
            'title' => $data['title'],
            'details' => $data['details'],
            'date_time' =>$data['date_time'],
            'users_id' => $data['users_id']
        ];
        return $this->add($exam);//calling the add() method on previous instantiated BaseDao object 
    }
}
?>