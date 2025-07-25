<?php

function handleImage($image, $uploadDir = "../images/img/"){
    if(isset($_FILES[$image]) && $_FILES[$image]['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES[$image]['tmp_name'];
        $fileName = $_FILES[$image]['name'];
        $fileSize = $_FILES[$image]['size'];
        $fileType = $_FILES[$image]['type'];

        $allowedFileTypes = ['image/jpeg', 'image/png'];
        if (!in_array($fileType, $allowedFileTypes)) {
            return ["error" => "Only JPG and PNG files are allowed."];
        }

        if ($fileSize > 5 * 1024 * 1024) {
            return ["error" => "File size exceeds 5MB limit."];
        }

        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . "." . $fileExtension;

        $destination = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $destination)) {
            return ["success" => true, "fileName" => $newFileName];
        } else {
            return ["error" => "Error moving the uploaded file."];
        }
    }

    return ["success" => true, "fileName" => "#"];

}

?>