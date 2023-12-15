<?php
session_start();
include '../../db_connect.php'; // Adjust the path as needed

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../../login.php");
    exit();
}

$guests = $conn->query("SELECT guests.*, events.title FROM guests JOIN events ON guests.event_id = events.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Guest Lists</title>
</head>
<body>
    <h1>All Guest Lists</h1>
    <a href="create_guest.php">Create New Guest List</a>
    <?php while ($guest = $guests->fetch_assoc()): ?>
        <div>
            <p>Event: <?php echo htmlspecialchars($guest['title']); ?></p>
            <p>Name: <?php echo htmlspecialchars($guest['name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($guest['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($guest['phone']); ?></p>
            <p>RSVP: <?php echo htmlspecialchars($guest['rsvp']); ?></p>
            - <a href="edit_guest.php?id=<?php echo $guest['id']; ?>">Edit</a> <!-- Link to edit user -->
            - <a href="delete_guest.php?id=<?php echo $guest['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
        </div>
    <?php endwhile; ?>
    <a href="../../dashboard.php">Back to Dashboard</a>
</body>
</html>
