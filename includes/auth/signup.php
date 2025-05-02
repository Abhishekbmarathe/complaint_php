<?php
session_start();
include('../../includes/db.php');


$name = $email = $password = $confirm_password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validations
    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";

    // If no errors, insert user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['user'] = ['name' => $name, 'email' => $email];
            header('Location: login.php');
            exit;
        } else {
            $errors[] = "Email already exists or error occurred";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <?php include('../../includes/navbar.php'); ?>
    <div class="form-container">
        <h2>Signup</h2>
        <?php if (!empty($errors)): ?>
            <div class="errors"><?php echo implode('<br>', $errors); ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name) ?>" required><br>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
