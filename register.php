<?php
$servername = "localhost";
$username = "root"; // Default user in XAMPP
$password = ""; // Default password in XAMPP
$dbname = "login_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$user_id = $_POST['user_id'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

// Insert data into the database
$sql = "INSERT INTO users (user_id, email, password) VALUES ('$user_id', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "User registered successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
