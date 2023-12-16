<?php

if (array_key_exists('mode', $_GET) && array_key_exists('id', $_GET)) {

    require_once '../controllers/ClientOrderController.php';
    $ordersController = new ClientOrderController();

    if ($_GET['mode'] == 0) {
        $ordersController->addProductToOrder(htmlspecialchars($_GET['id']));
    } else if ($_GET['mode'] == 1) {
        $ordersController->removeProductFromOrder(htmlspecialchars($_GET['id']));
    } else {
        header('Location: /achat/index.php');
        exit();
    }

} else {
    // Not authorized
    header('Location: /');
    exit();
}