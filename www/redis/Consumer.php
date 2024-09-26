<?php
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../src/Controllers/TweetController.php';


$controller = new TweetController();
$consumer = new Consumer('tweet_queue', 'processing_queue', $controller);
$consumer->run();
class Consumer{

    private string $queueName;
    private string $processingQueueName;
    public function __construct(string $queueName, string $processingQueueName, $controller)
    {
        $this->queueName = $queueName;
        $this->processingQueueName = $processingQueueName;
        $this->controller = $controller;
    }
    public function run(){
        while(true){
            echo "ждем ....<br>";

            $payload = $this->popFromQueue();

            if ($payload===false) {
                sleep(1);
                continue;
            }
            $this->process($payload);

            $this->removeFromProcessingQueue($payload);

          }
    }

    private function popFromQueue(){
        return $this->getRedis()->rawCommand("RPOPLPUSH", $this->queueName, $this->processingQueueName);
    }

    private function process(string $payload)
    {


        $data = json_decode($payload, true);

        $this->controller->createTweet($data);

    }

    private function removeFromProcessingQueue(string $payload)
    {
      
        $this->getRedis()->rawCommand('LREM', $this->processingQueueName, 1, $payload);
    }

    private function getRedis():Redis{
        $redis = new Redis();
        try {
            $redis->connect('redis2', 6379);
        } catch (Exception $e) {
            die("Could not connect to Redis: " . $e->getMessage());
        }
        return $redis;
    }

}
