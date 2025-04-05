<?php
session_start();
include('Database.php'); // Database Connection Include

// Handle Book Deletion (using prepared statements)
if (isset($_GET['delete_id'])) {
    $book_id = intval($_GET['delete_id']); // Convert to integer to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_books.php");
    exit();
}

// Handle Book Addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $total_copies = isset($_POST['total_copies']) ? intval($_POST['total_copies']) : 1;
    $available_copies = $total_copies; // New books have all copies available

    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, total_copies, available_copies) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $title, $author, $isbn, $total_copies, $available_copies);
    
    if ($stmt->execute()) {
        header("Location: manage_books.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}


// Fetch All Books
$booksQuery = "SELECT * FROM books";
$booksResult = mysqli_query($conn, $booksQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link rel="stylesheet" href="../frontend/css/dashboard_styles.css">
</head>
<body>
    <header class="header">
        <h1>Library Management System - Manage Books</h1>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Library System</h2>
            <ul>
                <li><a href="../backend/dashboard.php">Dashboard</a></li>
                <li><a href="manage_books.php">Manage Books</a></li>
                <li><a href="../backend/members.php">Manage Members</a></li>
                <li><a href="../backend/transactions.php">Transactions</a></li>
                <li><a href="../backend/about.php">About</a></li>
                <li><a href="../backend/contact.php">Contact</a></li>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1>Books List</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Total Copies</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($book = mysqli_fetch_assoc($booksResult)) { ?>
                        <tr>
                            <td><?php echo $book['book_id']; ?></td>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td><?php echo $book['isbn']; ?></td>
                            <td><?php echo $book['total_copies']; ?></td>
                            <td>
                                <a href="manage_books.php?delete_id=<?php echo $book['book_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2>Add New Book</h2>
            <form method="POST" action="manage_books.php">
                <label for="title">Title:</label>
                <input type="text" name="title" required>
                
                <label for="author">Author:</label>
                <input type="text" name="author" required>
                
                <label for="isbn">ISBN:</label>
                <input type="text" name="isbn" required>

                <label for="total_copies">Total Copies:</label>
                <input type="number" name="total_copies" min="1" required>
                
                <button type="submit" name="add_book">Add Book</button>
            </form>
        </main>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Library Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
