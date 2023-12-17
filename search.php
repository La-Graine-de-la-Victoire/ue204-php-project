<?php
require_once 'utils/dabaseDriver.php';
global $pdo;

// Secure data
$name = htmlspecialchars($_POST['__name']);
$price = htmlspecialchars($_POST['__price']);
$maxAge = intval(htmlspecialchars($_POST['__maxAge']));

// If all data are empty, redirect to products page without search mode
if (empty($name) && empty($price) && empty($maxAge)) {
    header('Location: /products.php');
}

$sqlRequest = 'SELECT * FROM products WHERE 1';

// Search by name or description containing word
if (!empty($name)) {
    $sqlRequest.= " AND name LIKE CONCAT('%', :name, '%') OR description LIKE CONCAT('%', :name, '%')";
}

// Search by price
if (!empty($price) && is_numeric($price)) {
    $sqlRequest.= " AND id IN (SELECT id FROM productsMeta WHERE price <= :price)";
} else {
    // Price not requested : get meta by product id only
    $sqlRequest.= " AND id IN (SELECT id FROM productsMeta)";
}

if (!empty($maxAge)) {
    // Search by recommended age max
    $sqlRequest.= " AND recommendedAge <= :name";
}

$query = $pdo->prepare($sqlRequest);

//
// Register search parameters
if (!empty($name)) {
    $query->bindValue(':name', $name);
}
if (!empty($price) && is_numeric($price)) {
    $query->bindValue(':price', $price, PDO::PARAM_INT);
}
if (!empty($maxAge)) {
    $query->bindValue(':name', $maxAge, PDO::PARAM_INT);
}

$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);

// Get price for all
$sqlRequest = 'SELECT * FROM productsMeta';
$query = $pdo->prepare($sqlRequest);
$query->execute();
$resultMeta = $query->fetchAll(PDO::FETCH_ASSOC);

$products = [];

foreach ($result as $key => $product) {
    foreach ($resultMeta as $meta) {
        if ($meta['id'] == $product['id']) {
            // Add price to product information without build another array
            $result[$key]['price'] = $meta['price'];
        }
    }
}

// Sort products by search criteria
header('Location: /products.php?results='.urlencode(json_encode($result)));