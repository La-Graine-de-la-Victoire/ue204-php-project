<?php
session_start();
setcookie('remember', NULL, -1);
unset($_SESSION['auth']);
unset($_SESSION['flash']);
$_SESSION['flash']['success'] = 'Vous êtes maintenant déconnecté';
header('Location: /account/login.php');
?>