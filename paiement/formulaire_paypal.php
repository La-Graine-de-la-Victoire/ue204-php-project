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

            <?php
            // Vérification de l'e-mail après la soumission du formulaire
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST["email"];

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Si toutes les vérifications sont réussies, redirigez vers la page de succès
                    header('Location: page_succes.php');
                    exit();
                    // Ajoutez ici le code de traitement supplémentaire après la validation de l'e-mail
                } else {
                    echo "<p>L'adresse e-mail n'est pas valide. Veuillez entrer une adresse e-mail valide.</p>";
                }
            }
            ?>
            
            <form action="" method="POST">
                <label for="email" class="label_carte">Adresse mail :</label><br>
                <input type="email" id="email" name="email" required class="input_carte"><br><br>

                <button type="submit" class="bouton_carte">Payer</button>
            </form>
        </div>
    </div>
</body>
</html>