<?php

$method = $_SERVER['REQUEST_METHOD'];

require 'src/Controllers/TweetController.php';
require 'src/Controllers/CategoriesController.php';
require 'redis/Producer.php';

$requestUri = $_SERVER['REQUEST_URI'];
$params = explode('/', trim($requestUri, '/'));

$type =$params[1];
$id = $params[2] ??  null ;

$controller = new TweetController();
$producer = new Producer('tweet_queue');
if ($method==='GET') {
    if ($type === 'tweets' ) {

        if (isset($id)) {
            $controller->getTweetById($id);
        } else {

            $controller->getAllTweets();
        }
    }elseif ($type==='categories'){
        $category = new CategoryController();
        $category->getAllcats();
    }
}elseif ($method==='POST') {
    if ($type==='tweets'){

        $data = json_decode(file_get_contents('php://input'), true);
        
        $producer->send($data);
   
        echo json_encode(['status' => true]);

    }
}elseif ($method==="PATCH"){
    if ($type === 'tweets' ) {

        if (isset($id)) {

            $controller->updateTweet($id);
        } else {
            http_response_code(400);
            echo json_encode(["message"=>"Введите твит и его id!"]);

        }
    }
}elseif($method==="DELETE"){

    if ($type==="tweets"){
        if (isset($id)){
            $controller->deleteTweet($id);
        }
    }
}

