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
        public function fetchUserId (PDO $conn, $email) {
            $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email]);
    
            return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
        }

        public function saveInDB($email) : void{
            require_once "../db/db.php";
            try{
                $db = new DB();
                $conn = $db->getConnection();
            }
            catch(PDOException $e){
                echo json_encode([
                    'success' => false,
                    'message' => 'Неуспешно свързване с базата от данни!'
                ]);
            }
            $statement = $conn->prepare(
                "INSERT INTO `adinfo` (RecruiterId, AdName, AdDesc, CreatedEventDateTime) 
                VALUES (:recruiterId, :name, :description, NOW())"
            );
            $userId = $this->fetchUserId($conn, $email);
            $result = $statement->execute([
                'recruiterId' => $userId,
                'name' => $this->name,
                'description' => $this->description
            ]);
            if(!$result){
                throw new Exception("Грешка при запис в базата данни: " . $statement->errorInfo()[2]);
            }
        }

        public function validateAd() : void{
            if(empty($this->name)){
                throw new Exception("Полето име е задължително!");
            }
            if(empty($this->description)){
                throw new Exception("Полето описание е задължително!");
            }
            if(strlen($this->name) < 5){
                throw new Exception("Името трябва да е поне 5 символа!");
            }
            if(strlen($this->name) > 50){
                throw new Exception("Името трябва да е не повече от 50 символа!");
            }
            if(strlen($this->description) < 10){
                throw new Exception("Описанието трябва да е поне 10 символа!");
            }
            if(strlen($this->description) > 200){
                throw new Exception("Описанието трябва да е не повече от 200 символа!");
            }
        }
    }
