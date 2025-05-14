<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'service_desk') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'service_desk');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $response = $_POST['response'];
    $resolution_days = (int) $_POST['resolution_days'];

    
    $resolution_date = date('Y-m-d', strtotime("+$resolution_days days"));

   
    $query = $conn->prepare("UPDATE complaints SET status = 'resolved', response = ?, resolution_date = ? WHERE id = ?");
    $query->bind_param('ssi', $response, $resolution_date, $complaint_id);

    if ($query->execute()) {
        echo "<p>Complaint #$complaint_id has been updated successfully.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}


$query = $conn->prepare("SELECT c.id, u.username, c.complaint, c.status, c.response, c.resolution_date, c.created_at 
                         FROM complaints c
                         JOIN users u ON c.user_id = u.id
                         WHERE c.status = 'pending'
                         ORDER BY c.created_at DESC");
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Desk Dashboard</title>
</head>
<body>
    <h2>Welcome, Service Desk</h2>
    <h3>Pending Complaints</h3>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Client</th>
                    <th>Complaint</th>
                    <th>Status</th>
                    <th>Response</th>
                    <th>Resolution Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['complaint']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo $row['response'] ?: 'No response yet'; ?></td>
                        <td><?php echo $row['resolution_date'] ?: 'N/A'; ?></td>
                        <td>
                            <form method="POST">
                                <textarea name="response" placeholder="Write your response..." required></textarea><br>
                                <label>Resolution Days: <input type="number" name="resolution_days" min="1" required></label><br>
                                <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Respond</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending complaints.</p>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
