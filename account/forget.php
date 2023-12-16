<?php
session_start();

if(!empty($_POST) && !empty($_POST['email'])){
    require_once '../utils/dabaseDriver.php';
    require_once '../utils/functions.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmationToken IS NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if($user){
        $reset_token = str_random(60);
        $pdo->prepare('UPDATE users SET resetToken = ?, resetAt = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);
        $_SESSION['flash']['success'] = 'Les instructions du rappel de mot de passe vous ont été envoyées par emails';
        try {
            mail(htmlspecialchars($_POST['email']), 'Réinitiatilisation de votre mot de passe', "Afin de réinitialiser votre mot de passe merci de cliquer sur ce lien\n\n".WEBSITE_URL."/account/reset.php?id={$user->id}&token=$reset_token");
        } catch (Exception $e) {
            $_SESSION['flash']['danger'] = 'Impossible d\'envoyer les instructions';
        }
//        header('Location: login.php');
    }else{
        $_SESSION['flash']['danger'] = 'Aucun compte ne correspond à cet adresse';
    }
}


?>


<?php include '../elements/head.php';?>
<?php include '../elements/header.php';?>
    <nav class="forget-box">
        <?php if(isset($_SESSION['auth'])): ?>

        <?php else: ?>
            <a href="/account/register.php">Inscription</a>
            <a href="/account/login.php">Connexion</a>
        <?php endif; ?>
    </nav>

    <!--Lorsque l'on réappuis sur le lien de confirmation recu par mail on a un message nous disant que le token n'est plus valide.-->

    <div class="container_forget">
        <?php if(isset($_SESSION['flash'])): ?>

            <?php foreach($_SESSION['flash'] as $type => $message): ?>
                <div class="alert alert-<?= $type; ?>">
                    <?= $message; ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <form action="#" method="POST" class="form_forget">
            <h1 class="forget-title">Mot de passe oublié</h1>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" />
            </div>

            <div class="form-group">
                <button type="submit" class="forget-submit">Envoyer un mail</button>
            </div>

        </form>
    </div>
<?php include '../elements/footer.php';?>