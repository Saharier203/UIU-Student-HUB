<?php
include('db_config.php');

// Fetch reservations
$sql = "SELECT * FROM RoomReservations ORDER BY ReservationDate ASC";
$result = $conn->query($sql);

// Handle new reservation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roomType = $_POST['roomType'];
    $roomNumber = $_POST['roomNumber'];
    $reservedBy = 1; // Placeholder: Use session-based user ID
    $reservationDate = $_POST['reservationDate'];

    $sql = "INSERT INTO RoomReservations (RoomType, RoomNumber, ReservedBy, ReservationDate) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $roomType, $roomNumber, $reservedBy, $reservationDate);

    if ($stmt->execute()) {
        header("Location: rooms.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Reservations</title>
</head>
<body>
    <h1>Room Reservations</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                Room Type: <?php echo $row['RoomType']; ?><br>
                Room Number: <?php echo $row['RoomNumber']; ?><br>
                Date: <?php echo $row['ReservationDate']; ?><br>
                Status: <?php echo $row['Status']; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Reserve a Room</h2>
    <form method="POST">
        <select name="roomType">
            <option value="Study">Study</option>
            <option value="Program">Program</option>
        </select>
        <input type="text" name="roomNumber" placeholder="Room Number" required>
        <input type="date" name="reservationDate" required>
        <button type="submit">Reserve Room</button>
    </form>
</body>
</html>
