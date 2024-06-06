<?php
session_start();

function fetchUserId (PDO $conn, $email) {
    $sql = "SELECT UserId FROM Users WHERE emailaddress = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
}

function getUserInfo($conn, $userId){
    $sql = "SELECT users.FirstName, users.LastName, users.UserType, users.PhoneNumber, users.EmailAddress
            FROM Users WHERE userId=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);

    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $userType = $userInfo["UserType"];
    $allInfo = $userInfo;

    if(strcmp($userType, 'graduate')){
        $graduateSql = "SELECT graduate.Class, major.MajorName
        FROM `graduate` JOIN `major` ON graduate.MajorId=major.MajorId
        WHERE graduate.graduateId=?";
        $stmt = $conn->prepare($graduateSql);
        $stmt->execute([$userId]);
        $allInfo |= $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else if(strcmp($userType, 'recruiter')){
        $recruiterSql = "SELECT CompanyName
        FROM `recruiter`
        WHERE recruiter.recruiterId=?";
        $stmt = $conn->prepare($recruiterSql);
        $stmt->execute([$userId]);
        $allInfo |= $stmt->fetch(PDO::FETCH_ASSOC)["CompanyName"];
    }
    return $allInfo;
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