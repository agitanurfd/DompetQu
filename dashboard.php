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

$totalPemasukan = query("SELECT * FROM pemasukkan WHERE username = '$ambilNama'"); //untuk mengakses dan menampilkan data pada sistem database.
$totalPengeluaran = query("SELECT * FROM pengeluaran WHERE username = '$ambilNama'");

foreach ($totalPemasukan as $rowMasuk) { //foreach perulangan khusus untuk pembacaan nilai array
    $hargaMasuk[] = $rowMasuk["jumlah"]; //harga masuk berarti sama dengan total pemasukan
    $convertHarga = str_replace('.', '', $hargaMasuk); //str_replace untuk menggantikan beberapa karakter dengan beberapa karakter dalam sebuah string.
    $totalMasuk = array_sum($convertHarga); //array_sum digunakan untuk mengembalikan jumlah semua nilai dalam array.
}

foreach ($totalPengeluaran as $rowKeluar) {
    $hargaKeluar[] = $rowKeluar["jumlah"];
    $convertHarga2 = str_replace('.', '', $hargaKeluar);
    $totalKeluar = array_sum($convertHarga2);
}

//saldo dompet
global $totalMasuk, $totalKeluar; //global ini adalah variabel yang selalu bisa diakses kapan pun dan dimana pun
$saldo = $totalMasuk - $totalKeluar;
$saldoFix = number_format($saldo, 0, ',', '.');
//number_format digunakan untuk memformat tampilan angka.

$month = date('m');
$day = date('d');
$year = date('Y');

$today = $year . '-' . $month . '-' . $day;

// pemasukkan rekening
$rekeningMasuk = query("SELECT * FROM rekening_masuk WHERE username = '$ambilNama'"); //untuk mengakses dan menampilkan data pada sistem database.
foreach ($rekeningMasuk as $rowRekIn) {
    $jumlah[] = $rowRekIn['jumlah'];
    $jumlahConvert = str_replace('.', '', $jumlah); //str_replace untuk menggantikan beberapa karakter dengan beberapa karakter dalam sebuah string.
    $totalRekIn = array_sum($jumlahConvert);
}

// pengeluaran rekening
$rekeningKeluar = query("SELECT * FROM rekening_keluar WHERE username = '$ambilNama'"); //untuk mengakses dan menampilkan data pada sistem database.
foreach ($rekeningKeluar as $rowRekOut) {
    $jumlah2[] = $rowRekOut['jumlah'];
    $jumlahConvert2 = str_replace('.', '', $jumlah2); //str_replace untuk menggantikan beberapa karakter dengan beberapa karakter dalam sebuah string.
    $totalRekOut = array_sum($jumlahConvert2);
}

// saldo rekening
global $totalRekIn, $totalRekOut;
$saldoRek = $totalRekIn - $totalRekOut;
$saldoRekFix = number_format($saldoRek, 0, ',', '.');
$no = 1; //untuk penomoran tabel

// get no rekening
$query = "SELECT * FROM users WHERE username = '$ambilNama'";
$ambilQuery = mysqli_query($koneksi, $query); //untuk menjalankan instruksi ke mysql.
$ambilData = mysqli_fetch_assoc($ambilQuery); //untuk mengambil data yang berhubungan dengan mysqli.

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
                        <!--digunakan untuk subset string berdasarkan posisi awal yang ditentukan atau memotong atau mengambil Sebagian string.-->
                    </a>
                    <div class="online online2">
                        <p class="float-right ontext">Online</p>
                        <div class="on float-right"></div>
                    </div>
                </li>
                <!-- fungsi flip pada bagian gambar profile -->
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
                        <!--Li fungsi dalam HTML yang digunakan untuk menampilkan data secara berurutan ke bawah.-->
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

                <!-- change icon ketika diklik ikon tanda panahnya kebawah, ini ada dibagian data harian dan input data -->
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
        <div class="konten khusus2">
            <div class="konten_dalem khusus3">
                <h2 class="heade" style="color: #4b4f58;">Dashboard</h2>
                <hr style="margin-top: -2px;">
                <div class="container" id="container" style="border: none;">
                    <div class="row tampilCardview" id="row">

                        <!--bagian saldo dompet-->
                        <div class="col-md-4 jarak">
                            <div class="card card-stats card-warning" style="background: #347ab8;">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-wallet ikon"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center tulisan">
                                            <div class="numbers">
                                                <p class="card-category ket head">Saldo Rekening</p>
                                                <h4 class="card-title ket total">Rp. <?= $saldoRekFix; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--card tambah pengeluaran-->
                        <div class="col-md-4 jarak">
                            <a href="tambahPengeluaran" style="text-decoration: none;">
                                <div class="card card-stats card-warning" style="background: #d95350;">
                                    <div class="card-body ">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fa fa-file-invoice-dollar ikon"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center tulisan">
                                                <div class="numbers">
                                                    <p class="card-category ket head">Pengeluaran</p>
                                                    <?php foreach ($totalPengeluaran as $row) : ?>
                                                        <?php
                                                        $hargaPengeluaran[] = $row["jumlah"];
                                                        $hargaConvert = str_replace('.', '', $hargaPengeluaran);
                                                        $totalPeng = array_sum($hargaConvert);
                                                        $hasilHargaPengeluaran = number_format($totalPeng, 0, ',', '.');
                                                        ?>
                                                    <?php endforeach; ?>

                                                    <?php global $hasilHargaPengeluaran;
                                                    if ($hasilHargaPengeluaran != "") : ?>
                                                        <h4 class="card-title ket total">Rp. <?= $hasilHargaPengeluaran; ?></h4>
                                                    <?php else : ?>
                                                        <h4 class="card-title ket total">Rp. 0</h4>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="overlay" style="background: #e45351;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fas fa-plus-circle ikon2"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center">
                                                <p class="tulisan">Tambah Data</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- card tambah pemasukkan-->
                        <div class="col-md-4 jarak">
                            <a href="tambahPemasukkan" style="text-decoration: none;">
                                <div class="card card-stats card-warning" style="background: #5db85b;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fa fa-hand-holding-usd ikon"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center tulisan">
                                                <div class="numbers">
                                                    <p class="card-category ket head">Pemasukkan</p>
                                                    <?php foreach ($totalPemasukan as $row) : ?>
                                                        <?php
                                                        $hargaPemasukkan[] = $row["jumlah"];
                                                        $hargaConvert = str_replace('.', '', $hargaPemasukkan);
                                                        $totalPem = array_sum($hargaConvert);
                                                        $hasilHarga = number_format($totalPem, 0, ',', '.');
                                                        ?>
                                                    <?php endforeach ?>

                                                    <?php global $hasilHarga;
                                                    if ($hasilHarga != "") : ?>
                                                        <h4 class="card-title ket total">Rp. <?= $hasilHarga ?> </h4>
                                                    <?php else : ?>
                                                        <h4 class="card-title ket total">Rp. 0 </h4>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="overlay">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fas fa-plus-circle ikon2"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center">
                                                <p class="tulisan">Tambah Data</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="rekening">
                            <div class="row tampil">
                                <div class="col-lg-5 rek">
                                    <div class="konten-rekening border-right">

                                        <!--bagian saldo rekekning-->
                                        <h3>Rp. <?= $saldoRekFix ?></h3>
                                        <!--bagian button kelola rekening-->
                                        <button class="btn btn-lg add-rekening btn-prev" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-dollar-sign"></i>
                                            Kelola rekening</button>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="history text-center">
                                                    <a href="#" id="openBtn3">
                                                        <i class="fas fa-history"></i>
                                                        <span>History</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="refresh text-center">
                                                    <a href="dashboard">
                                                        <i class="fas fa-sync-alt"></i>
                                                        <span>Refresh</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 rek">
                                    <canvas id="myChart" width="60px" height="30px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="username" value="<?= $ambilNama ?>">
    <input type="hidden" id="saldoRekening" value="<?= $saldoRek ?>">

    <!-- Modal Kelola rekening -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Kelola Rekening</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- isi form -->
                <div class="modal-body">
                    <p>No rekening anda : </p>
                    <h5 style="margin-top: -10px; margin-bottom: 13px;"><b><?= $ambilData['no_rek'] ?></b></h5>
                    <p style="margin-bottom: 5px;">Tentukan aksi : </p>
                    <button class="btn btn-info" id="openBtn" data-dismiss="modal">Isi saldo rekening</button>
                    <button class="btn btn-success" id="openBtn4" data-dismiss="modal">Transfer ke akun lain</button>
                </div>

                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Kelola rekening -->

    <!-- Modal dana masuk (ISI SALDO REKENING) -->
    <div class="modal fade" id="myModal2" data-backdrop="static">
        <!-- edit tampilan bagian DANA MASUK, dengan data-backdrop static (latar belakang statik-->
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- edit tampilan DANA MASUK display, background dan position (positionny relative)-->
                <div class="modal-header">
                    <!-- untuk tampilan juga seperti padding, border, align items-->
                    <h5 class="modal-title" id="exampleModalCenterTitle">Dana masuk</h5> <!-- modal-title untuk memberi line height pada tulisan DANA MASUK-->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <!-- edit tampilan button di class close, aria label berfungsi untuk memberikan label dengan dibantu sentuhan css agar pengguna dapat melihatnya. -->
                        <span aria-hidden="true"> &times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <script type="text/javascript" src="js/pisahTitik.js"></script>
                <!--js/pisahTitik.js untuk memberikan titik pada angka ribuan, jutaan atau lebih secara otomatis-->
                <div class="modal-body">
                    <!--edit tampilan bagian tanggal dengan padding, position daln lain-lain-->
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" value="<?= $today ?>" required> <!-- membuat tampilan bagian form tanggalnya di class form-control-->
                    </div>
                    <div class="form-group">
                        <label for="jumlahRek">Jumlah nominal</label>
                        <input type="text" class="form-control" id="jumlahRek" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required> <!-- onkeydown berfungsi untuk membuat aksi pada sebuah elemen ketika user mengisi sebuah nilai-->
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <!-- edit tampilan footer-->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a href="#" class="btn btn-primary tambahRek">Tambah</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal dana masuk -->


    <!-- Modal dana keluar (SALDO DOMPET) -->
    <div class="modal fade" id="myModal3" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Dana keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <script type="text/javascript" src="js/pisahTitik.js"></script>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggalRekOut" value="<?= $today ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlahRekOut">Jumlah nominal</label>
                        <input type="text" class="form-control" id="jumlahRekOut" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a href="#" class="btn btn-primary tambahRekOut">Tambah</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal dana keluar -->

    <!-- Modal history -->
    <div class="modal fade" id="myModal4" data-backdrop="static">
        <!-- edit tampilan bagian History, dengan data-backdrop static (latar belakang statik)-->
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- edit tampilan DANA MASUK display, background dan position (positionnya relative)-->
                <div class="modal-header">
                    <!-- untuk tampilan juga seperti padinng, border, align items-->
                    <h5 class="modal-title" id="exampleModalCenterTitle">Riwayat transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <script type="text/javascript" src="js/pisahTitik.js"></script>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered">
                            <!-- table-sm dsni untuk megatur padding pada tabel. table-striped berfungsi untuk membuat baris tabel berwarna belang-belang.  table-bordered berfungsi untuk membuat tabel yg memiliki garis dan termasuk class tambahan pada bootstrap untuk mendesign tabel.-->
                            <tr>
                                <th>No.</th>
                                <th>Kode transaksi</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                                <th>Tanggal</th>
                            </tr>
                            <?php foreach ($rekeningMasuk as $row) : ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $row['kode'] ?></td>
                                    <td><?= $row['jumlah'] ?></td>
                                    <td><?= $row['aksi'] ?></td>
                                    <td><?= $row['tanggal'] ?></td>
                                </tr>
                                <?php $no++ ?>
                                <!-- NOMOR TBEL BERTAMABAH DARI 1 KE 2 DAN SETERUSNYA-->
                            <?php endforeach; ?>
                            <!--untuk menutup blok kode loop foreach yang dimulai menggunakan sintaks foreach.-->

                            <?php foreach ($rekeningKeluar as $row) : ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $row['kode'] ?></td>
                                    <td><?= $row['jumlah'] ?></td>
                                    <td><?= $row['aksi'] ?></td>
                                    <td><?= $row['tanggal'] ?></td>
                                </tr>
                                <?php $no++ ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal history -->

    <!-- Modal transfer -->
    <div class="modal fade" id="myModal5" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jumlahRekOut">No rekening</label>
                        <input type="text" class="form-control" id="no_rek" required>
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a href="#" class="btn btn-primary tambah_norek">Cari</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal transfer -->

    <!-- banyak modal, untuk memanggil misalnya isi saldo rekening ketika diklik muncul form dana masuk -->
    <script>
        $('#openBtn').click(function() {
            $('#myModal2').modal({
                show: true
            });
        })
        $('#openBtn2').click(function() {
            $('#myModal3').modal({
                show: true
            });
        })
        $('#openBtn3').click(function() {
            $('#myModal4').modal({
                show: true
            });
        })
        $('#openBtn4').click(function() {
            $('#myModal5').modal({
                show: true
            });
        })
    </script>

    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: ["Saldo masuk", "Saldo keluar"],
                datasets: [{
                    label: 'Data rekening',
                    data: [
                        <?= $totalRekIn ?>,
                        <?= $totalRekOut ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132)',
                        'rgba(54, 162, 235)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
    </script>

    <script src="js/bootstrap.js"></script>
    <!--untuk tampilan card yg ada didashboard-->
    <script src="js/kirimNoRek.js"></script>
    <!--untuk transfer masukkan nomor rekening-->
    <script src="ajax/js/tambahRekeningIn.js"></script>
    <!--untuk memberikan peringatan keterangan dan jumlah saldo harus diisi-->
    <script src="ajax/js/tambahRekeningOut.js"></script>
    <!--untuk memberikan peringatan keterangan dan jumlah saldo harus diisi-->
</body>

</html>