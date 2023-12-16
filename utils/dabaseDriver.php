<?php
    require_once dirname(__DIR__).'/config.php';

    if (empty(DB_PORT)) {
        $__port = '';
    } else {
        $__port = ' port= '.DB_PORT . ' ; ';
    }

    $pdo = new PDO('mysql:host='.DB_HOST.'; '.$__port.' dbname='.DB_NAME, DB_USER, DB_PASS);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

?>