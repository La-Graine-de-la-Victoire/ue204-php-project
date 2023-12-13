<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $numero_carte = $_POST['numero_carte'];
    $date_exp = $_POST['date_exp'];
    $cvv = $_POST['cvv'];

    // Vérification du numéro de carte
    if (!preg_match('/^\d{16}$/', $numero_carte)) {
        echo "Le numéro de carte doit avoir 16 chiffres. Veuillez vérifier.";
        exit();
    }

    // Vérification du format de la date d'expiration
    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{4}$/', $date_exp)) {
        echo "Le format de la date d'expiration doit être MM/YYYY. Veuillez vérifier.";
        exit();
    }

    // Vérification du format de la CVV
    if (!preg_match('/^\d{3}$/', $cvv)) {
        echo "La CVV doit être composée de 3 chiffres. Veuillez vérifier.";
        exit();
    }

    // Si toutes les vérifications sont réussies, redirigez vers la page de succès
    header('Location: page_succes.php');
    exit();
}

?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Paiement par Carte Bancaire</title>
</head>
<body class="body_carte">
    <div class="container">
        <div class="payment-form">
            <h1>Paiement par Carte Bancaire</h1>
            <form action="" method="POST">
                <label for="nom" class="label_carte">Nom sur la carte :</label><br>
                <input type="text" id="nom" name="nom" required class="input_carte"><br><br>

                <label for="numero_carte" class="label_carte">Numéro de carte :</label><br>
                <input type="text" id="numero_carte" name="numero_carte" required class="input_carte"><br><br>

                <label for="date_exp" class="label_carte">Date d'expiration :</label><br>
                <input type="text" id="date_exp" name="date_exp" placeholder="MM/YY" required class="input_carte"><br><br>

                <label for="cvv" class="label_carte">CVV :</label><br>
                <input type="text" id="cvv" name="cvv" required class="input_carte"><br><br>

                <button type="submit" class="bouton_carte">Payer</button>
            </form>
        </div>
    </div>
</body>
</html>
