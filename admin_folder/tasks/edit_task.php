<?php
session_start();
include '../../db_connect.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'];
    $task_description = $_POST['task_description'];

    $stmt = $conn->prepare("UPDATE tasks SET task_description = ? WHERE id = ?");
    $stmt->bind_param("si", $task_description, $task_id);
    $stmt->execute();

    header("Location: all_tasks.php"); // Redirect to all tasks page
    exit();
}

$task_id = $_GET['id'] ?? 0;
$task = $conn->query("SELECT * FROM tasks WHERE id = $task_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>
    <form action="edit_task.php" method="post">
        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
        <textarea name="task_description" required><?php echo htmlspecialchars($task['task_description']); ?></textarea><br>
        <input type="submit" value="Update Task">
    </form>
    <a href="all_tasks.php">Back to All Tasks</a>
</body>
</html>
