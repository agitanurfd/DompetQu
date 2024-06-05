<?php
session_start();
require "function/functions.php";

// session dan cookie multilevel user
if (isset($_COOKIE['login'])) {
    if ($_COOKIE['level'] == 'user') {
        $_SESSION['login'] = true;
        $ambilNama = $_COOKIE['login'];
    } elseif ($_COOKIE['level'] == 'admin') {
        $_SESSION['login'] = true;
        header('Location: administrator');
    }
} elseif ($_SESSION['level'] == 'user') {
    $ambilNama = $_SESSION['user'];
} else {
    if ($_SESSION['level'] == 'admin') {
        header('Location: administrator');
        exit;
    }
}

if (empty($_SESSION['login'])) {
    header('Location: login');
    exit;
}

// ambil data no rekening
$no_rek = $_GET['no_rek']; //$_GET untuk menampilkan nilai data yang dikirimkan.
$saldoRekening = $_GET['saldoRek'];
$query = "SELECT * FROM users WHERE no_rek = '$no_rek'"; //query digunakan untuk mengakses dan menampilkan data pada sistem database.
$ambilQuery = mysqli_query($koneksi, $query); //Mysqli_query berfungsi untuk menjalankan instruksi ke mysql.
$dataRekening = mysqli_fetch_assoc($ambilQuery); //Mysqli_fetch_assoc berfungsi untuk mengambil data yang berhubungan dengan mysqli.
$saldoRekFix = number_format($saldoRekening, 0, ',', '.'); //Number_format berfungsi untuk dapat memformat angka dengan ribuan yang dikelompokkan.

// tanggal hari ini
$month = date('m');
$day = date('d');
$year = date('Y');
$today = $year . '-' . $month . '-' . $day;

// ambil no rek user
$rek = mysqli_query($koneksi, "SELECT no_rek, email FROM users WHERE username = '$ambilNama'");
$ambilRekeningUser = mysqli_fetch_assoc($rek);

$noRekPengirim = $ambilRekeningUser['no_rek'];
$noRekPenerima = $dataRekening['no_rek'];
$emailPengirim = $ambilRekeningUser['email'];
$emailPenerima = $dataRekening['email'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Dompet-Qu - Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styler.css?v=1.0">
    <link rel="stylesheet" href="css/dashboard.css?v=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
    <div class="header">
        <img src="img/favicon.png" width="25px" height="25px" class="float-left logo-fav">
        <h3 class="text-secondary font-weight-bold float-left logo">Dompet</h3>
        <h3 class="text-secondary float-left logo2">- Qu</h3>
        <a href="logout">
            <div class="logout">
                <i class="fas fa-sign-out-alt float-right log"></i>
                <p class="float-right logout">Logout</p>
            </div>
        </a>
    </div>

    <div class="sidebar">
        <nav>
            <ul>
                <li>
                    <img src="img/profile1.png" class="img-fluid profile float-left" width="60px">
                    <h5 class="admin"><?= substr($ambilNama, 0, 7) ?></h5>
                    <div class="online online2">
                        <p class="float-right ontext">Online</p>
                        <div class="on float-right"></div>
                    </div>
                </li>
                <!-- fungsi slide -->
                <script>
                    $(document).ready(function() {
                        $("#flip").click(function() {
                            $("#panel").slideToggle("medium");
                            $("#panel2").slideToggle("medium");
                        });
                        $("#flip2").click(function() {
                            $("#panel3").slideToggle("medium");
                            $("#panel4").slideToggle("medium");
                        });
                    });
                </script>
                <!-- dashboard -->
                <a href="dashboard" style="text-decoration: none;">
                    <li>
                        <div>
                            <span class="fas fa-tachometer-alt"></span>
                            <span>Dashboard</span>
                        </div>
                    </li>
                </a>

                <!-- transfer -->
                <a href="transfer" style="text-decoration: none;">
                    <li class="aktif" style="border-left: 5px solid #306bff;">
                        <div>
                            <span class="fas fa-exchange-alt"></span>
                            <span>Transfer</span>
                        </div>
                    </li>
                </a>

                <!-- data -->
                <li class="klik" id="flip" style="cursor:pointer;">
                    <div>
                        <span class="fas fa-database"></span>
                        <span>Data Harian</span>
                        <i class="fas fa-caret-right float-right" style="line-height: 20px;"></i>
                    </div>
                </li>

                <a href="pemasukkan" class="linkAktif">
                    <li id="panel" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-file-invoice-dollar"></i></span>
                            <span>Data Pemasukkan</span>
                        </div>
                    </li>
                </a>

                <a href="pengeluaran" class="linkAktif">
                    <li id="panel2" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-hand-holding-usd"></i></span>
                            <span>Data Pengeluaran</span>
                        </div>
                    </li>
                </a>
                <!-- data -->

                <!-- Input -->
                <li class="klik2" id="flip2" style="cursor:pointer;">
                    <div>
                        <span class="fas fa-plus-circle"></span>
                        <span>Input Data</span>
                        <i class="fas fa-caret-right float-right" style="line-height: 20px;"></i>
                    </div>
                </li>

                <a href="tambahPemasukkan" class="linkAktif">
                    <li id="panel3" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-file-invoice-dollar"></i></span>
                            <span>Pemasukkan</span>
                        </div>
                    </li>
                </a>

                <a href="tambahPengeluaran" class="linkAktif">
                    <li id="panel4" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-hand-holding-usd"></i></span>
                            <span>Pengeluaran</span>
                        </div>
                    </li>
                </a>
                <!-- Input -->

                <!-- laporan -->
                <a href="laporan" style="text-decoration: none;">
                    <li>
                        <div>
                            <span><i class="fas fa-clipboard-list"></i></span>
                            <span>Laporan</span>
                        </div>
                    </li>
                </a>

                <!-- change icon -->
                <script>
                    $(".klik").click(function() {
                        $(this).find('i').toggleClass('fa-caret-up fa-caret-right');
                        if ($(".klik").not(this).find("i").hasClass("fa-caret-right")) {
                            $(".klik").not(this).find("i").toggleClass('fa-caret-up fa-caret-right');
                        }
                    });
                    $(".klik2").click(function() {
                        $(this).find('i').toggleClass('fa-caret-up fa-caret-right');
                        if ($(".klik2").not(this).find("i").hasClass("fa-caret-right")) {
                            $(".klik2").not(this).find("i").toggleClass('fa-caret-up fa-caret-right');
                        }
                    });
                </script>
                <!-- change icon -->
            </ul>
        </nav>
    </div>

    <div class="main-content khusus">

        <!--HALAMAN TRANSFER-->
        <div class="konten khusus2">
            <div class="konten_dalem khusus3">
                <h2 class="heade" style="color: #4b4f58;">Transfer uang</h2>
                <input type="hidden" id="username" value="<?= $ambilNama ?>">
                <script type="text/javascript" src="js/pisahTitik.js"></script>
                <hr style="margin-top: -2px; margin-bottom: 25px;">

                <!--NOMOR REKENING-->
                <?php if (mysqli_num_rows($ambilQuery) === 1) : ?>
                    <?php if ($no_rek != $ambilRekeningUser['no_rek']) : ?>
                        <!--gbisa kirim ke rekening sendiri-->
                        <div class="row">
                            <div class="col-6" style="border-right: 1.45px solid #ccc;">
                                <p>Nomor rekening</p>
                                <input type="text" value="<?= $dataRekening['no_rek'] ?>" class="form-control control" disabled>

                                <!--ID USER-->
                                <p style="margin-top: 18px;">ID User</p>
                                <input type="text" value="<?= $dataRekening['id_user'] ?>" class="form-control control" disabled>

                                <!--USERNAME-->
                                <p style="margin-top: 18px;">Username</p>
                                <input type="text" value="<?= $dataRekening['username'] ?>" class="form-control control" disabled>

                                <!--EMAIL-->
                                <p style="margin-top: 18px;">Email</p>
                                <input type="text" value="<?= $dataRekening['email'] ?>" class="form-control control" disabled>
                            </div>
                            <div class="col-6">
                                <form action="" method="post">

                                    <input type="hidden" name="username" value="<?= $dataRekening['username'] ?>">
                                    <input type="hidden" name="username2" value="<?= $ambilNama ?>">
                                    <input type="hidden" name="saldoRekening" value="<?= $saldoRekening ?>">

                                    <!--SALDO ANDA-->
                                    <p>Saldo anda</p>
                                    <input type="text" value="<?= $saldoRekFix ?>" class="form-control control" disabled>

                                    <!--TANGGAL-->
                                    <p style="margin-top: 18px;">Tanggal</p>
                                    <input type="date" name="tanggal" value="<?= $today ?>" class="form-control control">

                                    <!--MASUKKAN JUMLAH NOMINAL-->
                                    <p style="margin-top: 18px;">Masukkan jumlah nominal</p>
                                    <input type="text" class="form-control" name="jumlah" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>

                                    <!--BUTTON TRANSFER-->
                                    <button type="submit" name="transfer" class="btn btn-primary" style="margin-top: 18px;">transfer</button>
                                </form>
                            </div>
                        </div>

                        <!--jika transfer ke no rek sendiri-->
                    <?php elseif ($no_rek == $ambilRekeningUser['no_rek']) : ?>
                        <h2 style="color: #4b4f58;">Maaf anda tidak bisa transfer ke rekening sendiri!</h2>

                    <?php endif; ?>

                    <!--jika no rek tidak tersedia-->
                <?php elseif (mysqli_num_rows($ambilQuery) != 1) : ?>
                    <h2 style="color: #4b4f58;">Maaf nomor rekening <?= $no_rek ?> tidak valid / tidak tersedia!</h2>

                <?php endif; ?>

                <?php
                // transfer 
                if (isset($_POST['transfer'])) {
                    if (transfer($_POST) > 0) {

                        //jika transfer berhasil akan muncul alert dan user akan di pindahkan ke halaman dashboard
                        echo "
                                <script>
                                    alert('Berhasil, Selamat transfer berhasil!');
                                    window.location.href = 'dashboard'
                                </script>
                                ";
                    }
                }
                ?>

            </div>
        </div>
    </div>

    <script src="ajax/js/laporan.js"></script>
    <!--function laoran-->
    <script src="js/bootstrap.js"></script>
</body>

</html>