<?php
/*
TAHAPAN
------------------------------------------
MEMPERSIAPKAN HALAMAN WEB
- buka file includes/class.php
- pada baris 61 tertulis

    <head><title>Test</title></head>

  ubah menjadi

    <head>
        <title>Test</title>

        <link rel="stylesheet" href="fonts/material-icons/material-icons.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/propeller.min.css">

        <link rel="stylesheet" href="js/components/datetimepicker/css/bootstrap-datetimepicker.css">
        <link rel="stylesheet" href="js/components/datetimepicker/css/pmd-datetimepicker.css">
        <link rel="stylesheet" href="js/components/select2/css/select2.min.css">
        <link rel="stylesheet" href="js/components/select2/css/select2-bootstrap.css">
        <link rel="stylesheet" href="js/components/select2/css/pmd-select2.css">

        <link rel="stylesheet" href="css/propeller-admin.css">
    </head>

  perhatikan bahwa posisi tag <body> saat ini berada pada baris 76
- buka http://localhost/aplikasi/
  perhatikan bahwa tampilan berubah dengan warna latar belakang dan font berbeda
- pada baris 80 tertulis

       </body>

  ubah menjadi

        <script src="js/jquery-1.12.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/propeller.min.js"></script>

        <script src="js/components/datetimepicker/js/moment-with-locales.js"></script>
        <script src="js/components/datetimepicker/js/locale/id.js"></script>
        <script src="js/components/datetimepicker/js/bootstrap-datetimepicker.js"></script>
        <script src="js/components/select2/js/select2.full.js"></script>
        <script src="js/components/select2/js/pmd-select2.js"></script>
    </body>

- TUGAS:
  perhatikan bahwa file id.js tidak tersedia disini
  silahkan ambil file tersebut dari github-nya library momentjs. temukan folder locale dengan file id.js

- TUGAS:
  buka pmd-admin-template-1.0.0/login.html
  kopikan *bagian yang diperlukan untuk form login*. taruh di beranda.php
  ubah form agar disubmit ke act=Pengguna&task=login

- TUGAS:
  buatkan file baru bernama pengguna.php di dalam folder acts/
  buat class Pengguna di dalam file tersebut. 
  pastikan controllernya memiliki function login yang akan memanggil function login di model.
  pada model, di dalam function login, cantumkan
    print_r($_REQUEST);

- TUGAS:
  buka phpmyadmin, database akademik, tabel pengguna
  masukkan pengguna admin, password 12345 dengan level akses Administrator

- TUGAS:
  tes halaman login, masukkan username dan password.
  apakah berhasil menampilkan request tsb?
*/
?>