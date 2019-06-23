<?php
    require_once __DIR__.'/includes/config.php';
    require_once __DIR__.'/includes/class.php';
    require_once __DIR__.'/includes/function.php';
    require_once __DIR__.'/libraries/tcpdf/tcpdf.php';

    $app = new Aplikasi($cfg);
    $app->connect();
?>