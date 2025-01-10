<?php
include('db_config.php');

// Fetch pending complaints
$complaints = $conn->query("SELECT * FROM Complaints WHERE Status = 'Pending'");

// Fetch pending room reservations
$reservations = $conn->query("SELECT * FROM RoomReservations WHERE Status = 'Pending'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>

    <h2>Pending Complaints</h2>
    <ul>
        <?php while ($row = $complaints->fetch_assoc()): ?>
            <li>
                <strong><?php echo $row['Subject']; ?></strong><br>
                <?php echo $row['Description']; ?><br>
                <form method="POST" action="resolve_complaint.php">
                    <input type="hidden" name="complaintID" value="<?php echo $row['ComplaintID']; ?>">
                    <button type="submit">Resolve</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Pending Room Reservations</h2>
    <ul>
        <?php while ($row = $reservations->fetch_assoc()): ?>
            <li>
                Room Type: <?php echo $row['RoomType']; ?><br>
                Room Number: <?php echo $row['RoomNumber']; ?><br>
                Date: <?php echo $row['ReservationDate']; ?><br>
                <form method="POST" action="update_reservation.php">
                    <input type="hidden" name="reservationID" value="<?php echo $row['ReservationID']; ?>">
                    <select name="status">
                        <option value="Approved">Approve</option>
                        <option value="Rejected">Reject</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
