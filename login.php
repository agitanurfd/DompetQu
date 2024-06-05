<?php
session_start(); //untuk memulai ekseskusi session pada server kemudia menyimpannya di browser.
require 'function/functions.php'; //require itu kyk butuh, jika file ini tidak ada maka script selanjutnya tidak berjalan.
require 'function/loginRegister.php';

// cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) { //isset untuk memeriksa apakah suatu variabel sudah diatur atau belum. && mengembalikan true jika kedua operan adalah true dan sebaliknya.
  $id = $_COOKIE['id']; //$_COOKIE untuk menyimpan data website pada browser, jd saat user kembali ke halaman yg pernah dibuka sebelumnya, informasi tsb dpt diambil kembali dari browser.
  $key = $_COOKIE['key'];

  // ambil username berdasarkan id
  global $koneksi;
  $result = mysqli_query($koneksi, "SELECT username FROM users WHERE id_user = $id"); // mysqli_query untuk menjalankan instruksi ke mysql. Query untuk mengakses dan menampilkan data pada sistem database.
  $row = mysqli_fetch_assoc($result);
  //mysqli_fetch_assoc untuk mengambil data yang berhubungan dengan mysqli.

  // cek cookie dan username
  if ($key === hash('sha256', $row['username'])) {
    $_SESSION['login'] = true;
  }
}

if (isset($_SESSION["login"])) { //$_SESSION berfungsi untuk sebuah variabel sementara yang diletakkan di dalam server.
  header("Location: dashboard");
  exit;
} elseif (isset($_COOKIE['login'])) {
  header("Location: dashboard");
  exit;
}

// login
if (isset($_POST['login'])) { //$_POST untuk memanggil data yang telah diinputkan agar bisa ditampilkan.
  login($_POST);
}

// register
if (isset($_POST['sign-up'])) {
  if (register($_POST) > 0) {
    echo "
          <script>
              swal('Berhasil','Akun anda berhasil didaftarkan!','success');
          </script>
      ";
  } else {
    echo mysqli_error($koneksi); //echo mysqli_error untuk mengembalikan deskripsi kesalahan terakhir untuk pemanggilan fungsi terbaru. echo ini berfungsi untuk menampilkan teks ke layar.
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>Login page</title>

  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <!--buat ikon-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <!--mullai bootstrap-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <!--buat bootstrap icon-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!--untuk menampilkan pop up alert, misal gagal login/sign up-->
  <style>
    body {
      background: url('img/body.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      font-family: "roboto", sans-serif;
    }

    .img {
      background: url('img/login-bg.jpg');
      background-size: cover;
      background-position: center;
      height: 100%;
      top: 0;
      position: absolute;
      width: 100%;
      z-index: 2;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-md-center mt-12">
      <!--justify content untuk mensejajarkan item-item diantara flexbox, mt (margin top)-->
      <div class="col-sm-8 border-box">
        <!--mengatur grid pada layar monitor yg berukuran sedang-->
        <div class="row">
          <div class="col-sm-6 p-0">
            <!--p-0 adalah padding, padding untuk memberi spasi antara konten dengan border-->
            <div class="card">
              <div class="card-header">
                <div class="signup">
                  <h4 class="aktif">SIGN UP</h4>
                </div>

                <div>
                  <h4> / </h4>
                </div>

                <div class="login">
                  <h4>LOGIN</h4>
                </div>

                <!--bagian sign up-->
                <div class="sub-title">Registrasi untuk gunakan Dompet - Qu</div>
              </div>

              <div class="icon-user">
                <h4 class="fa fa-user"> </h4> <!-- untuk memberi icon user-->
              </div>
              <div class="card-body">
                <form method="POST">
                  <!--method post atau get digunakan untuk mengirim data ke suatu server untuk diolah-->
                  <div class="input-group mb-3">
                    <!-- mb itu margin bottom-->
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                      <!--untuk memberi icon-->
                    </div>
                    <input type="text" name="email-registrasi" class="form-control" placeholder="Email" autocomplete="off" required>
                    <!--autocomplete adalah teks yg otomatis menyesuaikan dengan yg kita input-->
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                      <!--menampilkan icon user-->
                    </div>
                    <input type="text" name="username-registrasi" class="form-control" placeholder="Username" autocomplete="off" required>
                  </div>

                  <!--SHOW HIDE PASSOWORD LOGIN-->
                  <div class="input-group mb-3" id="show_hide_password">
                    <div class="input-group-prepend">
                      <a href="" class="input-group-text"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                    </div>
                    <input type="password" name="password-registrasi" class="form-control" placeholder="Password" autocomplete="off" required>
                  </div>

                  <!--confirm password-->
                  <div class="input-group mb-3" id="show_hide_password">
                    <div class="input-group-prepend">
                      <a href="" class="input-group-text"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                    </div>
                    <input type="password" name="password-confirm" class="form-control" placeholder="Confirm password" autocomplete="off" required>
                  </div>


                  <button type="submit" name="sign-up" class="btn btn-primary">Sign up</button>
                </form>
              </div>
            </div>
            <div class="img"></div>
          </div>

          <!--bagian login-->
          <div class="col-sm-6 p-0">
            <div class="card">
              <div class="card-header">
                <div class="login">
                  <h4 class="aktif">LOGIN</h4>
                </div>
                <div>
                  <h4> / </h4>
                </div>
                <div class="signup">
                  <h4>SIGN UP</h4>
                </div>
                <div class="sub-title">Login untuk gunakan Dompet - Qu</div>
              </div>
              <div class="icon-user">
                <h4 class="fa fa-user"></h4>
              </div>

              <div class="card-body">
                <form method="POST">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="text" name="user-email" class="form-control" placeholder="Username / email" autocomplete="off" required>
                  </div>

                  <!--SHOW HIDE PASSOWORD SIGN UP-->
                  <div class="input-group mb-3" id="show_hide_password">
                    <div class="input-group-prepend">
                      <a href="" class="input-group-text"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                    </div>
                    <input type="password" name="password-login" placeholder="Enter password" class="form-control" required autocomplete="current-password">
                  </div>


                  <div class="form-group">
                    <label class="mz-check">
                      <input type="checkbox" name="rememberme">
                      <i class="mz-blue"></i>
                      Remember Me
                    </label>
                  </div>

                  <button type="submit" name="login" class="btn btn-primary" style="margin-top: -15px">Login</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--function show password-->
  <script>
    $(document).ready(function() {
      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("bi bi-eye-slash");
          $('#show_hide_password i').removeClass("bi bi-eye");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("bi bi-eye-slash");
          $('#show_hide_password i').addClass("bi bi-eye");
        }
      });
    });
  </script>
  <!--attr () metode set atau mengembalikan atribut elemen yang dipilih dan nilai-nilai-->

  <script src="js/slidelogin.js"></script>
  <!--utuk slide gambar pada login dan sign up-->
</body>

</html>