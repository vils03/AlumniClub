<?php
    session_start();

    function fetchUserId (PDO $conn, $email) {
        $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
    }
    function getEvents($conn, $userId){
        $sql = "SELECT graduate.Class, major.MajorName 
        FROM `eventinfo` 
        JOIN `uecreated` ON eventinfo.EventId=uecreated.EventId 
        JOIN `users` ON uecreated.UserId=users.UserId 
        JOIN `graduate` ON users.UserId=graduate.GraduateId 
        JOIN `major` ON graduate.MajorId=major.MajorId 
        WHERE users.UserId=graduate.GraduateId AND users.userId=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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