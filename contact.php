<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Library Management System</title>
    <link rel="stylesheet" href="../frontend/css/dashboard_styles.css">
    <style>
        .contact-form {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #ccc;
        }
        .contact-form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        .contact-form button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #2e8b57;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .contact-form button:hover {
            background-color: #256d45;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Library Management System</h1>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Library System</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="../backend/manage_books.php">Manage Books</a></li>
                <li><a href="../backend/members.php">Manage Members</a></li>
                <li><a href="../backend/transactions.php">Transactions</a></li>
                <li><a href="../backend/about.php">About</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1>Contact Us</h1>
            <p>If you have any queries or feedback, feel free to contact us using the form below.</p>

            <div class="contact-form">
                <form action="login_handler.php" method="post">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit">Send Message</button>
                </form>
            </div>

            <h2>Contact Info</h2>
            <p>Email: support@librarysystem.com</p>
            <p>Phone: +91 98765 43210</p>
            <p>Address: ABC Library, Sector 10, Indira Nagar, Lucknow </p>
        </main>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>
