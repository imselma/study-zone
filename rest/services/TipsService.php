<?php
require_once 'BaseService.php';
require_once __DIR__."/../dao/TipsDao.class.php";

class TipsService extends BaseService{
    public function __construct(){
        parent::__construct(new TipsDao);
    } 

    public function addTip($data){

        $tip = [
            'category' => $data['category'],
            'details' => $data['details'],
            'title' => $data['title'],
        ];
        return $this->add($tip);//calling the add() method on previous instantiated BaseDao object 
    }

}
?>