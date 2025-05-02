<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Complaint Portal</title>
    <link rel="stylesheet" href="css/style.css" />

</head>

<body>

    <!-- Navbar -->
    <!-- <nav class="navbar">
        <div class="logo">Complaint Portal</div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">My Complaints</a></li>
            <li><button class="btn-login">Login</button></li>
            <li><button class="btn-signup">Sign Up</button></li>
        </ul>
    </nav> -->
    <?php include('includes/navbar.php'); ?>

    <!-- Hero Section -->
    <header class="hero">
        <h1>Welcome to the Complaint Portal</h1>
        <p>Submit your issues and track their resolution.</p>
    </header>

    <!-- Complaints Section -->
    <section class="complaints">
        <h2>Recent Complaints</h2>
        <div class="complaint-list">
            <?php include 'fetch_complaints.php'; ?>
        </div>

    </section>

    <script>
        // Fetch complaints from backend
        fetch("fetch_complaints.php")
            .then((res) => res.json())
            .then((data) => {
                const list = document.getElementById("complaintList");
                if (data.length === 0) {
                    list.innerHTML = "<p>No complaints found.</p>";
                    return;
                }
                data.forEach(c => {
                    const card = document.createElement("div");
                    card.className = "complaint-card";
                    card.innerHTML = `
            <h3>${c.subject}</h3>
            <p><strong>By:</strong> ${c.name}</p>
            <p>${c.description}</p>
            <p><strong>Status:</strong> ${c.status}</p>
            ${c.resolve_time ? `<p><strong>Resolve Time:</strong> ${c.resolve_time}</p>` : ""}
          `;
                    list.appendChild(card);
                });
            });
    </script>

</body>

</html>