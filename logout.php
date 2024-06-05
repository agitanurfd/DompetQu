<?php
session_start(); //untuk memulai ekseskusi session pada server kemudia menyimpannya di browser.
require "function/functions.php"; //require itu kyk butuh, jika file ini tidak ada maka script selanjutnya tidak berjalan.

if (empty($_SESSION['login'])) { //Fungsi empty() digunakan untuk dapat memeriksa apakah suatu variabel kosong atau tidak. session adalah cara untuk menjaga suatu variabel tetap ada selama sesi kunjungan user. Meskipun berpindah-pindah halaman, variabel session tetap ada dan bisa diakses sampai session ditutup.
  header('Location: login'); //bakal balik ke halaman login
  exit;
}

session_unset(); //Session_unset berfungsi untuk menghapus data session di server, dengan nama variabel tertentu saja.
session_destroy(); //File session akan dihapus dari server, maksudnya adalah maka session akan berakhir dan user diminta untuk login kembali.

setcookie('login', '', time() - 3600); //untuk mendefinisikan cookie yang akan dikirim bersama dengan header HTTP lainnya.
setcookie('level', '', time() - 3600);
setcookie('id', '', time() - 3600);
setcookie('key', '', time() - 3600);

header('Location: login');
