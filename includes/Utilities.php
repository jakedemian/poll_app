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

function getDatabaseConnection(){
    $connInfo=simplexml_load_file(__dir__ . "/../db_conn_info.xml") or die("Error: Unable to locate db_conn_info.xml");
    $hostname = $connInfo->hostname;
    $username = $connInfo->username;
    $password = $connInfo->password;
    $defaultdb = $connInfo->defaultdb;

    if(!$hostname || !$username || !$password || !$defaultdb){
        die("A required field is missing from db_conn_info.xml");
    }

    $conn = new mysqli($hostname, $username, $password, $defaultdb);
    if ($conn->connect_errno) {
        die("Failed to connect to the database with the provided credentials.");
    }

    return $conn;
}

function safePrint($s){
    echo htmlspecialchars($s);
}