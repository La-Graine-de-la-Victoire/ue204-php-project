<?php

/**
 * Print debug information
 *
 * @param $variable
 * @return void
 */
function debug($variable){
    echo '<pre>' . print_r($variable, true) . '</pre>';
}

/**
 * Return random string from latin alphabet
 *
 * @param $length
 * @return string
 */
function str_random($length){
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

/**
 * Check if user is logged in
 *
 * @return void
 */
function logged_only(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['auth'])){
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
        header('Location: login.php');
        exit();
    }
}

/**
 * Reconnect user from session saved in cookies
 * @return void
 */
function reconnect_from_cookie(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    // Check if cookie is set
    if(isset($_COOKIE['remember']) && !isset($_SESSION['auth']) ){
        require_once 'dabaseDriver.php';
        if(!isset($pdo)){
            global $pdo;
        }
        // Search user by remember token
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$user_id]);
        $user = $req->fetch();

        // User found
        if($user){
            // ratonlaveurs = security key
            $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs');
            if($expected == $remember_token){
                session_start();
                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
            } else{
                setcookie('remember', null, -1);
            }
        }else{
            setcookie('remember', null, -1);
        }
    }
}

/**
 * Redirect visitor to specified url
 *
 * @param $url
 * @param $message
 * @return void
 */
function redirect($url, $message){
    $_SESSION['message'] = $message;
    header('Location :'.$url);
    exit();
}

?>