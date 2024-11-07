<?php

include 'koneksi.php'; // Include your database connection

// Assuming you get the vehicle ID or parking ID from the request
$parkirId = $_POST['id']; // This should be passed from the frontend

// First, fetch the entry time and vehicle ID for the specified parking ID
$stmt = $koneksi->prepare("SELECT p.waktu_masuk, p.id_kendaraan, k.harga
                          FROM parkir p
                          JOIN kendaraan k ON p.id_kendaraan = k.id
                          WHERE p.id = ?");
$stmt->bind_param("i", $parkirId);
$stmt->execute();
$stmt->bind_result($waktu_masuk, $id_kendaraan, $harga_parkir);
$stmt->fetch();
$stmt->close();

// If no matching record is found, return an error
if (!$id_kendaraan) {
    echo json_encode(['success' => false, 'error' => 'Kendaraan tidak ditemukan.']);
    exit;
}

$exitTime = date('Y-m-d H:i:s'); // Get the current exit time

// Calculate duration and total fee
$entryTime = new DateTime($waktu_masuk);
$exitTimeObj = new DateTime($exitTime);
$duration = $entryTime->diff($exitTimeObj); // Duration between entry and exit

// Assuming a rate of $5 per hour
$totalBiaya = ceil($duration->h + ($duration->i / 60)) * $harga_parkir; // Round up to the next hour

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