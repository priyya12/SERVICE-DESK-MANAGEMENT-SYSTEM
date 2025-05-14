</head>
<body>
    <h2>Your Complaint History</h2>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Complaint</th>
                    <th>Status</th>
                    <th>Response</th>
                    <th>Resolution Date</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['complaint']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo $row['response'] ?: 'No response yet'; ?></td>
                        <td><?php echo $row['resolution_date'] ?: 'N/A'; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not submitted any complaints yet.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
