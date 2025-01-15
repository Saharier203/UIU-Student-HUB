<?php
include('db_config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservationID = $_POST['reservationID'];
    $status = $_POST['status'];

    $sql = "UPDATE RoomReservations SET Status = ? WHERE ReservationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $reservationID);

    if ($stmt->execute()) {
        header("Location: admin.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
