<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$name = $subject = $description = "";
$status = "Pending";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // $name = trim($_POST['name']);
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $userId = $_SESSION['user']['id'];

    if (empty($subject) || empty($description)) {
        $errors[] = "All fields are required.";
    }

    $imagePath = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . uniqid() . "_" . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            $errors[] = "Failed to upload image.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO complaints (subject, description, image, status, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $subject, $description, $imagePath, $status, $userId);
    
        if ($stmt->execute()) {
            $success = "Complaint registered successfully.";
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Failed to register complaint: " . $stmt->error;
        }
    
        $stmt->close();
    }
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Complaint</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include('includes/navbar.php'); ?>

<div class="container" style="max-width: 600px; margin: 2rem auto; background: #f9f9f9; padding: 2rem; border-radius: 10px;">
    <h2>Register a Complaint</h2>
    <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()" class="reg_form">
        <!-- <label for="name">name*</label>
        <input type="text" name="name" id="name" required>
        <br> -->
        <label for="subject">Subject*</label>
        <input type="text" name="subject" id="subject" required>
    <br>
        <label for="description">Description*</label>
        <textarea name="description" id="description" rows="5" required></textarea>
<br>
        <label for="image">Upload Image (optional)</label>
        <input type="file" name="image" accept="image/*">
<br><br>
        <button type="submit">Submit Complaint</button>
    </form>
</div>

<script>
function validateForm() {
    const subject = document.getElementById('subject').value.trim();
    const description = document.getElementById('description').value.trim();

    if (subject.length < 5) {
        alert("Subject must be at least 5 characters long.");
        return false;
    }

    if (description.length < 10) {
        alert("Description must be at least 10 characters long.");
        return false;
    }

    return true;
}
</script>

</body>
</html>

