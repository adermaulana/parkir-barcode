<?php

require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $plat_kendaraan = $_POST['plat_kendaraan'];
    $tipe_kendaraan = $_POST['tipe_kendaraan'];

    // Membuat QR code berdasarkan plat kendaraan
    $qrCodeResult = Builder::create()
        ->data($plat_kendaraan) // Data plat kendaraan sebagai isi QR code
        ->size(300)              // Ukuran QR code
        ->build();

    // Menyimpan QR code sebagai file PNG
    $qrCodeFileName = 'qrcode_' . $plat_kendaraan . '.png';
    $qrCodeResult->saveToFile($qrCodeFileName);

    // Menampilkan data dan QR code
    echo "<h2>Detail Kendaraan</h2>";
    echo "Nama: " . $nama . "<br>";
    echo "Telepon: " . $telepon . "<br>";
    echo "Plat Kendaraan: " . $plat_kendaraan . "<br>";
    echo "Tipe Kendaraan: " . $tipe_kendaraan . "<br><br>";

    echo "<h3>QR Code untuk Plat Kendaraan:</h3>";
    echo '<img src="' . $qrCodeFileName . '" alt="QR Code">';
}
?>
