<?php
require_once('../db/db.php');
session_start();

function fetchUserId (PDO $conn, $email) {
    $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
}

function getUserInfo($conn, $userId){
    $sql = "SELECT users.FirstName, users.LastName, users.UserType, users.PhoneNumber, users.EmailAddress, users.UserImage
            FROM Users WHERE userId=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);

    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $userType = $userInfo[0]["UserType"];
    $recInfo = [];

    if(strcmp($userType, 'graduate') == 0){
        $graduateSql = "SELECT graduate.Class, major.MajorName, graduate.Location, graduate.FN, graduate.Status
        FROM `graduate` JOIN `major` ON graduate.majorId=major.majorId
        WHERE graduate.graduateId=?";
        $stmt = $conn->prepare($graduateSql);
        $stmt->execute([$userId]);
        $grInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userInfo[0] += $grInfo[0];
    }
    else if(strcmp($userType, 'recruiter') == 0){
        $recruiterSql = "SELECT CompanyName
        FROM `recruiter`
        WHERE recruiter.recruiterId=?";
        $stmt = $conn->prepare($recruiterSql);
        $stmt->execute([$userId]);
        $recInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userInfo[0]['CompanyName'] = $recInfo[0]['CompanyName'];
    }
    return $userInfo;
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
$userInfo = getUserInfo($conn, $userId);
echo json_encode([
    'success' => true,
    'message' => "Информация за потребител:",
    'value' => $userInfo,
]);