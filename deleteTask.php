<?php
include "db.php";

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $id = $_GET['id'];
    if(isset($id) && !empty($id)){
        $stmt = $conn->prepare("DELETE FROM tasks  WHERE task_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->close();
        header("location:index.php");
        exit;
    }
}