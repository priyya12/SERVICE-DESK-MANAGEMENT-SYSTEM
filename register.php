<?php
$conn = new mysqli('localhost', 'root', '', 'service_desk');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? 'client'; 

    $query = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $query->bind_param('sss', $username, $password, $role);

    if ($query->execute()) {
        echo "<p>Registration successful! <a href='login.php'>Go to Login</a></p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Role:
            <select name="role">
                <option value="client">Client</option>
                <option value="service_desk">Service Desk</option>
            </select>
        </label><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
