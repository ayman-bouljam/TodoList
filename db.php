<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'todolist');

$conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

if($conn->connect_error){
    die("Connection falied: ". $conn->connect_error);
}else{
    echo "connection successful";
}