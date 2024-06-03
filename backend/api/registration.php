<?php

$phpInput = json_decode(file_get_contents('php://input'), true);
require_once "../models/User.php";
mb_internal_encoding("UTF-8");

if($phpInput['type'] == 'graduate'){
    $user = new Graduate(null, $phpInput['name'], $phpInput["last-name"], $phpInput['email'], $phpInput['password'], 
                            $phpInput['phone-number'], $phpInput['type'], 'default_pic.jpg',$phpInput['fn'], $phpInput['major'], 
                            $phpInput['class'], $phpInput['status'], $phpInput['location']);
}
else{
    $user = new Recruiter(null, $phpInput['name'], $phpInput["last-name"], $phpInput['email'], $phpInput['password'], 
    $phpInput['phone-number'], $phpInput['type'], 'default_pic.jpg', $phpInput['company']);
}

try {
    $user->validate();
    $user->storeInDB();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}    