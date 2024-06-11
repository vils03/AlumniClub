<?php

class Event{
    public $id;
    public $name;
    public $description;
    public $createdDateTime;
    public $eventImage;

    public function __construct($id, $name, $description, $createdDateTime, $eventImage){
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdDateTime = $createdDateTime;
        $this->eventImage = $eventImage;
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

    public function fetchUserId (PDO $conn, $email) {
        $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
    }

    public function fetchEventId (PDO $conn, $name, $description) {
        $sql = "SELECT EventId FROM EventInfo WHERE EventName = ? AND EventDesc= ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description]);

        return $stmt->fetch(PDO::FETCH_ASSOC)["EventId"];
    }

    public function saveInDB($email) : void {
        require_once "../db/db.php";
        try{
            $db = new DB();
            $conn = $db->getConnection();
            $conn->beginTransaction();
        }
        catch(PDOException $e){
            echo json_encode([
                'success' => false,
                'message' => 'Неуспешно свързване с базата от данни!'
            ]);
        }
        try{
            $statement = $conn->prepare(
                "INSERT INTO `eventinfo` (EventName, EventDesc, CreatedEventDateTime, EventImage) 
                VALUES (:name, :description, :createdDateTime, :eventImage)"
            );

            $statementCross = $conn->prepare(
                "INSERT INTO `usertoevent` (UserId, EventId, Accepted, Created)
                VALUES (:userid, :eventid, :accepted, :created)"
            );

            $resultEvent = $statement->execute([
                'name' => $this->name,
                'description' => $this->description,
                'createdDateTime' => $this->createdDateTime,
                'eventImage' => $this->eventImage
            ]);
            
            $userId = $this->fetchUserId($conn, $email);
            $eventId = $this->fetchEventId($conn, $this->name, $this->description);

            $resultCross = $statementCross->execute([
                'userid' => $userId,
                'eventid' => $eventId,
                'accepted' => 1,
                'created' => 1
            ]);
            if ($resultCross) {
                $conn->commit();
            } else {
                $conn->rollback();
            }
        }
        catch(PDOException $e)
        {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }

        if(!$resultEvent){
            throw new Exception("Грешка при запис в базата данни: " . $statement->errorInfo()[2]);
        }
    }

    public function saveInUserToEvents($email) : void {
        require_once "../db/db.php";
        try{
            $db = new DB();
            $conn = $db->getConnection();
            $conn->beginTransaction();
        }
        catch(PDOException $e){
            echo json_encode([
                'success' => false,
                'message' => 'Неуспешно свързване с базата от данни!'
            ]);
        }
        try{

            $statementCross = $conn->prepare(
                "INSERT INTO `usertoevent` (UserId, EventId, Accepted, Created)
                VALUES (:userid, :eventid, :accepted, :created)"
            );
            
            $userId = $this->fetchUserId($conn, $email);
            $eventId = $this->fetchEventId($conn, $this->name, $this->description);

            $resultCross = $statementCross->execute([
                'userid' => $userId,
                'eventid' => $eventId,
                'accepted' => 1,
                'created' => false
            ]);
            if ($resultCross) {
                $conn->commit();
            } else {
                $conn->rollback();
            }
        }
        catch(PDOException $e)
        {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }
}
