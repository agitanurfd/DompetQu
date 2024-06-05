<?php
$koneksi = mysqli_connect('localhost', 'root', '', 'dompet-qu'); //mysqli_connect digunakan untuk membuka koneksi baru ke server mySQL.
if (mysqli_connect_error() == true) {
    die('Gagal terhubung ke database');
    return false;
} else {
    return true;
}

function query($query)
{ //Query berfungsi untuk mengakses dan menampilkan data pada sistem database.
    global $koneksi;
    $result = mysqli_query($koneksi, $query); //Mysqli_query berfungsi untuk menjalankan instruksi ke mysql.
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) { //Mysqli_fetch_assoc berfungsi untuk mengambil data yang berhubungan dengan mysqli.
        $rows[] = $row;
    }
    return $rows;
}

// tambah data Pemasukkan
function tambahMasuk($dataMasuk)
{ //function itu untuk melakukan beberapa pemrosesan dan mengembalikan nilai.
    global $koneksi;
    $tanggalMasuk = htmlspecialchars($dataMasuk["tanggal"]); //htmlspecialchars digunakan untuk mengubah beberapa karakter yang telah ditentukan menjadi entitas.
    $keteranganMasuk = htmlspecialchars($dataMasuk["keterangan"]); //Fungsi htmlspecialchars() akan mengkonversi 4 karakter 'khusus' HTML menjadi named entity sehingga tidak akan di 'proses' oleh web browser. Keempat karakter tersebut adalah: <, >, & dan “. Keempat karakter khusus inilah yang membuat web browser akan menerjemahkan sebuah string menjadi kode HTML/JavaScript.
    $sumber = htmlspecialchars($dataMasuk["sumber"]);
    $jumlah = htmlspecialchars($dataMasuk["jumlah"]);
    $username = $dataMasuk["username"];

    // query insert data
    $query = "INSERT INTO pemasukkan (id, tanggal, keterangan, sumber, jumlah, username) VALUES (NULL, '$tanggalMasuk', '$keteranganMasuk', '$sumber', '$jumlah', '$username')";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// tambah data Pengeluaran
function tambahKeluar($dataKeluar)
{
    global $koneksi;
    $tanggalKeluar = htmlspecialchars($dataKeluar["tanggal"]);
    $keteranganKeluar = htmlspecialchars($dataKeluar["keterangan"]);
    $keperluan = htmlspecialchars($dataKeluar["keperluan"]);
    $jumlah = htmlspecialchars($dataKeluar["jumlah"]);
    $username = $dataKeluar["username"];

    // query insert data
    $query = "INSERT INTO pengeluaran (id, tanggal, keterangan, keperluan, jumlah, username) VALUES (NULL, '$tanggalKeluar', '$keteranganKeluar', '$keperluan', '$jumlah', '$username')";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi); //Pada mysqli_affected_rows digunakan untuk mengetahui jumlah baris tabel yang terdapat oleh proses dari query MySQL
}

// tanggal indonesia
function tgl_indo($tgl)
{
    $tanggal = substr($tgl, 8, 2);
    //Substr berfungsi untuk memotong atau mengambil Sebagian string.
    $nama_bulan = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
    $bulan = $nama_bulan[substr($tgl, 5, 2) - 1];
    $tahun = substr($tgl, 0, 4);

    return $tanggal . '-' . $bulan . '-' . $tahun;
}

// fungsi transfer
function transfer($dataTransfer)
{
    global $koneksi;
    $username = $dataTransfer['username']; // untuk rekening masuk
    $username2 = $dataTransfer['username2']; //untuk rekening keluar
    $tanggal = $dataTransfer['tanggal'];
    $saldoRekening = $dataTransfer['saldoRekening'];
    $jumlah = htmlspecialchars($dataTransfer['jumlah']);
    $jumlahConvert = str_replace('.', '', $jumlah); //str_replace digunakan untuk menggantikan beberapa karakter dengan beberapa karakter lain dalam sebuah string.

    if ($jumlahConvert > $saldoRekening) {
        //Pada echo digunakan untuk menampilkan teks ke layar
        echo " 
                <script>
                    alert('Maaf, saldo anda tidak cukup!');
                </script>
                ";
        return false;
    }
    // query insert data
    $query = "INSERT INTO rekening_masuk(jumlah, tanggal, username) VALUES('$jumlah', '$tanggal', '$username')";
    $query2 = "INSERT INTO rekening_keluar(jumlah, tanggal, username) VALUES('$jumlah', '$tanggal', '$username2')";
    mysqli_query($koneksi, $query);
    mysqli_query($koneksi, $query2);

    return mysqli_affected_rows($koneksi);
}
