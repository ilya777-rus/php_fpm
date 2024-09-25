<?php
class Categories{
    public $id;
    public $title;
    public $con;
    public function __construct($con){
        $this->con = $con;
    }

    public function read(){
        $sql = "SELECT * FROM Categories";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute()){
            return $stmt;
        }else{
            return $stmt->errorInfo();
        }
    }
}