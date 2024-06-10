<?php
require_once('../db/db.php');

function getAdInfo($conn){
    $sql = "SELECT adName, adDesc, createdEventDateTime
            FROM adinfo";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $adInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $adInfo;
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
$adInfo = getAdInfo($conn);
echo json_encode([
    'success' => true,
    'message' => "Информация за обяви:",
    'value' => $adInfo,
]);