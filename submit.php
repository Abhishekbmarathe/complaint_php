<?php
include 'config.php';

$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$description = $_POST['description'];
$image = '';

if ($_FILES['image']['name']) {
    $targetDir = "uploads/";
    $image = basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $image);
}

$sql = "INSERT INTO complaints (name, email, subject, description, image)
        VALUES ('$name', '$email', '$subject', '$description', '$image')";

if ($conn->query($sql) === TRUE) {
    echo "Complaint submitted successfully. <a href='index.html'>Go back</a>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
