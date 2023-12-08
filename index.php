<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Connexion/Inscription</title>
</head>
<body>

        <nav>
        <?php if(isset($_SESSION['auth'])): ?>
            <a href="./admin/logout.php" class="button-connect">Deconnexion</a>
        <ul>
        <li>
            <a href="./admin/profile.php"><img src="./assets/medias/utilisateur.png" alt="Profile"></a>
            <ul>
                <li><a href="#">Mon compte</a></li>
                <li><a href="#">Mes commandes</a></li>
                <li><a href="#">Paramètre</a></li>
            </ul>
        </li>
        <li><a href="#">Contact</a></li>
    </ul>
            <?php else: ?>
                <a href="./admin/login.php" class="button-connect">Connexion</a>
        <?php endif; ?>
    </nav>

</body>
</html>