<?php
require_once  './config/database.php';

require_once './src/Models/Categories.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Content-Type: application/json; charset=utf-8');

class CategoryController{

    private $con;
    private $category;
    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->category = new Categories($this->conn);  
    }

    public function getAllcats(){
        $stmt = $this->category->read();
        $arr = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($arr, $row);
        }

        if (count($arr)>0){
            echo json_encode($arr);
        }else{
            $message = ["message"=>"not is categories"];
            echo json_encode($message);
        }
    }
}