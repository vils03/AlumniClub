<?php

require_once "../models/User.php";

if (isset($_FILES['csv']['name'])) {
    $fileTmpName = $_FILES['csv']['tmp_name'];
    $fileName = $_FILES['csv']['name'];

    $location = '../files/csv/' . $fileName;
    $fileContent = file_get_contents($_FILES['csv']['tmp_name']);

    $rows = explode("\n", $fileContent);

    for ($i = 0; $i < count($rows); $i++) {
        $row = explode(",", rtrim($rows[$i]));
        $password = '12345678';
        if(strcmp($row[0], 'recruiter') == 0){
            $recruiter = new Recruiter(null, $row[1], $row[2], $row[3], $password, $row[4], $row[0], 'default_pic.jpg', $row[5]);
            $recruiter->storeInDB();
        }
        else if(strcmp($row[0], 'graduate') == 0){
            $graduate = new Graduate(null, $row[1], $row[2], $row[3], $password, $row[4], $row[0], 'default_pic.jpg', $row[5], $row[6], 
            $row[7], $row[8], $row[9]);
            $graduate->storeInDB();
            
        }
        else{
            echo json_encode([
                'success' => false,
                'message' => "Нeвалиден тип потребител!",
                'value' => $row[0]
            ]);
            exit();
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => "Успешно записани потребители!",
    ]);
}
