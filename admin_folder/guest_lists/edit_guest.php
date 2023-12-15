<?php
session_start();
include '../../db_connect.php'; // Adjust the path as needed

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guest_id = $_POST['guest_id'];
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $rsvp = $_POST['rsvp'];

    $stmt = $conn->prepare("UPDATE guests SET event_id = ?, name = ?, email = ?, phone = ?, rsvp = ? WHERE id = ?");
    $stmt->bind_param("issssi", $event_id, $name, $email, $phone, $rsvp, $guest_id);
    $stmt->execute();

    header("Location: all_guestlists.php"); // Redirect to all_guestlists page
    exit();
}

$guest_id = $_GET['id'] ?? 0;
$guest = $conn->query("SELECT * FROM guests WHERE id = $guest_id")->fetch_assoc();
$events = $conn->query("SELECT id, title FROM events");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Guest</title>
</head>
<body>
    <h1>Edit Guest</h1>
    <form action="edit_guest.php" method="post">
        <input type="hidden" name="guest_id" value="<?php echo $guest_id; ?>">
        <select name="event_id">
            <?php while ($event = $events->fetch_assoc()): ?>
                <option value="<?php echo $event['id']; ?>" <?php echo $event['id'] == $guest['event_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($event['title']); ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        <input type="text" name="name" placeholder="Name" required value="<?php echo htmlspecialchars($guest['name']); ?>"><br>
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($guest['email']); ?>"><br>
        <input type="text" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($guest['phone']); ?>"><br>
        <select name="rsvp">
            <option value="Pending" <?php echo $guest['rsvp'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Confirmed" <?php echo $guest['rsvp'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
            <option value="Declined" <?php echo $guest['rsvp'] == 'Declined' ? 'selected' : ''; ?>>Declined</option>
        </select><br>
        <input type="submit" value="Update Guest">
    </form>
    <a href="all_guestlists.php">Back to Guest Lists</a>
</body>
</html>
