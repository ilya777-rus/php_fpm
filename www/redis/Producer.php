<?php

class Producer{

    private string $queueName;
    public function __construct($queueName)
    {
        $this->queueName = $queueName;
    }

    public function send(array $data){
        $payload = json_encode($data);

        $this->pushToQueue($payload);
    }
    private function pushToQueue(string $payload){
        $redis = $this->getRedis();

        $redis->rawCommand('RPUSH', $this->queueName, $payload);
        $res=$redis->rawCommand("LRANGE", $this->queueName, 0,-1);
    }
    private function getRedis()
    {
        try{
        $redis = new Redis();
        $redis->connect('redis2', 6379);
        if ($redis->ping()){
            return $redis;
        }
        } catch (RedisException $e) {
        die( $e->getMessage());
        }
        return $redis;
    }
}
