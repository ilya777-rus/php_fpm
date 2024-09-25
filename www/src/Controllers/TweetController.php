<?php

require_once  './config/database.php';

require_once './src/Models/Tweet.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Content-Type: application/json; charset=utf-8');

class TweetController{
    private $conn;
    private $tweet;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->tweet = new Tweet($this->conn);
    }

    public function getAllTweets(){

        $result = $this->tweet->read();

        $arr_tweets = [];
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            array_push($arr_tweets, $row);
        }

        if (count($arr_tweets) > 0) {
            echo json_encode($arr_tweets);
        }else{
            $result =  [
                "message" => "No tweets were found."
            ];
            echo json_encode($result);
        }

    }
    public function getTweetById($id){
        $stmt = $this->tweet->readOne($id);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res){
            echo json_encode($res);
        }else{

           http_response_code(404);
           $result = [
               "status" => false,
               "message" => "Tweet not found"
           ];
           echo json_encode($result);
        }
    }

    public function createTweet($data){

        print_r('createTweet\n');

        if (isset($data["Username"]) && isset($data["Content"]) && isset($data["Category_id"])){

            $currentDate = date('Y-m-d H:i:s');
            $this->tweet->username = $data["Username"];
            $this->tweet->content = $data["Content"];
            $this->tweet->category_id = $data["Category_id"];
            $this->tweet->created_at = $currentDate;
            $result = $this->tweet->create();

            if ($result['status']){
                var_dump('OOOOOOOOOOOOOOOOOOOOOOOOOOO');
                http_response_code(201);
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }else{
                http_response_code(503);
                echo json_encode(['message'=>'Не удалось создать пользователя']);
            }
        }else{
            http_response_code(400);
            echo json_encode(['message' => 'Данные неполные..']);
        }
    }

    public function updateTweet($id){
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->username) && isset($data->content) && isset($data->category_id)){
            $this->tweet->username = $data->username;
            $this->tweet->content = $data->content;
            $this->tweet->category_id = $data->category_id;
            $this->tweet->id = $id;
            $result = $this->tweet->update();
            if ($result['status']){
                http_response_code(200);
                echo json_encode($result);
            }else{
                http_response_code(503);
                echo json_encode(['message'=>'Не удалось обновить пользователя']);
            }
        }else{
            http_response_code(400);
            echo json_encode(['message' => 'Данные неполные..']);
        }
    }

    public function deleteTweet($id){
        $data = json_decode(file_get_contents("php://input"));
        if (isset($id)){
            $this->tweet->id = $id;
            $result = $this->tweet->delete();
            if ($result['status']){
                http_response_code(200);
                echo json_encode($result);
            }else{
                http_response_code(503);
                echo json_encode(["message"=>"Не удалось обновить tweet"]);
            }
        }else{
            http_response_code(400);
            echo json_encode(["message"=>"Укажите твит для удаления!"]);
        }
    }


}
