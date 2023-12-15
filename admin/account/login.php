<?php
require_once '../utils/functions.php';
reconnect_from_cookie();
$errors = [];

if(!empty($_POST) && !empty($_POST['nom']) && !empty($_POST['password'])){
    require_once '../utils/dabaseDriver.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE (lastName = :nom OR email = :nom)');
    $req->execute(['nom' => $_POST['nom']]);
    $user = $req->fetch();
    if($user == null){
        $errors['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
    }elseif(password_verify($_POST['password'], $user->password)){

        if ($user->confirmationToken == null) {
            $_SESSION['auth'] = $user;
            $errors['flash']['success'] = 'Vous êtes maintenant connecté';
            $pdo->prepare('UPDATE users SET  lastAccess=NOW() WHERE id = ?')->execute([$user->id]);
        } else {
            $errors['flash']['success'] = 'Vous n\'avez pas confirmé votre compte';
        }

// dans la partie login 
        if(isset($_POST['remember'])){
            $remember_token = str_random(250);
            $pdo->prepare('UPDATE users SET rememberToken = ? AND lastAccess=NOW() WHERE id = ?')->execute([$remember_token, $user->id]);
            setcookie('remember', $user->id . '==' . $remember_token . sha1($user->id . 'ratonlaveurs'), time() + 60 * 60 * 24 * 7);
        }

        if (empty($errors) || !array_key_exists('danger', $errors)) {
            if($user->role == 'admin'){
                header('Location: /admin/');
                exit();
            }else{
                header('Location: ../index.php');
                exit();
            }
        }

    }else{
        $errors['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
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

<?php if(isset($errors) && array_key_exists('flash', $errors)) { ?>

<?php foreach($errors['flash'] as $type => $message): ?>
    <div class="alert alert-<?= $type; ?>">
        <?= $message; ?>
    </div>
<?php endforeach; ?>
<?php unset($errors['flash']); ?>
<?php }?>



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

