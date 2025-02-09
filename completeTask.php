<?php
include "db.php";
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $id = $_GET['id'];
    if($id){
        $stmt = $conn->prepare("UPDATE tasks SET task_completed = 1, task_inprogress = 0  WHERE task_id = ?");
        $stmt -> bind_param('i', $id);
        $stmt->execute();
        $stmt-> close();
        header("location:index.php");
        exit;
    }
}