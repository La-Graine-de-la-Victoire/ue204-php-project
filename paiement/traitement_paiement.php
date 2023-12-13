<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['mode'])) {
    $mode_paiement = $_GET['mode'];

    if ($mode_paiement === 'credit-card') {
        // Formulaire pour les coordonnées de la carte bancaire
        echo '<h1>Formulaire de paiement par Carte Bancaire</h1>';
        // Ajoutez ici les champs nécessaires pour les coordonnées de la carte bancaire
        header ('Location: formulaire_carte_credit.php');
        exit;
    } elseif ($mode_paiement === 'paypal') {
        // Formulaire pour PayPal
        echo '<h1>Formulaire de paiement par PayPal</h1>';
        // Ajoutez ici les champs nécessaires pour PayPal
        header ('Location: formulaire_paypal.php');
        exit;
    } else {
        // Gérer d'autres modes de paiement si nécessaire
        echo 'Mode de paiement non pris en charge.';
        
    }
}
?>
