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
            const currentTime = new Date().toLocaleString(); // Mendapatkan waktu sekarang

            // Menampilkan hasil pemindaian
            document.getElementById('result').innerHTML = `
                <h2>Success!</h2>
            `;

            // Mengirim data ke server menggunakan fetch
            fetch('insert_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `qr_code_data=${encodeURIComponent(result)}`
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Menampilkan hasil dari server

                // Menambahkan data ke tabel
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><a href="${result}">${result}</a></td>
                    <td>${currentTime}</td>
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
