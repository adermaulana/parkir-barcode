<?php

    include 'koneksi.php';

    session_start();

    if(isset($_SESSION['status']) == 'login'){

        header("location:admin");
    }

    if(isset($_POST['login'])){

        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $login = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' and password='$password'");
        $cek = mysqli_num_rows($login);

        if($cek > 0) {
            $admin_data = mysqli_fetch_assoc($login);
            $_SESSION['id_admin'] = $admin_data['id'];
            $_SESSION['nama_admin'] = $admin_data['nama'];
            $_SESSION['username_admin'] = $username;
            $_SESSION['status'] = "login";
            header('location:admin');

        } else {
            echo "<script>
            alert('Login Gagal, Periksa Username dan Password Anda!');
            header('location:login.php');
                 </script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <style>

    .btn-color{
    background-color: #0e1c36;
    color: #fff;
    
    }

    .profile-image-pic{
    height: 200px;
    width: 200px;
    object-fit: cover;
    }



    .cardbody-color{
    background-color: #ebf2fa;
    }

    a{
    text-decoration: none;
    }

    </style>

</head>
<body>
    <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <h2 class="text-center text-dark mt-5">Login</h2>
            <div class="text-center mb-5 text-dark">Silahkan Masukkan Username dan Password</div>
            <div class="card my-5">
    
              <form class="card-body cardbody-color p-lg-5" method="POST">
                <div class="text-center">
                  <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                    width="200px" alt="profile">
                </div>
    
                <div class="mb-3">
                  <input type="text" class="form-control" id="Username" name="username" aria-describedby="emailHelp"
                    placeholder="username">
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control" id="password" name="password" placeholder="password">
                </div>
                <div class="text-center"><button type="submit" name="login" class="btn btn-color px-5 mb-5 w-100">Login</button></div>

              </form>
            </div>
    
          </div>
        </div>
      </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>


</html>

</html>