<?php
require_once('../db/db.php');
    session_start();

    function fetchUserId (PDO $conn, $email) {
        $sql = "SELECT UserId, UserType FROM Users WHERE emailaddress = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getGraduateEvents($conn, $userId){
        $sql = "SELECT graduate.Class, major.MajorName
        FROM `users`
        JOIN `graduate` ON users.UserId=graduate.GraduateId 
        JOIN `major` ON graduate.MajorId=major.MajorId 
        WHERE users.UserId=graduate.GraduateId AND users.userId=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        $grInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $eventSql = "SELECT eventinfo.EventName, eventinfo.EventDesc, eventinfo.CreatedEventDateTime, eventinfo.EventImage
        FROM eventinfo 
        JOIN usertoevent ON eventinfo.EventId=usertoevent.EventId
        JOIN graduate ON graduate.GraduateId=usertoevent.UserId 
        JOIN major ON Major.MajorId=graduate.MajorId 
        WHERE (major.MajorName=:major OR graduate.Class=:class) 
        AND NOT EXISTS (
                    SELECT 1
                    FROM usertoevent
                    WHERE usertoevent.eventid = eventinfo.eventid
                    AND usertoevent.userid = :userid);";

        $eventStmt = $conn->prepare($eventSql);
        $eventStmt->execute([
            "major" => $grInfo[0]['MajorName'],
            "class" => $grInfo[0]['Class'],
            "userid" => $userId,
        ]);
        $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);
        $recruiterEvents = "SELECT eventinfo.EventName, eventinfo.EventDesc, eventinfo.CreatedEventDateTime, eventinfo.EventImage
                            FROM eventinfo 
                            JOIN usertoevent ON eventinfo.EventId=usertoevent.EventId 
                            JOIN users ON users.UserId=usertoevent.UserId 
                            WHERE users.UserType='recruiter'
                            AND NOT EXISTS (
                                SELECT 1
                                FROM usertoevent
                                WHERE usertoevent.eventid = eventinfo.eventid
                                AND usertoevent.userid = ?)";
        $recStmt = $conn->prepare($recruiterEvents);
        $recStmt->execute([$userId]);
        $recEvents = $recStmt->fetchAll(PDO::FETCH_ASSOC);
        return array_merge($events, $recEvents);
    }

    function getRecruiterEvents($conn, $userId){
        $eventSql = "SELECT eventinfo.EventName, eventinfo.EventDesc, eventinfo.CreatedEventDateTime, eventinfo.EventImage
                    FROM eventinfo
                    WHERE NOT EXISTS (
                    SELECT 1
                    FROM usertoevent
                    WHERE usertoevent.eventid = eventinfo.eventid
                    AND usertoevent.userid = ?);";

        $eventStmt = $conn->prepare($eventSql);
        $eventStmt->execute([$userId]);
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
    $userId = fetchUserId($conn, $_SESSION['email'])[0]['UserId'];
    $userType = fetchUserId($conn, $_SESSION['email'])[0]['UserType'];
    if(strcmp($userType, 'graduate') == 0){
        $events = getGraduateEvents($conn, $userId);
    }
    else{
        $events = getRecruiterEvents($conn, $userId);
    }
    echo json_encode([
        'success' => true,
        'message' => "Списък от събития:",
        'value' => $events,
    ]);