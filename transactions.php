<?php
session_start();
include('Database.php'); // Database connection

// Handle Book Issuing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['issue_book'])) {
    $member_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $issue_date = date('Y-m-d'); // Current date
    $due_date = date('Y-m-d', strtotime('+7 days')); // Due in 7 days

    // Check if book is available
    $checkQuery = "SELECT available_copies FROM books WHERE book_id = $book_id";
    $result = mysqli_query($conn, $checkQuery);
    $book = mysqli_fetch_assoc($result);

    if ($book && $book['available_copies'] > 0) {
        // Insert transaction record
        $insertQuery = "INSERT INTO transactions (student_id, book_id, issue_date, due_date, status) 
                        VALUES ('$member_id', '$book_id', '$issue_date', '$due_date', 'issued')";
        if (mysqli_query($conn, $insertQuery)) {
            // Update book availability
            $updateQuery = "UPDATE books SET available_copies = available_copies - 1 WHERE book_id = $book_id";
            mysqli_query($conn, $updateQuery);
            header("Location: transactions.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Book is not available!";
    }
}

// Handle Book Return
if (isset($_GET['return_id'])) {
    $transaction_id = $_GET['return_id'];

    // Fetch book ID from transaction
    $fetchQuery = "SELECT book_id FROM transactions WHERE transaction_id = $transaction_id";
    $result = mysqli_query($conn, $fetchQuery);
    $transaction = mysqli_fetch_assoc($result);

    if ($transaction) {
        $book_id = $transaction['book_id'];

        // Update transaction status
        $updateTransaction = "UPDATE transactions SET status='returned', return_date=NOW() WHERE transaction_id = $transaction_id";
        if (mysqli_query($conn, $updateTransaction)) {
            // Increase book availability
            $updateBook = "UPDATE books SET available_copies = available_copies + 1 WHERE book_id = $book_id";
            mysqli_query($conn, $updateBook);
            header("Location: transactions.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch Transactions
$transactionsQuery = "SELECT transactions.*, students_login.student_name, books.title FROM transactions 
                      JOIN students_login ON transactions.student_id = students_login.student_id 
                      JOIN books ON transactions.book_id = books.book_id 
                      ORDER BY transactions.issue_date DESC";
$transactionsResult = mysqli_query($conn, $transactionsQuery);

// Fetch Books & Members for issuing
$booksQuery = "SELECT * FROM books WHERE available_copies > 0";
$booksResult = mysqli_query($conn, $booksQuery);

$membersQuery = "SELECT * FROM students_login";
$membersResult = mysqli_query($conn, $membersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Transactions</title>
    <link rel="stylesheet" href="../frontend/css/dashboard_styles.css">
</head>
<body>
    <header class="header">
        <h1>Library Management System - Transactions</h1>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Library System</h2>
            <ul>
                <li><a href="../backend/dashboard.php">Dashboard</a></li>
                <li><a href="../backend/manage_books.php">Manage Books</a></li>
                <li><a href="../backend/members.php">Manage Members</a></li>
                <li><a href="transactions.php">Transactions</a></li>
                <li><a href="../backend/about.php">About</a></li>
                <li><a href="../backend/contact.php">Contact</a></li>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1>Issue a Book</h1>
            <form method="POST" action="transactions.php">
                <label for="student_id">Select Member:</label>
                <select name="student_id" required>
                    <?php while ($member = mysqli_fetch_assoc($membersResult)) { ?>
                        <option value="<?php echo $member['student_id']; ?>"><?php echo $member['student_name']; ?></option>
                    <?php } ?>
                </select>

                <label for="book_id">Select Book:</label>
                <select name="book_id" required>
                    <?php while ($book = mysqli_fetch_assoc($booksResult)) { ?>
                        <option value="<?php echo $book['book_id']; ?>"><?php echo $book['title']; ?> (Available: <?php echo $book['available_copies']; ?>)</option>
                    <?php } ?>
                </select>

                <button type="submit" name="issue_book">Issue Book</button>
            </form>

            <h2>Transaction History</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Book</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction = mysqli_fetch_assoc($transactionsResult)) { ?>
                        <tr>
                            <td><?php echo $transaction['transaction_id']; ?></td>
                            <td><?php echo $transaction['student_name']; ?></td>
                            <td><?php echo $transaction['title']; ?></td>
                            <td><?php echo $transaction['issue_date']; ?></td>
                            <td><?php echo $transaction['due_date']; ?></td>
                            <td><?php echo $transaction['return_date'] ? $transaction['return_date'] : 'N/A'; ?></td>
                            <td><?php echo ucfirst($transaction['status']); ?></td>
                            <td>
                                <?php if ($transaction['status'] == 'issued') { ?>
                                    <a href="transactions.php?return_id=<?php echo $transaction['transaction_id']; ?>" onclick="return confirm('Mark as returned?')">Return</a>
                                <?php } else { ?>
                                    Returned
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Library Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
