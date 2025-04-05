<?php
include('Database.php'); // Include database connection

// Fetch members from database
$membersQuery = "SELECT * FROM students_login";
$membersResult = $conn->query($membersQuery);

// Add Member with Prepared Statements
if (isset($_POST['add_member'])) {
    $student_name = $_POST['student_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $contact_number = $_POST['contact_number'] ?? null;

    // Provide a default password
    $defaultPassword = password_hash("default123", PASSWORD_DEFAULT);

    if ($username && $email && $contact_number) {
        $stmt = $conn->prepare("INSERT INTO students_login (student_name, email, contact_number, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $contact_number, $defaultPassword);

        if ($stmt->execute()) {
            header("Location: members.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } 
}

// Delete Member with Safety Check
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Prevent deletion of super admin (optional check)
    if ($id > 1) { 
        $stmt = $conn->prepare("DELETE FROM students_login WHERE student_id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: members.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Cannot delete main admin!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
    <link rel="stylesheet" href="../frontend/css/dashboard_styles.css">
</head>
<body>
    <header class="header">
        <h1>Library Management System - Members</h1>
    </header>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Library System</h2>
            <ul>
                <li><a href="../backend/dashboard.php">Dashboard</a></li>
                <li><a href="../backend/manage_books.php">Manage Books</a></li>
                <li><a href="members.php">Manage Members</a></li>
                <li><a href="../backend/transactions.php">Transactions</a></li>
                <li><a href="../backend/about.php">About</a></li>
                <li><a href="../backend/contact.php">Contact</a></li>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <h1>Manage Members</h1>

            <!-- Add Member Form -->
            <form method="POST" action="members.php">
                <input type="text" name="student_name" placeholder="Student Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="contact_number" placeholder="Phone" required>
                <button type="submit" name="add_member">Add Member</button>
            </form>

            <!-- Display Members -->
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $membersResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                    <td>
                        <a href="members.php?delete=<?php echo $row['student_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
    <footer class="footer">
        <p>&copy; 2025 Library Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
