<?php

require_once "../models/Ad.php";
    session_start();
    $phpInput = json_decode(file_get_contents('php://input'), true);


    if (empty($phpInput['name'])){ 
        echo json_encode([
            'success' => false,
            'message' => "Полето име на обява е задължително.",
        ]);
    } 
    if (empty($phpInput['description'])){
        echo json_encode([
            'success' => false,
            'message' => "Полето описание на обява е задължително",
        ]);
    }
    else {
        $name = $phpInput['name'];
        $description = $phpInput['description'];

        $ad = new Ad(null, null, $name, $description);

        try {

            $ad->validateAd();

            $ad->saveInDB($_SESSION['email']);

            
            echo json_encode([
                'success' => true,
                'email' => $_SESSION['email'],
            ]);
            
        } catch (Exception $e) {
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    
}