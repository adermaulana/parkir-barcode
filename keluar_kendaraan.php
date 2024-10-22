<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <video id="video" width="300" height="200" style="border: 1px solid black"></video>
    <canvas id="canvas" hidden></canvas>
    <p>Result: <span id="result"></span></p>

    <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const result = document.getElementById('result');

        // Akses kamera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(function (stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function (err) {
                console.log("Error accessing camera: " + err);
            });

        function scanQRCode() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                result.textContent = code.data; // QR code result
            } else {
                requestAnimationFrame(scanQRCode); // Cek ulang
            }
        }

        video.addEventListener('play', scanQRCode);
    </script>
</body>
</html>
