<?php
$user_id = $_GET['id'];
$token = $_GET['token'];
require_once '../utils/dabaseDriver.php';
$req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$req->execute([$user_id]);
$user = $req->fetch();


if($user && $user->confirmation_token == $token ){
    session_start();
    $pdo->prepare('UPDATE users SET confirmationToken = NULL, confirmedAt = NOW() WHERE id = ?')->execute([$user_id]);
    $_SESSION['flash']['success'] = 'Votre compte a bien été validé';
    $_SESSION['auth'] = $user;
    header('Location: ../index.php');
}else{
    $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
    header('Location: login.php');
}?>