<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="./node_modules/html5-qrcode/html5-qrcode.min.js"></script>
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
        }
        #reader {
            width: 600px;
        }
        #result {
            text-align: center;
            font-size: 1.5rem;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        button {
            background-color: #4CAF50; /* Hijau */
            color: white; /* Teks putih */
            border: none; /* Tanpa border */
            padding: 10px 15px; /* Padding dalam tombol */
            text-align: center; /* Teks di tengah */
            text-decoration: none; /* Menghilangkan garis bawah */
            display: inline-block; /* Membuat tombol inline */
            font-size: 1rem; /* Ukuran font */
            margin: 4px 2px; /* Margin */
            cursor: pointer; /* Menampilkan pointer saat hover */
            border-radius: 5px; /* Sudut membulat */
        }
        button:hover {
            background-color: #45a049; /* Warna hijau lebih gelap saat hover */
        }

        a {
            background-color: #4CAF50; /* Hijau */
            color: white; /* Teks putih */
            border: none; /* Tanpa border */
            padding: 10px 15px; /* Padding dalam tombol */
            text-align: center; /* Teks di tengah */
            text-decoration: none; /* Menghilangkan garis bawah */
            display: inline-block; /* Membuat tombol inline */
            font-size: 1rem; /* Ukuran font */
            margin: 4px 2px; /* Margin */
            cursor: pointer; /* Menampilkan pointer saat hover */
            border-radius: 5px; /* Sudut membulat */
        }

        a:hover {
            background-color: #45a049; /* Warna hijau lebih gelap saat hover */

        }

    </style>
</head>
<body>
    <main>
        <div id="reader"></div>
        <div id="result"></div>
        <table id="parkingTable">
            <thead>
                <tr>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan ditambahkan di sini -->
            </tbody>
        </table>
    </main>

    <script>
        const scanner = new Html5QrcodeScanner('reader', { 
            qrbox: {
                width: 250,
                height: 250,
            }, 
            fps: 20,
        });

        scanner.render(success, error);

        function success(result) {
            // Menampilkan hasil pemindaian
            document.getElementById('result').innerHTML = `<h2>Success!</h2>`;

            // Mengirim data ke server menggunakan fetch
            fetch('insert_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `qr_code_data=${encodeURIComponent(result)}`
            })
            .then(response => response.json()) // Mengubah response menjadi JSON
            .then(data => {
                console.log(data); // Menampilkan hasil dari server

                // Menampilkan total bayar di hasil
                if (data.total_bayar) {
                    document.getElementById('result').innerHTML += `<p>Total Bayar: Rp ${data.total_bayar}</p>`;
                }

                // Menambahkan data ke tabel
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${result}</td>
                    <td>${new Date().toLocaleString()}</td>
                    <td>${data.total_bayar || 'Belum Dihitung'}</td>
                `;
                document.getElementById('parkingTable').querySelector('tbody').appendChild(newRow);
            })
            .catch(error => {
                console.error('Error:', error);
            });

            scanner.clear(); // Menghapus pemindaian yang sudah tidak diperlukan
        }

        function error(err) {
            console.error(err); // Mencetak kesalahan ke konsol
        }
    </script>
</body>
</html>
