<?php
require_once('../db/db.php');
session_start();

function fetchUserId (PDO $conn, $email) {
    $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
}

function getUserType($conn, $userId){
    $sql = "SELECT users.FirstName, users.LastName, users.UserType, users.PhoneNumber, users.EmailAddress
            FROM Users WHERE userId=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);

    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $userType = $userInfo[0]["UserType"];
   
    return $userType;
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
$userType = getUserType($conn, $userId);
echo json_encode([
    'success' => true,
    'message' => "Информация за потребител:",
    'value' => $userType,
]);