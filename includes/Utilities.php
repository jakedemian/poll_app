<?php

function getPostParam($key){
    $res = "";
    if(isset($_POST[$key])){
        $res = $_POST[$key];
    }
    return $res;
}

function generateRandomString($length){
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $string = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, $max)];
    }
    return $string;
}