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

// SQL to create hotels table
$sql = "CREATE TABLE hotels (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table hotels created successfully\n";
} else {
    echo "Error creating table hotels: " . $conn->error . "\n";
}

// SQL to create hotel_rooms table
$sql = "CREATE TABLE hotel_rooms (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT(6) UNSIGNED NOT NULL,
    room_name VARCHAR(255) NOT NULL,
    room_price_per_night DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table hotel_rooms created successfully\n";
} else {
    echo "Error creating table hotel_rooms: " . $conn->error . "\n";
}

// SQL to create hotel_bookings table
$sql = "CREATE TABLE hotel_bookings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    booked_hotel_id INT(6) UNSIGNED NOT NULL,
    booked_room INT(6) UNSIGNED NOT NULL,
    total_nights INT(3) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booked_hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (booked_room) REFERENCES hotel_rooms(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table hotel_bookings created successfully\n";
} else {
    echo "Error creating table hotel_bookings: " . $conn->error . "\n";
}

$conn->close();
?>
