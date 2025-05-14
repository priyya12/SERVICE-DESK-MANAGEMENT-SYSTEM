<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'service_desk');

if ($_SESSION['role'] == 'client') {
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $complaint = $_POST['complaint'];
        $user_id = $_SESSION['user_id'];

        $query = $conn->prepare("INSERT INTO complaints (user_id, complaint) VALUES (?, ?)");
        $query->bind_param('is', $user_id, $complaint);
        $query->execute();
        echo "<p>Complaint submitted successfully!</p>";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Dashboard</title>
</head>
<body>
    <h2>Welcome, Client</h2>
    <form method="POST">
        <textarea name="complaint" placeholder="Describe your issue here..." required></textarea><br>
        <button type="submit">Submit Complaint</button>
    </form>
    <p><a href="complaint_history.php">View Complaint History</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

<?php
} elseif ($_SESSION['role'] == 'service_desk') {

    header("Location: service_dashboard.php");
    exit;
} else {
    
    echo "<p>Unauthorized access!</p>";
    echo "<p><a href='logout.php'>Logout</a></p>";
}
?>
