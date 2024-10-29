<?php

require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;

include 'koneksi.php'; // Include your database connection

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

date_default_timezone_set('Asia/Singapore');
// Check if 'time' is set
if (isset($data['time'])) {
    // Use the current time in DATETIME format
    $currentTime = date('Y-m-d H:i:s');

    // Insert the timestamp into the 'parkir' table
    $stmt = $koneksi->prepare("INSERT INTO parkir (waktu_masuk) VALUES (?)");
    $stmt->bind_param("s", $currentTime);
    
    if ($stmt->execute()) {
        // Insertion successful
    } else {
        // Handle error if needed
        error_log("Error inserting data: " . $stmt->error);
    }
    $stmt->close();

    // Create the uploads directory if it doesn't exist
    $uploadsDir = 'uploads';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true); // Create with permissions if it doesn't exist
    }

    // Create QR code based on the current time
    $qrCodeResult = Builder::create()
        ->data($currentTime) // Data is the current time
        ->size(300)          // Size of the QR code
        ->build();

    // Save QR code as a PNG file in the uploads directory
    $qrCodeFileName = $uploadsDir . '/qrcode_' . time() . '.png'; // Use timestamp for unique filename
    $qrCodeResult->saveToFile($qrCodeFileName);

    // Display the QR code
    echo '<h3>Simpan QR-Code Anda</h3>';
    echo '<img src="' . htmlspecialchars($qrCodeFileName) . '" alt="QR Code">';
}

// Close the database connection
$koneksi->close();

?>
