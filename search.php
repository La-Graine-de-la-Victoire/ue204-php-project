<?php
require_once 'utils/dabaseDriver.php';
global $pdo;

$name = htmlspecialchars($_POST['__name']);
$price = htmlspecialchars($_POST['__price']);
$maxAge = intval(htmlspecialchars($_POST['__maxAge']));

if (empty($name) && empty($price) && empty($maxAge)) {
    header('Location: /products.php');
}

$sqlRequest = 'SELECT * FROM products WHERE 1';

if (!empty($name)) {
    $sqlRequest.= " AND name LIKE :name OR description LIKE :name";
}
if (!empty($price) && is_numeric($price)) {
    $sqlRequest.= " AND id IN (SELECT id FROM productsMeta WHERE price <= :price)";
} else {
    $sqlRequest.= " AND id IN (SELECT id FROM productsMeta)";
}
if (!empty($maxAge)) {
    $sqlRequest.= " AND recommendedAge <= :name";
}

$query = $pdo->prepare($sqlRequest);

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
            $result[$key]['price'] = $meta['price'];
        }
    }
}

header('Location: /products.php?results='.urlencode(json_encode($result)));