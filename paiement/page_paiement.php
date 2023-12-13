<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Choix du mode de paiement</title>
</head>
<body>
    <div class="container">
        <div class="payment-option" id="credit-card">
            <a href="traitement_paiement.php?mode=credit-card">
                <div class="option-content">
                    <img src="../assets/images/carte-de-credit.png" alt="Carte Bancaire">
                    <p>Carte Bancaire</p>
                </div>
            </a>
        </div>
        <div class="payment-option" id="paypal">
            <a href="traitement_paiement.php?mode=paypal">
                <div class="option-content">
                    <img src="../assets/images/pay-pal.png" alt="PayPal">
                    <p>PayPal</p>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
