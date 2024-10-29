<?php

include 'koneksi.php'; // Include your database connection

// Assuming you get the vehicle ID or parking ID from the request
$parkirId = $_POST['id']; // This should be passed from the frontend
$exitTime = date('Y-m-d H:i:s'); // Get the current exit time

// First, fetch the entry time for the specified parking ID
$stmt = $koneksi->prepare("SELECT waktu_masuk FROM parkir WHERE id = ?");
$stmt->bind_param("i", $parkirId);
$stmt->execute();
$stmt->bind_result($waktu_masuk);
$stmt->fetch();
$stmt->close();

// Calculate duration and total fee (for example: $5 per hour)
$entryTime = new DateTime($waktu_masuk);
$exitTimeObj = new DateTime($exitTime);
$duration = $entryTime->diff($exitTimeObj); // Duration between entry and exit

// Assuming a rate of $5 per hour
$ratePerHour = 5;
$totalBiaya = ceil($duration->h + ($duration->i / 60)) * $ratePerHour; // Round up to the next hour

// Update the parking record with exit time and total fee
$stmt = $koneksi->prepare("UPDATE parkir SET waktu_keluar = ?, total_biaya = ? WHERE id = ?");
$stmt->bind_param("sdi", $exitTime, $totalBiaya, $parkirId);
if ($stmt->execute()) {
    // Successful update
    echo json_encode(['success' => true, 'total_biaya' => $totalBiaya]);
} else {
    // Handle error if needed
    error_log("Error updating data: " . $stmt->error);
    echo json_encode(['success' => false, 'error' => 'Update failed.']);
}
$stmt->close();

// Close the database connection
$koneksi->close();

?>
