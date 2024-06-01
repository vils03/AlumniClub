<?php
    class Ad{
        public $id;
        public $recruiterId;
        public $name;
        public $description;

        public function __construct($id, $recruiterId, $name, $description){
            $this->id = $id;
            $this-> $recruiterId = $recruiterId;
            $this->name = $name;
            $this->description = $description;
        }

        public function saveInDB() : void{
            require_once "../db/db.php";
            try{
                $db = new DB();
                $con = $db->getConnection();
            }
            catch(PDOException $e){
                echo json_encode([
                    'success' => false,
                    'message' => 'Неуспешно свързване с базата от данни!'
                ]);
            }
            $statement = $con->prepare(
                "INSERT INTO `adinfo` (RecruiterId, AdName, AdDesc) 
                VALUES (:recruiterId, :name, :description)"
            );
            $result = $statement->execute([
                'recruiterId' => $this->recruiterId,
                'name' => $this->name,
                'description' => $this->description
            ]);
            if(!$result){
                throw new Exception("Грешка при запис в базата данни: " . $statement->errorInfo()[2]);
            }
        }

        public function validateAd() : void{
            if(empty($name)){
                throw new Exception("Полето име е задължително!");
            }
            if(empty($description)){
                throw new Exception("Полето описание е задължително!");
            }
            if(strlen($name) < 5){
                throw new Exception("Името трябва да е поне 5 символа!");
            }
            if(strlen($name) > 50){
                throw new Exception("Името трябва да е не повече от 50 символа!");
            }
            if(strlen($description) < 10){
                throw new Exception("Описанието трябва да е поне 10 символа!");
            }
            if(strlen($description) > 200){
                throw new Exception("Описанието трябва да е не повече от 200 символа!");
            }
        }
    }
