<?php
// Connect to the database
$host = 'localhost'; // The host, typically 'localhost' for XAMPP
$user = 'root'; // Default XAMPP user
$password = ''; // Default XAMPP password is usually empty
$dbname = 'bus_schedule';

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the selected destination from the form
$destination = $_POST['destination'];

// Query to get the bus schedules for the selected destination
$sql = "SELECT * FROM schedules WHERE destination = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $destination);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Schedule for <?php echo htmlspecialchars($destination); ?></title>
    <style>
        table {
            width: 100%; /* Full width */
            border-collapse: collapse; /* No space between borders */
        }

        th, td {
            border: 1px solid #ddd; /* Light border */
            padding: 8px; /* Padding for cells */
            text-align: center; /* Center text */
        }

        th {
            background-color: #f2f2f2; /* Light gray background */
            font-weight: bold; /* Bold font for headers */
        }
    </style>
</head>
<body>
    <h1>Bus Schedule for <?php echo htmlspecialchars($destination); ?></h1>
    <table>
        <tr>
            <th>Bus Number</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
        </tr>

        <?php
        // Loop through the result set and output each row in the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['bus_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['departure_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['arrival_time']) . "</td>";
            echo "</tr>";
        }
        ?>

    </table>

    <!-- Button to return to the selection form -->
    <form action="index.html" method="GET">
        
    </form>

</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
