<?php
    include "db.php";
    if($_SERVER['REQUEST_METHOD'] === "GET"){
        $id = $_GET['id'];
        if(isset($id)){
            $stmt = $conn->prepare("UPDATE tasks SET task_deleted = 0 WHERE task_id = ?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $stmt->close();
            header("location:index.php");
        }
    }