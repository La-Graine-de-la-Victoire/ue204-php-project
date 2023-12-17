<?php
    // Load configuration
    require_once dirname(__DIR__).'/config.php';

    // If the configuration port is not set, set it to 3306 (default) => empty
    // Else apply configuration port
    if (empty(DB_PORT)) {
        $__port = '';
    } else {
        $__port = ' port= '.DB_PORT . ' ; ';
    }

    // Init connection to database
    $pdo = new PDO('mysql:host='.DB_HOST.'; '.$__port.' dbname='.DB_NAME, DB_USER, DB_PASS);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

?>