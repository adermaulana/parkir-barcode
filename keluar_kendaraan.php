<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="./node_modules/html5-qrcode/html5-qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: #1a1a1a;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #666;
            font-size: 1.1rem;
        }

        .scanner-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .scanner-container {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            color: #1a1a1a;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-title i {
            font-size: 1.1rem;
            color: #4CAF50;
        }

        #reader {
            width: 100% !important;
            border-radius: 8px;
            overflow: hidden;
            border: none !important;
        }

        #reader video {
            border-radius: 8px;
        }

        #result {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            background-color: #f8f9fa;
            text-align: center;
        }

        #result.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        #result.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        a {
            text-decoration:none;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        button:hover {
            background-color: #45a049;
            transform: translateY(-1px);
        }

        button.secondary {
            background-color: #6c757d;
        }

        button.secondary:hover {
            background-color: #5a6268;
        }



        @keyframes scan {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(300px);
            }
            100% {
                transform: translateY(0);
            }
        }

        .amount {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>QR Code Scanner Parkir</h1>
            <p>Scan tiket parkir untuk pembayaran</p>
        </div>

        <div class="scanner-container">
            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-qrcode"></i>
                    Scanner
                </h2>
                <div style="position: relative;">
                    <div class="scan-animation"></div>
                    <div id="reader"></div>
                </div>
                <div id="result"></div>
            </div>

            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-history"></i>
                    Riwayat
                </h2>
                <table id="parkingTable">
                    <thead>
                        <tr>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Foto Kendaraan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be added here -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="button-container">
            <a href="index.php" class="secondary">
                Kembali
            </a>
        </div>

    </div>

    

    <script>
        let isScanning = true;
        let scanner;

        // Initialize scanner
        function initializeScanner() {
            scanner = new Html5QrcodeScanner('reader', { 
                qrbox: {
                    width: 250,
                    height: 250,
                }, 
                fps: 20,
            });
            scanner.render(onScanSuccess, onScanError);
        }

        // Handle successful scan
        function onScanSuccess(result) {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<h2>Sukses!</h2>';
            resultDiv.className = 'success';

            fetch('insert_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `qr_code_data=${encodeURIComponent(result)}`
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                if (data.total_bayar) {
                    resultDiv.innerHTML += `<p>Total Pembayaran: <span class="amount">Rp ${formatNumber(data.total_bayar)}</span></p>`;
                }

                // Tambahkan data ke tabel
                addTableRow({
                    entryTime: formatDateTime(new Date()),
                    exitTime: '-',
                    payment: data.total_bayar || 'Processing...',
                    foto_kendaraan: data.foto_kendaraan || 'uploads/default.png'
                });
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = '<h2>Error processing payment</h2>';
                resultDiv.className = 'error';
            });
        }

        // Handle scan errors
        function onScanError(error) {
            console.warn(error);
        }

        // Add row to table
        function addTableRow(data) {
            const tbody = document.querySelector('#parkingTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${data.entryTime}</td>
                <td>${new Date().toLocaleString()}</td>
                <td class="amount">Rp ${formatNumber(data.payment)}</td>
                <td><img src="${data.foto_kendaraan}" alt="Foto Kendaraan" style="width: 100px; height: auto;"></td>
            `;
            tbody.insertBefore(row, tbody.firstChild);
        }

        // Toggle scanner
        function toggleScanner() {
            const button = document.getElementById('scannerToggle');
            if (isScanning) {
                scanner.pause();
                button.innerHTML = '<i class="fas fa-play"></i> Resume Scanner';
            } else {
                scanner.resume();
                button.innerHTML = '<i class="fas fa-pause"></i> Pause Scanner';
            }
            isScanning = !isScanning;
        }

        // Clear history
        function clearHistory() {
            const tbody = document.querySelector('#parkingTable tbody');
            tbody.innerHTML = '';
        }

        // Format number with thousand separator
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Format date and time
        function formatDateTime(date) {
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        // Initialize scanner on page load
        initializeScanner();
    </script>

    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>