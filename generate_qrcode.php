<?php

require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check if 'time' is set
if (isset($data['time'])) {
    $currentTime = $data['time'];

    // Create QR code based on the current time
    $qrCodeResult = Builder::create()
        ->data($currentTime) // Data is the current time
        ->size(300)          // Size of the QR code
        ->build();

    // Save QR code as a PNG file
    $qrCodeFileName = 'qrcode_' . time() . '.png'; // Use timestamp for unique filename
    $qrCodeResult->saveToFile($qrCodeFileName);

    // Display the QR code
    echo '<h3>Simpan Barcode Anda</h3>';
    echo '<img src="' . htmlspecialchars($qrCodeFileName) . '" alt="QR Code">';
}
?>
