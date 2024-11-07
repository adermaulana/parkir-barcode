<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Parkir Barcode</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/home2/img/favicon.png" rel="icon">
  <link href="assets/home2/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/home2/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/home2/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/home2/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/home2/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/home2/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/home2/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: QuickStart
  * Template URL: https://bootstrapmade.com/quickstart-bootstrap-startup-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>

    .btn-no-style {
    background: none; /* Removes the button background */
    border: none; /* Removes the border */
    padding: 0; /* Removes default padding */
    cursor: pointer; /* Changes the cursor to pointer on hover */
    }

    .btn-no-style:focus {
    outline: none; /* Removes the focus outline */
    }


  </style>

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <img src="assets/home2/img/logo.png" alt="">
        <h1 class="sitename">PARKIR</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Home</a></li>
          <li><a href="keluar_kendaraan.php" class="active">Keluar Kendaraan</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="login.php">Login</a>

    </div>
  </header>

  <main class="main">
    <section id="hero" class="hero section">
      <div class="hero-bg">
        <img src="assets/home2/img/hero-bg-light.webp" alt="">
      </div>
      <div class="container text-center">
        <div class="d-flex flex-column justify-content-center align-items-center">
          <h1 data-aos="fade-up">Ambil Karcis Parkir</h1>
          <div class="form-group mt-5">
            <select class="form-control" id="vehicleType">
            <option value="" selected>Pilih tipe kendaraan</option>
            <?php

            include 'koneksi.php';

                $no = 1;
                $tampil = mysqli_query($koneksi, "SELECT * FROM kendaraan");
                while($data = mysqli_fetch_array($tampil)):
            ?>
              <option value="<?= $data['id'] ?>"><?= $data['tipe_kendaraan'] ?></option>
              <?php
                endwhile; 
              ?>
            </select>
          </div>
          <button id="generateQRCode" class="btn-no-style mt-4">
            <img src="assets/home2/img/tombol.jpg" class="img-fluid hero-img" alt="" data-aos="zoom-out" data-aos-delay="300">
          </button>
          <div id="qrCodeContainer"></div> <!-- To display the QR code -->
          <a id="downloadQRCode" style="display: none;" class="btn btn-success" name="qrcode" download>Download QR Code</a> <!-- Hidden download link -->
        </div>
      </div>
    </section><!-- /Hero Section -->
  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">QuickStart</span>
          </a>
          <div class="footer-contact pt-3">
            <p>A108 Adam Street</p>
            <p>New York, NY 535022</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>info@example.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Product Management</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Graphic Design</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
          </form>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">QuickStart</strong><span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/home2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/home2/vendor/php-email-form/validate.js"></script>
  <script src="assets/home2/vendor/aos/aos.js"></script>
  <script src="assets/home2/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/home2/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/home2/js/main.js"></script>


  <script>
    document.getElementById('generateQRCode').addEventListener('click', function() {
      // Get the selected vehicle type
      const vehicleType = document.getElementById('vehicleType').value;

      // Validasi apakah tipe kendaraan sudah dipilih
      if (vehicleType === '') {
        alert('Silakan pilih tipe kendaraan terlebih dahulu.');
        return;
      }

      // Get the current time
      const currentTime = new Date().toLocaleString();

      // Send an AJAX request to the PHP script
      fetch('generate_qrcode.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ time: currentTime, vehicleType: vehicleType })
      })
      .then(response => response.text())
      .then(data => {
        document.getElementById('qrCodeContainer').innerHTML = data; // Display the QR code

        // Show the download button with the QR code image source
        const qrCodeImg = document.querySelector('#qrCodeContainer img');
        if (qrCodeImg) {
          const downloadLink = document.getElementById('downloadQRCode');
          downloadLink.href = qrCodeImg.src; // Set the download link to the QR code image
          downloadLink.style.display = 'inline'; // Show the download button
          downloadLink.textContent = 'Download QR Code'; // Set link text
        }
      })
      .catch(error => console.error('Error:', error));
    });

    document.getElementById('downloadQRCode').addEventListener('click', function() {
      // Tunggu sejenak agar unduhan selesai, kemudian refresh halaman
      setTimeout(function() {
        window.location.reload();
      }, 1000); // Tunggu 1 detik sebelum refresh
    });
  </script>

</body>

</html>