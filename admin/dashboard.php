<?php
session_start();
include('../includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../includes/auth/login.php");
    exit;
}

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    if (isset($_POST['accept'])) {
        $resolution = $conn->real_escape_string($_POST['resolution']);
        $conn->query("UPDATE complaints SET status = 'In Progress', resolution_time = '$resolution' WHERE id = $id");
    } elseif (isset($_POST['reject'])) {
        $conn->query("UPDATE complaints SET status = 'Rejected', resolution_time = NULL WHERE id = $id");
    } elseif (isset($_POST['delete'])) {
        $conn->query("DELETE FROM complaints WHERE id = $id");
    } elseif (isset($_POST['update_resolution'])) {
        $resolution = $conn->real_escape_string($_POST['resolution']);
        $conn->query("UPDATE complaints SET resolution_time = '$resolution' WHERE id = $id");
    }
}

// Fetch all complaints
$result = $conn->query("SELECT complaints.*, users.name AS user_name FROM complaints JOIN users ON complaints.user_id = users.id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        form {
            margin: 5px 0;
        }
        input[type="text"] {
            padding: 4px;
            width: 120px;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
        button[name="reject"] {
            background-color: red;
            color: white;
        }
        button[name="delete"] {
            background-color: #333;
            color: white;
        }
        button[name="update_resolution"] {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
<?php include('../includes/navbar.php'); ?>

<div class="container">
    <h2>All Complaints</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Resolution Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Image">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="text" name="resolution" value="<?= htmlspecialchars($row['resolution_time']) ?>">
                                <button type="submit" name="update_resolution">Update</button>
                            </form>
                        </td>
                        <td>
                            <?php if (strtolower($row['status']) === 'pending'): ?>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="text" name="resolution" placeholder="e.g., 3 days" required>
                                    <button type="submit" name="accept">Accept</button>
                                </form>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="reject">Reject</button>
                                </form>
                            <?php else: ?>
                                <em>No actions</em>
                            <?php endif; ?>

                            <!-- Delete button for all complaints -->
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete" onclick="return confirm('Delete this complaint?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No complaints found.</p>
    <?php endif; ?>
</div>
</body>
</html>
