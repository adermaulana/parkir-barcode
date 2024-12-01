<?php
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'koneksi.php';

function generateUniqueTicketID() {
    return 'PK-' . strtoupper(bin2hex(random_bytes(4)));
}

date_default_timezone_set('Asia/Singapore');

$uploadsDir = 'uploads';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['time'], $data['vehicleType'], $data['photo'], $data['email'])) {
        throw new Exception('Data tidak lengkap');
    }

    $currentTime = date('Y-m-d H:i:s');
    $vehicleType = $data['vehicleType'];
    $photoData = $data['photo'];
    $email = $data['email'];
    $uniqueTicketID = generateUniqueTicketID();

    // Proses foto
    list($type, $photoData) = explode(';', $photoData);
    list(, $photoData) = explode(',', $photoData);
    $photoData = base64_decode($photoData);

    $photoFileName = $uploadsDir . '/foto_' . time() . '.png';
    file_put_contents($photoFileName, $photoData);

    // Ambil nama tipe kendaraan
    $vehicleTypeQuery = $koneksi->prepare("SELECT tipe_kendaraan FROM kendaraan WHERE id = ?");
    $vehicleTypeQuery->bind_param("i", $vehicleType);
    $vehicleTypeQuery->execute();
    $vehicleTypeResult = $vehicleTypeQuery->get_result();
    $vehicleTypeRow = $vehicleTypeResult->fetch_assoc();
    $vehicleTypeName = $vehicleTypeRow['tipe_kendaraan'];

    // Simpan data parkir
    $stmt = $koneksi->prepare("INSERT INTO parkir (waktu_masuk, id_kendaraan, foto_kendaraan, email, ticket_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $currentTime, $vehicleType, $photoFileName, $email, $uniqueTicketID);
    
    if (!$stmt->execute()) {
        throw new Exception('Gagal menyimpan data');
    }

    // Buat QR Code
    $qrCodeResult = Builder::create()
        ->data($currentTime)
        ->size(300)
        ->build();

    $qrCodeFileName = $uploadsDir . '/qrcode_' . time() . '.png';
    $qrCodeResult->saveToFile($qrCodeFileName);

    // Buat isi email
    $emailBody = "Konfirmasi Tiket Parkir - $uniqueTicketID

Yth. Pelanggan,

Terima kasih telah menggunakan layanan parkir kami.

Detail Parkir:
- Waktu Masuk: $currentTime
- Jenis Kendaraan: $vehicleTypeName
- Lokasi: Area Parkir Utama
- Nomor Tiket: $uniqueTicketID

Petunjuk:
1. Simpan QR Code terlampir
2. Tunjukkan QR Code saat keluar parkir

Terima kasih,
Tim Manajemen Parkir";

    // Kirim email
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'parkirmanajemen@gmail.com';
    $mail->Password   = 'qmnw fbom thvx kulk';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('parkirmanajemen@gmail.com', 'Sistem Parkir');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Konfirmasi Parkir - Tiket $uniqueTicketID";
    $mail->Body = nl2br($emailBody);
    $mail->AltBody = strip_tags($emailBody);
    
    $mail->addAttachment($qrCodeFileName, 'QRCode.png');
    $mail->addAttachment($photoFileName, 'FotoKendaraan.png');

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => 'Konfirmasi parkir berhasil',
        'qrCodePath' => $qrCodeFileName,
        'ticketId' => $uniqueTicketID
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
} finally {
    $koneksi->close();
}
?>