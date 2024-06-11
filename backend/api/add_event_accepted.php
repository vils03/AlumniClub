<?php

require_once "../models/Event.php";
    session_start();
    $phpInput = json_decode(file_get_contents('php://input'), true);

        $name = $phpInput['EventName'];
        $description = $phpInput['EventDesc'];
        $date = $phpInput['CreatedEventDateTime'];
        $image = basename($phpInput['EventImage']);

        $event = new Event(null, $name, $description, $date, $image);

        try {

            $event->saveInUserToEvents($_SESSION['email']);

            echo json_encode([
                'success' => true,
                'email' => $_SESSION['email'],
                'event' => $phpInput['EventName']
            ]);
            
        } catch (Exception $e) {
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    
