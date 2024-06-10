<?php

require_once('../db/db.php');
session_start();
$phpInput = json_decode(file_get_contents('php://input'), true);

function fetchUserId (PDO $conn, $email) {
    $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
}


function setImage($conn, $userId, $image) {
    $sql = "UPDATE users
            SET UserImage = :img
            WHERE UserId = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'img' => $image,
        'id' => $userId 
    ]);
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
$image = basename($phpInput['image']);
setImage($conn, $userId, $image);
echo json_encode([
    'success' => true,
    'message' => "Успешна промяна",
]);
