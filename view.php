<?php
include 'config.php';

$result = $conn->query("SELECT * FROM complaints ORDER BY id DESC");

echo "<h2>All Complaints</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Name</th><th>Subject</th><th>Description</th><th>Image</th><th>Delete</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['subject']}</td>";
    echo "<td>{$row['description']}</td>";
    echo "<td>";
    if ($row['image']) {
        echo "<img src='uploads/{$row['image']}' width='100'/>";
    }
    echo "</td>";
    echo "<td><a href='delete.php?id={$row['id']}'>Delete</a></td>";
    echo "</tr>";
}

echo "</table>";

$conn->close();
?>
