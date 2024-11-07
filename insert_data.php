<?php

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
    // Jika ada, ambil waktu_masuk dan tarif_parkir
    $row = $result->fetch_assoc();
    $waktu_masuk = $row['waktu_masuk'];
    $tarif_parkir = $row['harga'];

    // Hitung selisih waktu dalam jam
    $datetime1 = new DateTime($waktu_masuk);
    $datetime2 = new DateTime($waktu_keluar);
    $interval = $datetime1->diff($datetime2);
    $jam = $interval->h + ($interval->days * 24);
    $total_bayar = 0; // Inisialisasi total bayar

    // Jika selisih waktu kurang dari 1 jam
    if ($jam < 1) {
        $total_bayar = $tarif_parkir; // Biaya minimum jika kurang dari 1 jam
    } else {
        // Hitung total bayar jika lebih dari 1 jam
        $total_bayar = $jam * $tarif_parkir;
    }

    // Perbarui waktu_keluar dan total_bayar
    $sql_update = "UPDATE parkir SET waktu_keluar = ?, total_bayar = ? WHERE waktu_masuk = ?";
    $stmt_update = $koneksi->prepare($sql_update);
    // Menggunakan 's' untuk datetime dan 'i' untuk integer
    $stmt_update->bind_param("sis", $waktu_keluar, $total_bayar, $qr_code_data); 

    if ($stmt_update->execute()) {
        $response['message'] = "Data waktu keluar dan total bayar berhasil diperbarui.";
        $response['total_bayar'] = $total_bayar; // Kirim total bayar kembali ke front-end
    } else {
        $response['error'] = "Error: " . $stmt_update->error;
    }

    $stmt_update->close();
} else {
    // Jika tidak ada, masukkan entri baru
    $sql_insert = "INSERT INTO parkir (id_kendaraan, waktu_masuk, waktu_keluar, total_bayar) VALUES (?, ?, ?, ?)";
    $id_kendaraan = null; // Isi dengan ID kendaraan yang didapatkan dari QR code
    $total_bayar = 4000; // Total bayar saat masuk
    $stmt_insert = $koneksi->prepare($sql_insert);
    $stmt_insert->bind_param("issi", $id_kendaraan, $qr_code_data, $waktu_keluar, $total_bayar); // 'issi' karena id_kendaraan adalah integer

    if ($stmt_insert->execute()) {
        $response['message'] = "Data berhasil disimpan.";
        $response['total_bayar'] = $total_bayar; // Kirim total bayar
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