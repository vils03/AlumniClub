<?php
    $uploadDir = '../../files/uploaded/';
    $uploadedFile = $uploadDir . basename($_FILES['photo']['name']);
    if(move_uploaded_file($_FILES['photo']['tmp_name'], $uploadedFile)){
        echo json_encode([
            'success' => true,
            'message' => "Успешно качване на изображение!",
        ]); 
    }
    else{
        echo json_encode([
            'success' => false,
            'message' => "Неуспешно качване на изображение!",
        ]);
    }
