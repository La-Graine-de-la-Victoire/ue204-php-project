<?php
session_start();
require 'functions.php';
logged_only();
if(!empty($_POST)){

    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
        $_SESSION['flash']['danger'] = "Les mots de passes ne correspondent pas";
    }else{
        $user_id = $_SESSION['auth']->id;
        $password= password_hash($_POST['password'], PASSWORD_BCRYPT);
        require_once 'connexion_BD.php';
        $pdo->prepare('UPDATE utilisateurs SET password = ? WHERE id = ?')->execute([$password,$user_id]);
        $_SESSION['flash']['success'] = "Votre mot de passe a bien été mis à jour";
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

    <?php if(isset($_SESSION['flash'])): ?>

<?php foreach($_SESSION['flash'] as $type => $message): ?>
<div class="alert alert-<?= $type; ?>">
    <?= $message; ?>
        </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>


    <h1>Bonjour <?= $_SESSION['auth']->prenom; ?></h1>

    <form action="" method="POST">

<div class="form-group">
    <label for="">Changer votre mot de passe</label>
    <input type="password" name="password" placeholder="Changer de mot de passe"/>
</div>

<div class="form-group">
    <label for="">Confirmation du mot de passe</label>
    <input type="password" name="password_confirm" placeholder="Confirmez le mot de passe"/>
</div>

<button type="submit">Changer mon mot de passe</button>

</form>
    
</body>
</html>
