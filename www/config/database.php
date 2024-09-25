<?php
    class Database{
        private $host='mysql2';
        private $port ='3306';
        private $dbname='tages';
        private $user = 'root';
        private $password='root';
        private $conn;
        
        public function getConnection(){
            $this->conn=null;
            try{
                $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->user, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e){
                echo 'Connection failed:' . $e->getMessage();
            }
            return $this->conn;
        }
    }
