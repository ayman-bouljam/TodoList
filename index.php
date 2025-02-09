<?php
include "db.php";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $taskName = $_POST['taskName'] ?? '';
    if (!empty($taskName)) {
        try {
            $stmt = $conn->prepare('INSERT INTO tasks (task_name, created_at) VALUES (?, CURRENT_TIMESTAMP)');
            
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

$open_tasks = $conn->query("SELECT * FROM tasks Where task_compeleted= 0");
$closed_tasks = $conn->query("SELECT * FROM tasks Where task_compeleted= 1");


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
  <div class="container mt-5 p-4 bg-light">
        <h1 class="text-center">To Do list by Aymane</h1>
    <form action="index.php" method="POST"  class="mb-4">
        <div class="input-group w-50 mx-auto">
            <input type="text" name="taskName" class="form-control d-block" placeholder="New Task ..." require />
            <button type="submit" class="btn btn-outline-dark">Add</button>
        </div>
    </form>
    <div class="row">
        <div class="col-md-6">
            <h2 class="text-center">Open Tasks</h2>
            <ul class="list-group">
                <?php if($open_tasks->num_rows > 0) : ?>
                    <?php while($row = $open_tasks->fetch_assoc()) :?>
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
        <div class="col-md-6">
            <h2 class="text-center">Closed Tasks</h2>
            <ul class="list-group">
            <?php if($closed_tasks->num_rows > 0) : ?>
                <?php while($row = $closed_tasks->fetch_assoc()) :?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $row['task_name'] ?>
                    <div>
                        <a href="deleteTask.php?id=<?= $row['task_id']; ?>" class="btn btn-outline-warning" >Delete</a>
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