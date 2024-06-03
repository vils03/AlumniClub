<?php

require_once "../models/Event.php";
    session_start();
    $phpInput = json_decode(file_get_contents('php://input'), true);

    if (empty($phpInput['name'])){ 
        echo json_encode([
            'success' => false,
            'message' => "Полето име на събитие е задължително.",
        ]);
    } 
    if (empty($phpInput['description'])){
        echo json_encode([
            'success' => false,
            'message' => "Полето описание на събитие е задължително",
        ]);
    }
    if (empty($phpInput['date'])){
        echo json_encode([
            'success' => false,
            'message' => "Полето дата на събитие е задължително",
        ]);
    }
    else {

        $name = $phpInput['name'];
        $description = $phpInput['description'];
        $date = $phpInput['date'];
        $image = $phpInput['image'];

        $event = new Event(null, $name, $description, $date, $image);

        try {

            $event->validateEvent();
            $_SESSION['email'] = $phpInput['email'];

            $event->saveInDB($_SESSION['email']);

            
            echo json_encode([
                'success' => true,
                'email' => $email,
            ]);
            
        } catch (Exception $e) {
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    
}