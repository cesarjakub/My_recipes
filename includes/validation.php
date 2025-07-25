<?php

function validatePasswords($pass1, $pass2){
    if($pass1 === $pass2){
        return TRUE;
    }
    return FALSE;
}

function validateLenght($pass1, $pass2){
    if(strlen($pass1) >= 8 && strlen($pass2) >= 8){
        return TRUE;
    }
    return FALSE;
}

function validatePasswordCheckForWhiteSpace($password) {
    if (preg_match('/\s+/', $password)) {
        return FALSE;
    }
    return TRUE;
}

function validateName($name) {
    if (preg_match('/^[a-zA-Zá-žÁ-Ž\s]+$/', $name)) {
        return TRUE;
    }
    return FALSE;
}

function validateEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return TRUE;
    }
    return FALSE;
}

function validateEmailExist($email, $users){
    foreach ($users as $user) {
        if($user["email"] === $email){
            return FALSE;
        }
    }
    return TRUE;
}


?>