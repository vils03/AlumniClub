<?php
session_start();
$phpInput = json_decode(file_get_contents('php://input'), true);
require_once "../models/User.php";
mb_internal_encoding("UTF-8");

if($phpInput['type'] == 'graduate'){
    $user = new Graduate(null, $phpInput['name'], $phpInput["last-name"], null, null, 
                            $phpInput['phone-number'], $phpInput['type'], 'default_pic.jpg',$phpInput['fn'], $phpInput['major'], 
                            $phpInput['class'], $phpInput['status'], $phpInput['location']);
}
else{
    $user = new Recruiter(null, $phpInput['name'], $phpInput["last-name"], null, null, 
    $phpInput['phone-number'], $phpInput['type'], 'default_pic.jpg', $phpInput['company']);
}

try {
    $user->updateInDB($_SESSION['email']);
    echo json_encode([
        'success' => true,
        'message' => 'Успешна промяна!'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}  