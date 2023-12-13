<?php
    session_start();

    // Vérifier si l'utilisateur est connecté
    if(isset($_SESSION['auth'])) {
        echo '<h1>Votre panier</h1>';
        // Afficher les produits dans le panier, cela dépend de la façon dont vous stockez les produits dans la session
        // Vous pourriez avoir un tableau $_SESSION['panier'] contenant les détails des produits ajoutés
        // Par exemple, $_SESSION['panier'] = array(array('id' => 1, 'name' => 'Produit 1', 'price' => 10), ...);
        // Affichez ensuite les produits en parcourant ce tableau
        if(isset($_SESSION['panier'])) {
            foreach($_SESSION['panier'] as $produit) {
                echo '<p>' . $produit['name'] . ' - Prix: $' . $produit['price'] . '</p>';
            }
        } else {
            echo '<p>Votre panier est vide.</p>';
        }
    } else {
        // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
        header('Location: ../account/login.php');
        exit();
    }
?>

