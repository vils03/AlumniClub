<?php

class Event{
    public $id;
    public $name;
    public $description;
    public $createdDateTime;

    public function __construct($id, $name, $description, $createdDateTime){
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdDateTime = $createdDateTime;
    }

    public function validateEvent() : void {
        if(empty($this->name)){
            throw new Exception("Полето име е задължително!");
        }
        if(empty($this->description)){
            throw new Exception("Полето описание е задължително!");
        }
        if(empty($this->createdDateTime)){
            throw new Exception("Полето дата и час е задължително!");
        }
        if(strlen($this->name)<5){
            throw new Exception("Полето име трябва да съдържа поне 5 символа!");
        }
        if(strlen($this->name)>30){
            throw new Exception("Полето име трябва да е не повече от 30 символа!");
        }
        if(strlen($this->description)<10){
            throw new Exception("Полето описание трябва да съдържа поне 10 символа!");
        }
        if(strlen($this->description)>200){
            throw new Exception("Полето описание трябва да е не повече от 200 символа!");
        }
    }

    public function saveInDB() : void {
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
            "INSERT INTO `eventinfo` (EventName, EventDesc, CreatedEventDateTime) 
             VALUES (:name, :description, :createdDateTime)"
        );
        $result = $statement->execute([
            'name' => $this->name,
            'description' => $this->description,
            'createdDateTime' => $this->createdDateTime
        ]);
        if(!$result){
            throw new Exception("Грешка при запис в базата данни: " . $statement->errorInfo()[2]);
        }
    }
}
