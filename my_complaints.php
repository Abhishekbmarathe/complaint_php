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

// Start the session and check for user login
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id']; // Get logged-in user's ID

// Fetch complaints for the logged-in user based on user_id
$sql = "SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Start the HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Complaints</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('includes/navbar.php'); ?> <!-- Include Navbar -->

    <div class="container">
        <h2>My Complaints</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="complaint-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="complaint-card">
                        <h3><?php echo htmlspecialchars($row['subject']); ?></h3>
                        <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
                        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

                        <?php if (!empty($row['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Complaint Image" style="max-width:100%; margin-top:10px;">
                        <?php endif; ?>

                        <?php if (!empty($row['resolution_time'])): ?>
                            <p><strong>Resolution Time:</strong> <?php echo htmlspecialchars($row['resolution_time']); ?></p>
                        <?php endif; ?>

                        <p><small><strong>Created At:</strong> <?php echo $row['created_at']; ?></small></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No complaints yet!</p>
        <?php endif; ?>

    </div> <!-- End of container -->

</body>
</html>

<?php
// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>
