<?php
// Step 1: Set database connection parameters
$servername = "localhost";  // Update if needed
$username = "root";          // Update if needed
$password = "";              // Update if needed
$dbname = "route_db";        // Ensure this is the correct database name

// Step 2: Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Step 3: Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 4: Get the selected destination from the POST request
$destination = $_POST['destination'] ?? '';  // Ensure it exists

// Step 5: Validate the destination
if (empty($destination)) {
    die("Error: No destination specified.");
}

$destination = trim($destination); // Trim whitespace

// Step 6: Prepare a parameterized SQL query to fetch trips for the specified destination
$sql = "SELECT trip_number, trip_time FROM routes WHERE destination = ?";
$stmt = $conn->prepare($sql);

// Step 7: Check for preparation errors
if ($stmt === false) {
    die("Error preparing the query: " . $conn->error);
}

// Step 8: Bind the destination parameter and execute the query
$stmt->bind_param("s", $destination);
$stmt->execute();

// Step 9: Get the results of the query
$result = $stmt->get_result();

// Step 10: Display the trips for the specified destination
echo "<h2>Trips for destination: " . htmlspecialchars($destination) . "</h2>";

if ($result->num_rows > 0) {
    echo "<ul>";  // Start an unordered list
    while ($row = $result->fetch_assoc()) {
        echo "<li>Trip " . $row['trip_number'] . " at " . $row['trip_time'] . "</li>";  // Display each trip
    }
    echo "</ul>";  // End the unordered list
} else {
    echo "<p>No trips found for this destination.</p>";  // Message if no trips are found
}

// Step 11: Close the statement and the connection
$stmt->close();
$conn->close();
?>
