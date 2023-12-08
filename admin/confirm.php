<?php
$user_id = $_GET['id'];
$token = $_GET['token'];
require_once 'connexion_BD.php';
$req = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
$req->execute([$user_id]);
$user = $req->fetch();


if($user && $user->confirmation_token == $token ){
    session_start();
    $pdo->prepare('UPDATE utilisateurs SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?')->execute([$user_id]);
    $_SESSION['flash']['success'] = 'Votre compte a bien été validé';
    $_SESSION['auth'] = $user;
    header('Location: ../index.php');
}else{
    $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
    header('Location: login.php');
}?>