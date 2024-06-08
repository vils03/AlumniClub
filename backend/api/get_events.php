<?php
require_once('../db/db.php');
    session_start();

    function fetchUserId (PDO $conn, $email) {
        $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
    }
    function getEvents($conn, $userId){ // nneeds one more query
        $sql = "SELECT graduate.Class, major.MajorName 
        FROM `eventinfo` 
        JOIN `usertoevent` ON eventinfo.EventId=usertoevent.EventId 
        JOIN `users` ON usertoevent.UserId=users.UserId 
        JOIN `graduate` ON users.UserId=graduate.GraduateId 
        JOIN `major` ON graduate.MajorId=major.MajorId 
        WHERE users.UserId=graduate.GraduateId AND users.userId=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        $grInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $eventSql = "SELECT eventinfo.EventName, eventinfo.EventDesc, eventinfo.CreatedEventDateTime 
        FROM eventinfo 
        JOIN usertoevent ON eventinfo.EventId=usertoevent.EventId 
        JOIN graduate ON graduate.GraduateId=usertoevent.UserId 
        JOIN major ON Major.MajorId=graduate.MajorId 
        WHERE major.MajorName=:major OR graduate.Class=:class";

        $eventStmt = $conn->prepare($eventSql);
        $eventStmt->execute([
            "major" => $grInfo[0]['MajorName'],
            "class" => $grInfo[0]['Class'],
        ]);

        return $eventStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    try{
        $db = new DB();
        $conn=$db->getConnection();
    }
    catch (PDOException $e){
        echo json_encode([
            'success' => false,
            'message' => "Неуспешно свързване с базата от данни!",
            'value' => null,
        ]);
        exit();
    }
    $userId = fetchUserId($conn, $_SESSION['email']);
    $events = getEvents($conn, $userId);
    echo json_encode([
        'success' => true,
        'message' => "Списък от събития:",
        'value' => $events,
    ]);