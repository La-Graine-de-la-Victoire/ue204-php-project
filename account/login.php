<?php
require_once '../utils/functions.php';
reconnect_from_cookie();
if(!empty($_POST) && !empty($_POST['nom']) && !empty($_POST['password'])){
    require_once '../utils/dabaseDriver.php';
    $req = $pdo->prepare('SELECT * FROM utilisateurs WHERE (nom = :nom OR email = :nom) AND confirmed_at IS NOT NULL');
    $req->execute(['nom' => $_POST['nom']]);
    $user = $req->fetch();
    if($user == null){
        $_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
    }elseif(password_verify($_POST['password'], $user->password)){
        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';

// dans la partie login 
if($_POST['remember']){
    $remember_token = str_random(250);
    $pdo->prepare('UPDATE utilisateurs SET remember_token = ? WHERE id = ?')->execute([$remember_token, $user->id]);
    setcookie('remember', $user->id . '==' . $remember_token . sha1($user->id . 'ratonlaveurs'), time() + 60 * 60 * 24 * 7);
}

if($user->type == 'admin'){
    header('Location: index.php');
        exit();
}else{
    header('Location: ../index.php');
        exit();
}
        
    }else{
        $_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Connexion</title>
</head>
<body class="body_connexion">

   <!--Lorsque l'on réappuis sur le lien de confirmation recu par mail on a un message nous disant que le token n'est plus valide.-->

    <?php if(isset($_SESSION['flash'])): ?>

    <?php foreach($_SESSION['flash'] as $type => $message): ?>
    <div class="alert alert-<?= $type; ?>">
        <?= $message; ?>
            </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>


    <div id="container_connexion">

    <form class="formulaire_connexion" action="" method="post" name="login">

<h1 class="titre_connexion">Connexion</h1>

    <label for="nom">Email</label><br>
    <input type="text" name="nom" id="nom" placeholder="Entrez votre email" required/>

    <label for="password" class="box-register">Mot de passe<a href="forget.php">(Mot de passe oubliée?)</a></label><br>
    <input type="password" name="password" id="password" placeholder="Mot de passe" required/>

<div class="form-group">
    <label>
        <input type="checkbox" name="remember" value="1"/> Se souvenir de moi
    </label>
</div>

<button type="submit" name="submit" id="connecter">Se connecter</button>

<p class="box-register">Vous êtes nouveau ici? 
  <a href="/account/register.php">S'inscrire</a>
</p>

</form>
</body>
</html>

