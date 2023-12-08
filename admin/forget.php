<?php

if(!empty($_POST) && !empty($_POST['email'])){
    require_once 'connexion_BD.php';
    require_once 'functions.php';
    $req = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if($user){
        session_start();
        $reset_token = str_random(60);
        $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);
        $_SESSION['flash']['success'] = 'Les instructions du rappel de mot de passe vous ont été envoyées par emails';
        mail($_POST['email'], 'Réinitiatilisation de votre mot de passe', "Afin de réinitialiser votre mot de passe merci de cliquer sur ce lien\n\nhttp://localhost/UE_L204/code/admin/reset.php?id={$user->id}&token=$reset_token");
        header('Location: login.php');
        exit();
    }else{
        $_SESSION['flash']['danger'] = 'Aucun compte ne correspond à cet adresse';
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<nav>
        <?php if(isset($_SESSION['auth'])): ?>
            <a href="logout.php">Deconnexion</a>
            <?php else: ?>
        <a href="register.php">Inscription</a>
        <a href="login.php">Connexion</a>
        <?php endif; ?>
    </nav>

   <!--Lorsque l'on réappuis sur le lien de confirmation recu par mail on a un message nous disant que le token n'est plus valide.-->

    <?php if(isset($_SESSION['flash'])): ?>

    <?php foreach($_SESSION['flash'] as $type => $message): ?>
    <div class="alert alert-<?= $type; ?>">
        <?= $message; ?>
            </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>


    <h1>Mot de passe oublié</h1>

<form action="" method="POST">

<div class="form-group">
    <label for="">Email</label>
    <input type="email" name="email" />
</div>

<button type="submit">Se connecter</button>

</form>
</body>
</html>