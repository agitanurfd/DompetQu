<?php
session_start();
require "function/functions.php";

//session_start() berfungsi untuk mengeksekusi session (functions.php) pada server kemudian menyimpannya pada browser. Session untuk menyimpan informasi user untuk digunakan di beberapa halaman.
//Substr berfungsi untuk memotong atau mengambil Sebagian string.
//isset berfungsi utk memeriksa apakah sebuah variabel disetel, yg berarti ia harus dideklarasikan dan bukan NULL. Fungsi ini mengembalikan nilai true jika variabel ada dan jika tidak maka mengembalikan false.

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

$totalPemasukan = query("SELECT * FROM pemasukkan WHERE username = '$ambilNama'");
$totalPengeluaran = query("SELECT * FROM pengeluaran WHERE username = '$ambilNama'");

foreach ($totalPemasukan as $rowMasuk) {
    $hargaMasuk[] = $rowMasuk["jumlah"];
    $convertHarga = str_replace('.', '', $hargaMasuk);
    $totalMasuk = array_sum($convertHarga);
}

foreach ($totalPengeluaran as $rowKeluar) {
    $hargaKeluar[] = $rowKeluar["jumlah"];
    $convertHarga2 = str_replace('.', '', $hargaKeluar);
    $totalKeluar = array_sum($convertHarga2);
}

global $totalMasuk, $totalKeluar;
$saldo = $totalMasuk - $totalKeluar;
$saldoFix = number_format($saldo, 0, ',', '.');

$month = date('m');
$day = date('d');
$year = date('Y');

$today = $year . '-' . $month . '-' . $day;

// pemasukkan rekening
$rekeningMasuk = query("SELECT * FROM rekening_masuk WHERE username = '$ambilNama'");
foreach ($rekeningMasuk as $rowRekIn) {
    $jumlah[] = $rowRekIn['jumlah'];
    $jumlahConvert = str_replace('.', '', $jumlah);
    $totalRekIn = array_sum($jumlahConvert);
}

// pengeluaran rekening
$rekeningKeluar = query("SELECT * FROM rekening_keluar WHERE username = '$ambilNama'");
foreach ($rekeningKeluar as $rowRekOut) {
    $jumlah2[] = $rowRekOut['jumlah'];
    $jumlahConvert2 = str_replace('.', '', $jumlah2);
    $totalRekOut = array_sum($jumlahConvert2);
}

// saldo rekening
global $totalRekIn, $totalRekOut;
$saldoRek = $totalRekIn - $totalRekOut;
$saldoRekFix = number_format($saldoRek, 0, ',', '.');
$no = 1;

// get no rekening
$query = "SELECT * FROM users WHERE username = '$ambilNama'";
$ambilQuery = mysqli_query($koneksi, $query);
$ambilData = mysqli_fetch_assoc($ambilQuery);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Dompet-Qu - Dashboard</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styler.css?v=1.0">
    <link rel="stylesheet" href="css/dashboard.css?v=1.0">
    <script src="js/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="js/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

    <style>
        .rentang {
            padding-bottom: 75px;
        }
    </style>
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
                <li class="rentang">
                    <a href="profile.php">
                        <img src="img/profile1.png" class="img-fluid profile float-left" width="60px">
                        <h5 class="admin"><?= substr($ambilNama, 0, 7) ?></h5>
                    </a>
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
                    <li class="aktif" style="border-left: 5px solid #306bff;">
                        <div>
                            <span class="fas fa-tachometer-alt"></span>
                            <span>Dashboard</span>
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

    <!--halaman profile-->
    <div class="main-content khusus">
        <div class="konten khusus2">
            <div class="konten_dalem khusus3">
                <h2 class="heade" style="color: #4b4f58;">Profile</h2>
                <hr style="margin-top: -2px;">
            </div>
        </div>
    </div>
    <div class="profile-box">
        <div class="box">
            <img src="img/profile1.png" alt="" class="box-img">
            <h1><?= substr($ambilNama, 0, 7) ?></h1>
            <br>
            <div class="tbody">
                <table>
                    <tbody>
                        <tr>
                            <td>Jumlah Saldo</td>
                            <td>:</td>
                            <td>Rp. <?= $saldoRekFix; ?></td>
                        </tr>
                        <tr>
                            <td>No. Rekening</td>
                            <td>:</td>
                            <td>&nbsp; &nbsp;<?= $ambilData['no_rek'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>