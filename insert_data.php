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
$sql = "SELECT * FROM parkir WHERE waktu_masuk = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $qr_code_data);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika ada, ambil waktu_masuk
    $row = $result->fetch_assoc();
    $waktu_masuk = $row['waktu_masuk'];

    // Hitung selisih waktu dalam jam
    $datetime1 = new DateTime($waktu_masuk);
    $datetime2 = new DateTime($waktu_keluar);
    $interval = $datetime1->diff($datetime2);
    $jam = $interval->h + ($interval->days * 24); // Hitung total jam

    // Perbarui waktu_keluar dan selisih
    $sql_update = "UPDATE parkir SET waktu_keluar = ?, selisih = ? WHERE waktu_masuk = ?";
    $stmt_update = $koneksi->prepare($sql_update);
    $stmt_update->bind_param("sis", $waktu_keluar, $jam, $qr_code_data);

    if ($stmt_update->execute()) {
        echo "Data waktu keluar dan selisih berhasil diperbarui.";
    } else {
        echo "Error: " . $stmt_update->error;
    }

    $stmt_update->close();
} else {
    // Jika tidak ada, masukkan entri baru
    $sql_insert = "INSERT INTO parkir (waktu_masuk, waktu_keluar, selisih) VALUES (?, ?, ?)";
    $selisih = 0; // Total selisih saat masuk
    $stmt_insert = $koneksi->prepare($sql_insert);
    $stmt_insert->bind_param("ssi", $qr_code_data, $waktu_keluar, $selisih);

    if ($stmt_insert->execute()) {
        echo "Data berhasil disimpan.";
    } else {
        echo "Error: " . $stmt_insert->error;
    }

    $stmt_insert->close();
}

// Menutup koneksi
$stmt->close();
$koneksi->close();
?>
