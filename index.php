<?php
include "db.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $taskName = $_POST['taskName'] ?? '';
    if (!empty($taskName)) {
        try {
            $stmt = $conn->prepare('INSERT INTO tasks (task_name) VALUES (?)');
            
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param('s', $taskName);
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } 
}

$open_tasks = $conn->query("SELECT * FROM tasks Where task_completed= 0 AND task_inprogress = 0 AND task_deleted = 0");
$inprogress_tasks = $conn->query("SELECT * FROM tasks Where task_completed= 0 AND task_inprogress = 1 AND task_deleted = 0" );
$closed_tasks = $conn->query("SELECT * FROM tasks Where task_completed= 1 AND task_inprogress = 0 AND task_deleted = 0");
$deleted_tasks = $conn->query("SELECT * FROM tasks Where task_deleted = 1");

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}else{
    header("location:login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Todo List</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log out</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <h1 class="text-center mt-3">To Do list by Aymane</h1>
  <div class="container mt-5 p-4 bg-light text-center">
        <?php echo "<h3>Welcome $username</h3>" ?>
    <form action="index.php" method="POST"  class="my-4">
        <div class="input-group w-50 mx-auto">
            <input type="text" name="taskName" class="form-control d-block" placeholder="New Task ..." require />
            <button type="submit" class="btn btn-outline-dark">Add</button>
        </div>
    </form>
    <div class="row pt-4">
        <div class="col-md-6">
            <h2 class="text-center">Open Tasks</h2>
            <ul class="list-group">
                <?php if($open_tasks->num_rows > 0) : ?>
                    <?php while($row = $open_tasks->fetch_assoc()) :?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $row['task_name'] ?>
                    <div>
                        <a href="in_progress_task.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-primary">In Progress</a>
                        <a href="completeTask.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-success">Complete</a>
                        <a href="deleteTask.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Do you want to delete this task ?');">delete</a>
                    </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <li class="list-group-item">No open tasks found.</li>
                <?php endif ?>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <h2 class="text-center">In Progress Tasks</h2>
            <ul class="list-group">
                <?php if($inprogress_tasks->num_rows > 0) : ?>
                    <?php while($row = $inprogress_tasks->fetch_assoc()) :?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $row['task_name'] ?>
                    <div>
                        <a href="completeTask.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-success">Complete</a>
                        <a href="deleteTask.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Do you want to delete this task ?');">delete</a>
                    </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <li class="list-group-item">No open tasks found.</li>
                <?php endif ?>
                </li>
            </ul>
        </div>
        <div class="col-md-6 mt-5">
            <h2 class="text-center">Closed Tasks</h2>
            <ul class="list-group">
            <?php if($closed_tasks->num_rows > 0) : ?>
                <?php while($row = $closed_tasks->fetch_assoc()) :?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $row['task_name'] ?>
                    <div>
                        <a href="deleteTask.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-warning" onclick="return confirm('Do you want to delete this task ?');" >Delete</a>
                    </div>
                </li>
                <?php endwhile ?>
            <?php else : ?>
                <li class="list-group-item">No CLosed Tasks found.</li>
            <?php endif ?>
            </ul>
        </div>
        <div class="col-md-6 mt-5">
            <h2 class="text-center">Deleted Tasks</h2>
            <ul class="list-group">
            <?php if($deleted_tasks->num_rows > 0) : ?>
                <?php while($row = $deleted_tasks->fetch_assoc()) :?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $row['task_name'] ?>
                    <div>
                        <a href="recoverTask.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-warning" onclick="return confirm('Do you want to recover this task ?');" >Cancel</a>
                    </div>
                </li>
                <?php endwhile ?>
            <?php else : ?>
                <li class="list-group-item">No CLosed Tasks found.</li>
            <?php endif ?>
            </ul>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>