<?php

require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;

include 'koneksi.php'; // Include your database connection

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

date_default_timezone_set('Asia/Singapore');

// Directory for uploads
$uploadsDir = 'uploads';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Check if 'time', 'vehicleType', and 'photo' are set
if (isset($data['time'], $data['vehicleType'], $data['photo'])) {
    $currentTime = date('Y-m-d H:i:s');
    $vehicleType = $data['vehicleType'];
    $photoData = $data['photo'];

    // Decode base64 photo
    list($type, $photoData) = explode(';', $photoData);
    list(, $photoData) = explode(',', $photoData);
    $photoData = base64_decode($photoData);

    // Save photo to uploads folder
    $photoFileName = $uploadsDir . '/foto_' . time() . '.png';
    file_put_contents($photoFileName, $photoData);

    // Insert data into database
    $stmt = $koneksi->prepare("INSERT INTO parkir (waktu_masuk, id_kendaraan, foto_kendaraan) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $currentTime, $vehicleType, $photoFileName);
    if ($stmt->execute()) {
        $lastInsertId = $stmt->insert_id;

        // Generate QR code
        $qrCodeData = $currentTime;
        $qrCodeResult = Builder::create()
            ->data($qrCodeData)
            ->size(300)
            ->build();

        // Save QR code to file
        $qrCodeFileName = $uploadsDir . '/qrcode_' . time() . '.png';
        $qrCodeResult->saveToFile($qrCodeFileName);

        // Display QR code
        echo '<h3>Simpan QR-Code Anda</h3>';
        echo '<img src="' . htmlspecialchars($qrCodeFileName) . '" alt="QR Code">';
    } else {
        echo 'Error inserting data: ' . $stmt->error;
    }
    $stmt->close();
}

$koneksi->close();
?>
