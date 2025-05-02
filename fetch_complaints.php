<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "complaint_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch complaints
$sql = "SELECT * FROM complaints ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="complaint-card">';
        echo '<h3>' . htmlspecialchars($row['subject']) . '</h3>';
        echo '<p><strong>Status:</strong> ' . ucfirst($row['status']) . '</p>';
        // echo '<p><strong>By:</strong> ' . htmlspecialchars($row['name']) . '</p>';
        // echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
        echo '<p><strong>Description:</strong> ' . nl2br(htmlspecialchars($row['description'])) . '</p>';
        if (!empty($row['image'])) {
            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Complaint Image" style="max-width:100%; margin-top:10px;">';

        }
        if (!empty($row['resolution_time'])) {
            echo '<p><strong>Resolution Time:</strong> ' . htmlspecialchars($row['resolution_time']) . '</p>';
        }
        echo '<p><small><strong>Created At:</strong> ' . $row['created_at'] . '</small></p>';
        echo '</div>';
    }
} else {
    echo "<p style='text-align:center;'>No complaints yet!</p>";
}

$conn->close();
?>
