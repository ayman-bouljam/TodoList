<?php
    include "db.php";
    session_start();
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = $_POST["username"];
        $password = $_POST["password"];
        if(isset($username, $password) && !empty($username) && !empty($password)){
           try{
                $stmt = $conn->prepare("INSERT INTO users (username,password) VALUES (?,?)");
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bind_param("ss", $username, $hashedPassword);
                $stmt->execute();
                $stmt->close();
                header("location:index.php");
                exit;
           }catch(Exception $e){
                die("Error occured ".$e->getMessage());
           }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="pt-5">
    <div class="container w-25 mt-5">
        <h1 class="text-center">Register</h1>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-outline-info">Register</button>
        </form>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>