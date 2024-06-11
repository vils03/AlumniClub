<?php

require_once "../models/User.php";
    session_start();
    $phpInput = json_decode(file_get_contents('php://input'), true);
    $user = $phpInput['userData'];
    $passes = $phpInput['eventData'];
    if (empty($passes['password-old'])){ 
        echo json_encode([
            'success' => false,
            'message' => "Полето стара парола е задължително.",
        ]);
    } 
    if (empty($passes['password-new'])){
        echo json_encode([
            'success' => false,
            'message' => "Полето нова парола е задължително",
        ]);
    }
    else {
        $oldp = $passes['password-old'];
        $newp = $passes['password-new'];
        $user = new User (null, null, null, $_SESSION['email'], null, null, null,null);
        $user->updatePass($oldp, $newp);
    
    }