<?php

session_start();
if (!array_key_exists('auth', $_SESSION)) {
    header('Location: /account/login.php');
}
require_once '../utils/functions.php';
logged_only();
if(!empty($_POST)){

    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
        $_SESSION['flash']['danger'] = "Les mots de passes ne correspondent pas";
    }else{
        $user_id = $_SESSION['auth']->id;
        $password= password_hash($_POST['password'], PASSWORD_BCRYPT);
        require_once '../utils/dabaseDriver.php';
        $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$password,$user_id]);
        $_SESSION['flash']['success'] = "Votre mot de passe a bien été mis à jour";
    }

}
?>

<?php include '../elements/head.php';?>
<?php include '../elements/header.php';?>

<div class="account-alerts">
    <?php if(isset($_SESSION['flash'])): ?>

        <?php foreach($_SESSION['flash'] as $type => $message): ?>
            <div class="alert alert-<?= $type; ?>">
                <?= $message; ?>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
</div>

<div class="container">

    <div class="account-title">
        <div class="space-col">
            <h1>Bonjour <?= $_SESSION['auth']->firstName; ?></h1>
        </div>
        <div class="space-col">
            <a href="/account/logout.php">Deconnexion</a>
        </div>
    </div>

    <form action="" method="POST" class="account-form">

        <div class="form-group">
            <label for="password">Changer votre mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Changer de mot de passe"/>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirmation du mot de passe</label>
            <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe"/>
        </div>

        <div class="form-submit">
            <button type="submit">Changer mon mot de passe</button>
        </div>

    </form>
</div>

<?php include '../elements/footer.php';?>
