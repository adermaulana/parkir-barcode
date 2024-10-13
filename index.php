<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kendaraan</title>
</head>
<body>
    <h2>Form Kendaraan</h2>
    <form action="generate_qrcode.php" method="POST">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required><br><br>

        <label for="telepon">Telepon:</label>
        <input type="text" id="telepon" name="telepon" required><br><br>

        <label for="plat_kendaraan">Plat Kendaraan:</label>
        <input type="text" id="plat_kendaraan" name="plat_kendaraan" required><br><br>

        <label for="tipe_kendaraan">Tipe Kendaraan:</label>
        <input type="text" id="tipe_kendaraan" name="tipe_kendaraan" required><br><br>

        <input type="submit" value="Generate QR Code">
    </form>
</body>
</html>
