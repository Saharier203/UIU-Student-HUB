<?php
include('db_config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaintID = $_POST['complaintID'];

    $sql = "UPDATE Complaints SET Status = 'Resolved' WHERE ComplaintID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $complaintID);

    if ($stmt->execute()) {
        header("Location: admin.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
