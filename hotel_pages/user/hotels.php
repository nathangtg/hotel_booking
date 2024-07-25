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

// Handle booking submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_room'])) {
    $user_id = $_POST['user_id'];
    $hotel_id = $_POST['hotel_id'];
    $room_id = $_POST['room_id'];
    $total_nights = $_POST['total_nights'];

    // Fetch room price per night
    $room_price_query = "SELECT room_price_per_night FROM hotel_rooms WHERE id = $room_id";
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
            VALUES ('$user_id', '$hotel_id', '$room_id', '$total_nights', '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo "New booking added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch and display hotel details if id is set
if (isset($_GET['id'])) {
    $hotel_id = $_GET['id'];
    $hotel_query = "SELECT * FROM hotels WHERE id = $hotel_id";
    $hotel_result = $conn->query($hotel_query);

    if ($hotel_result->num_rows > 0) {
        $hotel = $hotel_result->fetch_assoc();
        echo "<h1>Hotel: " . $hotel['name'] . "</h1>";
        echo "<p>Location: " . $hotel['location'] . "</p>";

        // Fetch and display rooms for the hotel
        $rooms_query = "SELECT * FROM hotel_rooms WHERE hotel_id = $hotel_id";
        $rooms_result = $conn->query($rooms_query);

        if ($rooms_result->num_rows > 0) {
            echo "<h2>Rooms</h2>";
            echo "<ul>";
            while ($room = $rooms_result->fetch_assoc()) {
                echo "<li>Room Name: " . $room['room_name'] . ", Price: " . $room['room_price_per_night'] . "</li>";
            }
            echo "</ul>";

            echo "<h2>Book a Room</h2>";
            ?>
            <form method="post" action="">
                User ID: <input type="number" name="user_id" required><br>
                Room: 
                <select name="room_id" id="room_id" required>
                    <option value="">Select a room</option>
                    <?php
                    $rooms_result->data_seek(0); // Reset result pointer
                    while ($room = $rooms_result->fetch_assoc()) {
                        echo "<option value='" . $room['id'] . "' data-price='" . $room['room_price_per_night'] . "'>" . $room['room_name'] . " - " . $room['room_price_per_night'] . "</option>";
                    }
                    ?>
                </select><br>
                Total Nights: <input type="number" name="total_nights" id="total_nights" required><br>
                Total Price: <span id="total_price">0</span><br>
                <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
                <input type="submit" name="book_room" value="Book Room">
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    const roomSelect = document.getElementById('room_id');
                    const nightsInput = document.getElementById('total_nights');
                    const totalPriceSpan = document.getElementById('total_price');

                    function calculateTotalPrice() {
                        const selectedRoom = roomSelect.options[roomSelect.selectedIndex];
                        const pricePerNight = selectedRoom.getAttribute('data-price');
                        const totalNights = nightsInput.value;

                        if (pricePerNight && totalNights) {
                            const totalPrice = pricePerNight * totalNights;
                            totalPriceSpan.innerText = totalPrice;
                        } else {
                            totalPriceSpan.innerText = 0;
                        }
                    }

                    roomSelect.addEventListener('change', calculateTotalPrice);
                    nightsInput.addEventListener('input', calculateTotalPrice);
                });
            </script>
            <?php
        } else {
            echo "<p>No rooms available for this hotel.</p>";
        }
    } else {
        echo "<p>Hotel not found.</p>";
    }
} else {
    // Fetch and display all hotels
    $hotels_query = "SELECT * FROM hotels";
    $hotels_result = $conn->query($hotels_query);

    if ($hotels_result->num_rows > 0) {
        echo "<h1>Hotels</h1>";
        echo "<ul>";
        while ($hotel = $hotels_result->fetch_assoc()) {
            echo "<li><a href='hotels.php?id=" . $hotel['id'] . "'>" . $hotel['name'] . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hotels found.</p>";
    }
}

$conn->close();
?>
