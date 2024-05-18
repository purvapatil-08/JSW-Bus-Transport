<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_db"; // Adjust to your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form data is received via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate data
    if (!isset($_POST['full_name']) || !isset($_POST['email']) || !isset($_POST['message'])) {
        die("Required data missing!");
    }

    // Sanitize and validate data
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($full_name) || empty($email) || empty($message)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Prepare the SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO contact_messages (full_name, email, message) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $full_name, $email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Error sending message: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
