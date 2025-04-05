<?php
$servername = "localhost";
$username = "root";
$password = "Kajal@sql123";
$db = "lib";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

  /* // Retrieve form data
     $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Sanitize data (example)
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

   // Build the SQL query
    $sql = "INSERT INTO students_login(student_name, email, password_hash) 
    VALUES ('$name', '$email', '$password')";
    

       // Execute the query
   if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
  } else {
  echo "Error: " . $sql . "<br>" . $conn->error;
  }

//fetch database
/*$sql = "SELECT admin_id, username, email FROM admin_login";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["admin_id"]. " - Name: " . $row["username"]. "- Email: " . $row["email"]. "<br>";
  }
} else {
  echo "0 results";
}*/
?>
