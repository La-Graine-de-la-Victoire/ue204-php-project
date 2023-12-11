<?php
session_start();
?>

<?php

require_once '../utils/functions.php';

if(isset($_POST['forminscription'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $password = sha1($_POST['password']);
    $password_confirm = sha1($_POST['password_confirm']);

if(!empty($_POST)){

    $errors = array();
    require_once '../utils/dabaseDriver.php';

    if(empty($_POST['nom']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['nom'])){
        $errors['nom'] = "Votre pseudo n'est pas valide (alphanumérique)";
    }

    if(empty($_POST['prenom']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['prenom'])){
        $errors['prenom'] = "Votre pseudo n'est pas valide (alphanumérique)";
    }

    if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = "Votre email n'est pas valide";
    } else {
        $req = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = ?');
        $req->execute([$_POST['email']]);
        $user = $req->fetch();
        if($user){
            $errors['email'] = 'Cet email est déjà utilisé pour un autre compte';
        }
    }

    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
        $errors['password'] = "Votre devez rentrer un mot de passe valide";
    }

    if(empty($errors)){
    require_once '../utils/dabaseDriver.php';
    $req = $pdo->prepare("INSERT INTO utilisateurs SET nom = ?, prenom = ?, password = ?, email = ?, type = 'user', confirmation_token = ?");
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $token = str_random(60);
    $req->execute(array($nom, $prenom, $password, $email, $token));
    // On envoit l'email de confirmation
    $user_id = $pdo->lastInsertId();
    $header="MIME-Version: 1.0\r\n";
    $header.='From:"[JoueTopia]"<JoueTopia@gmail.com>'."\n";
    $header.='Content-Type:text/html; charset="uft-8"'."\n";
    $header.='Content-Transfer-Encoding: 8bit';
    $message = '
    <html lang="fr">
    <body>
    <h1 align="center">LOGO</h1>
    <p>
    Bonjour '.$prenom.', <br>

    Merci pour votre inscription.<br>
    Votre compte chez nous sera activé dés que vous aurez appuyer sur le lien ci-dessous:
    </p>
    <div align="center">
    <a href="http://localhost/UE_L204/code/admin/confirm.php?id='.$user_id.'&token='.$token.'">Confirmez votre compte !</a>
    </div>
    <p></p>
    </body>
    </html>
    ';
    mail($email, "Confirmation de compte", $message, $header);
    $_SESSION['flash']['success'] = 'Un email de confirmation vous a été envoyé pour valider votre compte';
    header('Location: login.php');
    exit();
    }

    
}
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Inscription</title>
</head>
<body class="body_connexion">
    <!--<nav>
        <?php //if(isset($_SESSION['auth'])): ?>
            <a href="logout.php">Deconnexion</a>
            <?php //else: ?>
        <a href="register.php">Inscription</a>
        <a href="login.php">Connexion</a>
        <?php //endif; ?>
    </nav>-->

    <div id="container_connexion">
    <form class="formulaire_connexion" action="" method="post">
    <h1 class="titre_connexion">S'inscrire</h1>

    <?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <p>Vous n'avez pas rempli le formulaire correctement</p>
        <ul>
            <?php foreach($errors as $error):?>
                <li><?= $error; ?></li>
                <?php endforeach; ?>
    </ul>
            </div>
            <?php endif; ?>


    <div class="app-form-group">
        <label for="">Nom</label>
        <input type="text" name="nom" placeholder="Entrez votre nom" required />
    </div>

    <div class="app-form-group">
        <label for="">Prènom</label>
        <input type="text" name="prenom" placeholder="Entrez votre prènom" required />
    </div>

    <div class="app-form-group">
        <label for="">Email</label>
        <input type="email" name="email" placeholder="Votre email" required/>
    </div>


    <div class="app-form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="password" placeholder="Mot de passe" required/>
    </div>

    <div class="app-form-group">
        <label for="">Confirmation du mot de passe</label>
        <input type="password" name="password_confirm" placeholder="Confirmez votre mot de passe" required/>
    </div>

    <button type="submit" name="forminscription" id="connecter" class="app-form-button">S'inscrire</button>

    <p class="box-register">Déjà inscrit? 
     <a href="login.php">Connectez-vous ici</a></p>

</form>

</body>
</html>