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


switch([$method, $type, $id]){
    case ['GET', 'categories', NULL]:
        $category = new CategoryController();
        $category->getAllcats();
        break;

    case ['GET', 'tweets', NULL]:
        $controller->getAllTweets();
        break;

    case ['GET', 'tweets', $id]:
        $controller->getTweetById($id);
        break;

    case ['POST', 'tweets', NULL]:
        $data = json_decode(file_get_contents('php://input'), true);
        $producer->send($data);
        echo json_encode(['status' => true]);
        break;

    // case ['PATCH', 'tweets', $id]:
    //     if (isset($id)) {
    //         $controller->updateTweet($id);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(["message"=>"Введите твит и его id!"]);
    //     }
    //     break;

    case ['DELETE', 'tweets', $id]:
        $controller->deleteTweet($id);
        break;

    default:
        http_response_code(400); 
        echo json_encode([
            'status' => false,
            'message' => 'Некорректный запрос: неправильный метод или тип'
        ]);
        break;
}
