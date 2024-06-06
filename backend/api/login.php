<?php
// Set session configuration options
ini_set('session.cookie_lifetime', 0); // Session cookie lifetime, 0 means until the browser is closed
ini_set('session.use_strict_mode', 1); // Use strict mode to prevent session fixation
ini_set('session.use_cookies', 1); // Use cookies to store session ID
ini_set('session.use_only_cookies', 1); // Don't use URL-based session IDs
session_start();
$phpInput = json_decode(file_get_contents('php://input'), true);
require_once "../models/User.php";
mb_internal_encoding("UTF-8");



if (empty($phpInput['email'])){ 
    echo json_encode([
        'success' => false,
        'message' => "Полето имейл е задължително.",
    ]);
} 
if (empty($phpInput['password'])){
    echo json_encode([
        'success' => false,
        'message' => "Полето парола е задължително",
    ]);
}
else {

        $email = $phpInput['email'];
        $password = $phpInput['password'];

        $user = new User(null, null, null, $email, $password, null, null, null);

        try {

            $user->checkLogin();

            $_SESSION['email'] = $phpInput['email'];

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