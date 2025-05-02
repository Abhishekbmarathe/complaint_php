<?php
// Check if the session is already started before calling session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav style="background: #007bff; padding: 1rem; display: flex; justify-content: space-between; color: white;">
    <div><a href="index.php" style="color: white; text-decoration: none; font-weight: bold;">Complaint Portal</a></div>
    <div>
        <?php if (isset($_SESSION['user'])): ?>
            <!-- For regular users -->
            Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?> |
            <a href="my_complaints.php" style="color: white;">My Complaints</a> |
            <a href="register.php" style="color: white;">Register Complaint</a> |
            <a href="includes/auth/logout.php" style="color: white;">Logout</a>

        <?php elseif (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <!-- For admins -->
            Welcome, Admin |
            <a href="includes/admin/dashboard.php" style="color: white;">Admin Panel</a> |
            <a href="includes/auth/logout.php" style="color: white;">Logout</a>

        <?php else: ?>
            <!-- For guests (not logged in) -->
            <a href="includes/auth/login.php" style="color: white;">Login</a> |
            <a href="includes/auth/signup.php" style="color: white;">Signup</a>
        <?php endif; ?>
    </div>
</nav>
