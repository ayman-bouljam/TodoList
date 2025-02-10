<?php
    include "db.php";
    session_start();

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try{
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? ");
            $stmt->bind_param('s',$username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($row = $result->fetch_assoc()){
                if(password_verify($password, $row['password'])){
                    $_SESSION['username'] = $row['username'];
                    header("location:index.php");
                    exit;
                }else{
                    echo "<p class='text-center text-danger'>Username or Password is not correct </p>";
                }
            }
            $stmt->close();
        }catch(Exception $e){
            die('Login Failed '.$e->getMessage());
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
        <h1 class="text-center">Login</h1>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-outline-info">Login</button>
        </form>
        <div class="text-centr mt-3">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>