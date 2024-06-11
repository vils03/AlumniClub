<?php
require_once "../models/User.php";
require_once('../db/db.php');


function getUserInfo($conn) {
    // Fetch all users
    $sql = "SELECT users.UserId, users.FirstName, users.LastName, users.UserType, users.PhoneNumber, users.EmailAddress
            FROM Users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userObjects = [];

    foreach ($users as $userInfo) {
        $userType = $userInfo['UserType'];

        if ($userType === 'graduate') {
            $graduateSql = "SELECT graduate.Class, major.MajorName, graduate.status, graduate.location, graduate.fn
                            FROM graduate
                            JOIN major ON graduate.MajorId = major.MajorId
                            WHERE graduate.graduateId = ?";
            $stmt = $conn->prepare($graduateSql);
            $stmt->execute([$userInfo['UserId']]);
            $grInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            $userObjects[] = new Graduate(null,
                $userInfo['FirstName'],
                $userInfo['LastName'],
                $userInfo['EmailAddress'],
                null,
                $userInfo['PhoneNumber'],
                $userInfo['UserType'],
                null,
                $grInfo['fn'],
                $grInfo['MajorName'],
                $grInfo['Class'],
                $grInfo['status'],
                $grInfo['location']
            );
        } elseif ($userType === 'recruiter') {
            $recruiterSql = "SELECT CompanyName
                             FROM recruiter
                             WHERE recruiter.recruiterId = ?";
            $stmt = $conn->prepare($recruiterSql);
            $stmt->execute([$userInfo['UserId']]);
            $recInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            $userObjects[] = new Recruiter(
                null,
                $userInfo['FirstName'],
                $userInfo['LastName'],
                $userInfo['EmailAddress'],
                null,
                $userInfo['PhoneNumber'],
                $userInfo['UserType'],
                null,
                $recInfo['CompanyName']
            );
        }
    }
    return $userObjects;
}

function exportUsersToCSV($conn) {
    // Get user info
    $users = getUserInfo($conn);

    // Write user data
    foreach ($users as $user) {
        $row = [
            $user->userType,
            $user->name,
            $user->lastname,
            $user->email,
            $user->phoneNumber,
        ];
        if($user instanceof Graduate){
            $res =array_merge($row,  [
                $user->fn,
                $user->major,
                $user->class,
                $user->status,
                $user->location,
            ]);
        }
        else if($user instanceof Recruiter){
            $res = array_merge($row, [
                $user->companyName,
            ]);
        }
        $csvData[] = $res;
    }

    // Convert CSV data to a string
    $csvString = '';
    foreach ($csvData as $row) {
        $encodedRow = array_map(function($value) {
            return mb_convert_encoding($value, 'UTF-8', 'auto');
        }, $row);
        $csvString .= implode(',', $encodedRow) . "\n";
    }
    return $csvString;
}
try {
    $db = new DB();
    $conn = $db->getConnection();
    
    $conn->exec("SET NAMES 'utf8'");
    
    $csvData = exportUsersToCSV($conn);
    
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="exported_users.csv"');
    
    echo "\xEF\xBB\xBF";
    
    echo $csvData;
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Неуспешно свързване с базата данни",
    ]);
}