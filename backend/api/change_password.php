<?php

    session_start();
    require_once "../models/User.php";
    
    $phpInput = json_decode(file_get_contents('php://input'), true);
    mb_internal_encoding("UTF-8");
    
    if (empty($phpInput['password-old'])){ 
        echo json_encode([
            'success' => false,
            'message' => "Полето стара парола е задължително.",
        ]);
    } 
    if (empty($phpInput['password-new'])){
        echo json_encode([
            'success' => false,
            'message' => "Полето нова парола е задължително",
        ]);
    }
    else {
        $userEmail = $_SESSION['email'];
        $user = new User (null, null, null, $userEmail, null, null, null, null);

        try {
            $user->updatePass($phpInput['password-new'], $phpInput['password-old']);
            echo json_encode([
                'success' => true,
                'message' => 'Успешна промяна!',
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }  
    }