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

function exportUsersToCSV($conn, $filename) {
    // Get user info
    $users = getUserInfo($conn);

    // Open file for writing
    $file = fopen($filename, 'w+');

    // Write user data
    foreach ($users as $user) {
        $row = [
            $user->userType,
            $user->name,
            $user->lastname,
            $user->email,
            $user->phoneNumber,
            $user instanceof Graduate ? $user->fn : '',
            $user instanceof Graduate ? $user->major : '',
            $user instanceof Graduate ? $user->class : '',
            $user instanceof Graduate ? $user->status : '',
            $user instanceof Graduate ? $user->location : '',
            $user instanceof Recruiter ? $user->companyName : ''
        ];
        $csvData[] = $row;
    }

    // Convert CSV data to a string
    $csvString = '';
    foreach ($csvData as $row) {
        $csvString .= implode(',', $row) . "\n";
    }
    return $csvString;
}
try{
    $db = new DB();
    $conn=$db->getConnection();
    $csvData = exportUsersToCSV($conn, '../files/csv/exported_users.csv');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_users.csv"');
    echo $csvData;
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Неуспешно свързване с базата данни",
    ]);
}
