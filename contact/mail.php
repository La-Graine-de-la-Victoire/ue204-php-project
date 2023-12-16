<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Envoi d'un message par formulaire</title>
</head>

<body>
    <?php
    $retour = mail('assiaikerchalene91@gmail.com', 'TEST : Formulaire de contact', $_POST['message'], 'From: assiaikerchalene91@gmail.com');
    if ($retour)
        echo '<p>Votre message a bien été envoyé.</p>';
    ?>
</body>
</html>