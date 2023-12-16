<?php
session_start();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $productId;

    echo json_encode(['message' => 'Produit ajouté au panier avec succès']);
} else {
    echo json_encode(['error' => 'Aucun ID de produit reçu']);
}
?>