<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog-cms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_hotel'])) {
        $name = $_POST['hotel_name'];
        $location = $_POST['hotel_location'];
        
        $sql = "INSERT INTO hotels (name, location) VALUES ('$name', '$location')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New hotel added successfully<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    if (isset($_POST['add_room'])) {
        $hotel_id = $_POST['hotel_id'];
        $room_name = $_POST['room_name'];
        $room_price_per_night = $_POST['room_price_per_night'];
        
        $sql = "INSERT INTO hotel_rooms (hotel_id, room_name, room_price_per_night) VALUES ('$hotel_id', '$room_name', '$room_price_per_night')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New room added successfully<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    if (isset($_POST['add_booking'])) {
        $user_id = $_POST['user_id'];
        $booked_hotel_id = $_POST['booked_hotel_id'];
        $booked_room = $_POST['booked_room'];
        $total_nights = $_POST['total_nights'];
        
        // Fetch room price per night
        $room_price_query = "SELECT room_price_per_night FROM hotel_rooms WHERE id = $booked_room";
        $result = $conn->query($room_price_query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $room_price_per_night = $row['room_price_per_night'];
            $total_price = $room_price_per_night * $total_nights;
        } else {
            echo "Invalid room ID<br>";
            exit;
        }
        
        $sql = "INSERT INTO hotel_bookings (user_id, booked_hotel_id, booked_room, total_nights, total_price) 
                VALUES ('$user_id', '$booked_hotel_id', '$booked_room', '$total_nights', '$total_price')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New booking added successfully<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Data</title>
</head>
<body>
    <h2>Add New Hotel</h2>
    <form method="post" action="">
        Name: <input type="text" name="hotel_name" required><br>
        Location: <input type="text" name="hotel_location" required><br>
        <input type="submit" name="add_hotel" value="Add Hotel">
    </form>
    
    <h2>Add New Room</h2>
    <form method="post" action="">
        Hotel ID: <input type="number" name="hotel_id" required><br>
        Room Name: <input type="text" name="room_name" required><br>
        Room Price Per Night: <input type="number" step="0.01" name="room_price_per_night" required><br>
        <input type="submit" name="add_room" value="Add Room">
    </form>
    
    <h2>Add New Booking</h2>
    <form method="post" action="">
        User ID: <input type="number" name="user_id" required><br>
        Booked Hotel ID: <input type="number" name="booked_hotel_id" required><br>
        Booked Room ID: <input type="number" name="booked_room" required><br>
        Total Nights: <input type="number" name="total_nights" required><br>
        <input type="submit" name="add_booking" value="Add Booking">
    </form>
</body>
</html>
