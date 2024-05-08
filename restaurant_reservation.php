<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$mysqli = new mysqli("localhost", "username", "password", "restaurant_reservation");

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch user_id based on email
    $user_email = $_SESSION['email'];
    $user_query = $mysqli->query("SELECT id FROM users WHERE email = '$user_email'");
    $user_row = $user_query->fetch_assoc();
    $user_id = $user_row['id'];

    $first_name = $mysqli->real_escape_string($_POST['first_name']);
    $last_name = $mysqli->real_escape_string($_POST['last_name']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $date = $mysqli->real_escape_string($_POST['date']);
    $time = $mysqli->real_escape_string($_POST['time']);
    $party_size = $mysqli->real_escape_string($_POST['party_size']);
    $location = $mysqli->real_escape_string($_POST['location']);

    // Insert reservation into database
    $insert_query = "INSERT INTO reservations (user_id, first_name, last_name, phone, date, time, party_size, location) 
                     VALUES ('$user_id', '$first_name', '$last_name', '$phone', '$date', '$time', '$party_size', '$location')";
    if ($mysqli->query($insert_query) === TRUE) {
        echo "Reservation successful!";
    } else {
        echo "Error: " . $insert_query . "<br>" . $mysqli->error;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Reservation</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #ff9900;">
    <div class="container">
        <h2 style="color: #333;">Restaurant Reservation</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="tel" name="phone" placeholder="Phone" required>
            <input type="date" name="date" required>
            <input type="time" name="time" required>
            <input type="number" name="party_size" placeholder="Number of Persons" min="1" required>
            <label for="location" style="color: #333;">Location:</label>
            <select name="location" required>
                <option value="indoor">Indoor</option>
                <option value="outdoor">Outdoor</option>
            </select>
            <input type="submit" value="Make Reservation" style="background-color: #4CAF50;">
        </form>
    </div>
</body>
</html>
