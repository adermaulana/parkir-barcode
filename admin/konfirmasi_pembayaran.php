<?php
// proses_konfirmasi_pembayaran.php

// Koneksi database
include '../koneksi.php';

// Terima data ID dari request
$id = $_POST['id'];

// Update status pembayaran
$query = "UPDATE parkir SET status = 'Terbayar' WHERE id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
            alert('Sukses Konfirmasi Pembayaran!');
            document.location='keluar_masuk.php';
        </script>";
} else {
    echo "<script>
            alert('Gagal Konfirmasi Pembayaran!');
            document.location='keluar_masuk.php';
        </script>";
}
