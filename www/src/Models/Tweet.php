<?php

class Tweet
{
    public $id;
    public $content;
    public $username;
    public $category_id;
    public $created_at;
    private $conn;
    private $str;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function read()
    {
        $sql = "SELECT * FROM Tweets";
        $sql2 = "SELECT Tweets.Id,Username,Content,Categories.Title as Category_title,CreatedAt
                            FROM Tweets
                            LEFT JOIN Categories ON Tweets.Category_id = Categories.Id;
                            ";

        $stmt = $this->conn->prepare($sql2);
        if ($stmt->execute()) {
            return $stmt;
        } else {
            return $stmt->errorInfo();
        }
    }
    public function readOne($id)
    {
        $sql = "SELECT * FROM Tweets WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $res  = $stmt->execute();

        if ($stmt->execute()){
            return $stmt;
        }else{
            return $stmt->errorInfo();
        }
    }

    public function create(){
        
        $sql = "INSERT INTO Tweets  SET Username=:username, Content=:content,CreatedAt=:created_at, Category_id=:category_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":category_id", $this->category_id);
        if($stmt->execute()){

            $last_id = $this->conn->lastInsertId();
            $res = [
                'status' => true,
            ];

            return $res;
        }else{
            return $stmt->errorInfo();
        }
    }

    public function update(){

        $sql = "UPDATE Tweets SET Username=:username, Content=:content, Category_id=:category_id WHERE Id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()){
            $tweet_id = $this->conn->lastInsertId();
            $result = [
                'status' => true,
                'message' => "tweet is updated!",
            ];
            return $result;
        }else{
            return $stmt->errorInfo();
        }
    }

    public function delete(){
        $sql = "DELETE FROM Tweets WHERE Id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()){
            $result = [
                'status' => true,
                'message' => "tweet is deleted!",
            ];
            return $result;
        }else{
            return $stmt->errorInfo();
        }
    }

}