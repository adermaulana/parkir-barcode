

<?php

// memasukkan data ke dalam database ketika scan qrcode

include 'koneksi.php';

date_default_timezone_set('Asia/Singapore');
// Periksa koneksi
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Mendapatkan data dari permintaan POST
$qr_code_data = $_POST['qr_code_data'];
$waktu_keluar = date('Y-m-d H:i:s'); // Waktu keluar saat ini

// Pertama, periksa apakah ada entri yang cocok dengan qr_code_data (dianggap sama dengan waktu_masuk)
$sql = "SELECT p.*, k.harga
        FROM parkir p
        JOIN kendaraan k ON p.id_kendaraan = k.id
        WHERE p.waktu_masuk = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $qr_code_data);
$stmt->execute();
$result = $stmt->get_result();

$response = []; // Menyiapkan array untuk response

if ($result->num_rows > 0) {
    // Jika data ditemukan
    $row = $result->fetch_assoc();

    // Periksa apakah waktu_keluar sudah diisi
    if (!empty($row['waktu_keluar'])) {
        $response['error'] = "Data sudah memiliki waktu keluar, tidak dapat diubah lagi.";
    } else {
        $waktu_masuk = $row['waktu_masuk'];
        $tarif_parkir = $row['harga'];
        $foto_kendaraan = $row['foto_kendaraan'] ?? 'uploads/default.png'; // Default jika null

        // Hitung total bayar
        $datetime1 = new DateTime($waktu_masuk);
        $datetime2 = new DateTime($waktu_keluar);
        $interval = $datetime1->diff($datetime2);
        $jam = $interval->h + ($interval->days * 24);
        $total_bayar = $jam < 1 ? $tarif_parkir : $jam * $tarif_parkir;

        // Update database
        $sql_update = "UPDATE parkir SET waktu_keluar = ?, total_bayar = ? WHERE waktu_masuk = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        $stmt_update->bind_param("sis", $waktu_keluar, $total_bayar, $qr_code_data);
        if ($stmt_update->execute()) {
            $response['message'] = "Data waktu keluar dan total bayar berhasil diperbarui.";
            $response['total_bayar'] = $total_bayar;
            $response['waktu_masuk'] = $qr_code_data;
            $response['foto_kendaraan'] = $foto_kendaraan; // Sertakan dalam respons
        } else {
            $response['error'] = "Error: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
} else {
    // Jika data tidak ditemukan, masukkan entri baru
    $response['foto_kendaraan'] = 'uploads/default.png'; // Default untuk data baru
    $sql_insert = "INSERT INTO parkir (id_kendaraan, waktu_masuk, waktu_keluar, total_bayar) VALUES (?, ?, ?, ?)";
    $id_kendaraan = null; // Misalnya, null untuk saat ini
    $total_bayar = 4000; // Biaya minimum
    $stmt_insert = $koneksi->prepare($sql_insert);
    $stmt_insert->bind_param("issi", $id_kendaraan, $qr_code_data, $waktu_keluar, $total_bayar);
    if ($stmt_insert->execute()) {
        $response['message'] = "Data berhasil disimpan.";
        $response['total_bayar'] = $total_bayar;
        $response['waktu_masuk'] = $qr_code_data;
    } else {
        $response['error'] = "Error: " . $stmt_insert->error;
    }
    $stmt_insert->close();
}

// Mengembalikan response dalam format JSON
echo json_encode($response);

// Menutup koneksi
$stmt->close();
$koneksi->close();
?>
