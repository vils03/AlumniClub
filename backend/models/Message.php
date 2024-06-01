<?php
    class Message{
        public $senderId;
        public $recepientId;
        public $text;

        public function __construct($senderId, $recepientId, $text){
            $this->senderId = $senderId;
            $this->recepientId = $recepientId;
            $this->text = $text;
        }

        public function validateMessage() : void {
            if(empty($text)){
                throw new Exception("Полето текст е задължително!");
            }
            if(strlen($text) > 100){
                throw new Exception("Съобщението не може да надвишава 100 символа!");
            }
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
                "INSERT INTO `messagesinfo` (SenderId, RecepientId, MessageText) 
                VALUES (:senderId, :recepientId, :text)"
            );
            $result = $statement->execute([
                'senderId' => $this->senderId,
                'recepientId' => $this->recepientId,
                'text' => $this->text
            ]);
            if(!$result){
                throw new Exception("Грешка при запис в базата данни: " . $statement->errorInfo()[2]);
            }
        }
    }

