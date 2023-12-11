<?php
if(isset($_GET['id']) && isset($_GET['token'])){
    require_once '../utils/dabaseDriver.php';
    require_once '../utils/functions.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND resetToken IS NOT NULL AND resetToken = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $req->execute([$_GET['id'], $_GET['token']]);
    $user = $req->fetch();
    if($user){
        if(!empty($_POST)){
            if(!empty($_POST['password']) && $_POST['password'] == $_POST['password_confirm']){
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $pdo->prepare('UPDATE users SET password = ?, resetAt = ?, resetToken = NULL')->execute([$password, new \DateTime()]);
                session_start();
                $_SESSION['flash']['success'] = 'Votre mot de passe a bien été modifié';
                $_SESSION['auth'] = $user;
                header('Location: account.php');
                exit();
            }
        }
    }else{
        session_start();
        $_SESSION['flash']['error'] = "Ce token n'est pas valide";
        header('Location: login.php');
        exit();
    }
}else{
    header('Location: login.php');
    exit();
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


    <h1>Réinitialisé mon mot de passe</h1>

<form action="" method="POST">



<div class="form-group">
    <label for="">Mot de passe <a href="forget.php">(Mot de passe oubliée?)</a></label>
    <input type="password" name="password" />
</div>

<div class="form-group">
    <label for="">Confirmation du mot de passe</label>
    <input type="password" name="password_confirm" />
</div>

<button type="submit">Réinitialisé mon mot de passe</button>

</form>
</body>
</html>
