<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Change if different in your environment
$password = ""; // Change if you have a password
$dbname = "login_db"; // Your database name

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine if it's a login or registration form
    if (isset($_POST['user_id']) && isset($_POST['password'])) {
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];

        // Check if it's a login or registration based on additional fields
        if (isset($_POST['email'])) {
            // Registration form
            $email = $_POST['email'];
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare SQL to insert new user
            $stmt = $conn->prepare("INSERT INTO users (user_id, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_id, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "Registration successful!";
                header("Location: login_page.php");
            } else {
                echo "Error during registration: " . $stmt->error;
            }

        } else {
            // Login form
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    echo "Login successful!";
                    header("Location:stylesheet.html");
                } else {
                    echo "Incorrect password!";
                }
            } else {
                echo "User not found!";
            }
        }

        // Close the prepared statement
        $stmt->close();

    } else {
        echo "Required data missing!";
    }
}

// Close the database connection
$conn->close();
?>
