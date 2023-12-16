<?php
require_once '../../utils/security/AdminSecurity.php';
require_once '../../utils/security/HtmlMessage.php';
require_once '../../controllers/admin/AdminOrdersController.php';
notAdminRedirection();

include "../../elements/adminTop.php";
?>
<div class="admin-body">
    <?php

    if (!array_key_exists('id', $_GET) || empty($_GET['id'])) {
        HtmlMessage::errorMessage('Vous n\'avez pas sélectionné de commande', '/admin/order/list.php');
        return;
    } else {
        $controller = new AdminOrdersController();
        $product = $controller->getOrderByID(htmlspecialchars($_GET['id']));

        if (!$product) {
            HtmlMessage::errorMessage('Commande inconnue', '/admin/order/list.php');
            return;
        }
    }

    if ($product['status'] == 2) {
        $statusStyle = 'in-progress';
    } else if ($product['status'] == 0) {
        $statusStyle = 'done';
    } else {
        $statusStyle = 'not-confirmed';
    }
    $quantity = 0;
    foreach (json_decode($product['products']) as $sell) {
        $quantity += $sell[1];
    }

    ?>

    <div class="column">
        <div class="row justify-center">
            <span class="box-status box-<?php echo $statusStyle?>"><?php echo $product['statusStr'] ?></span>
        </div>
        <div class="row jc-sa box-master-info">
            <div class="box-info">
                <div class="box-info-title">
                    <h2>Informations importantes</h2>
                </div>
                <div class="box-info-content">
                    <div class="row">
                        <p><strong class="important-text">Numéro de commande : #</strong><span class="response-text"><?php echo $product['id']?></span></p>
                    </div>
                    <div class="row">
                        <p><strong class="important-text">Nombre d'articles : </strong><span class="response-text"><?php echo $quantity ?></span></p>
                    </div>
                    <div class="row">
                        <p><strong class="important-text">Date d'ouverture du panier : </strong><span class="response-text"><?php echo $product['creationDate']?></span></p>
                    </div>
                    <div class="row">
                        <p><strong class="important-text">Date de confirmation du panier : </strong><span class="response-text"><?php echo $product['closeDate']?></span></p>
                    </div>
                </div>
            </div>
            <div class="box-info">
                <div class="box-info-title">
                    <h2>Informations de livraison</h2>
                </div>
                <div class="box-info-content">
                    <div class="row">
                        <p><strong class="important-text">Adresse de livraison : </strong><span class="response-text"><?php echo $product['address']?></span></p>
                    </div>
                </div>
                <div class="box-info-title mtop-2">
                    <h2>Informations de paiement</h2>
                </div>
                <div class="box-info-content">
                    <div class="row">
                        <?php
                            if ($product['payementMode'] == 1) {
                                echo '<p><strong class="important-text">Mode de paiement : </strong><span class="pay-response pay-response-paypal">Paypal</span></p>';
                            } else if ($product['payementMode'] == 2){
                                echo '<p><strong class="important-text">Mode de paiement : </strong><span class="pay-response pay-response-bank">Carte bancaire</span></p>';
                            } else {
                                echo '<p><strong class="important-text">Mode de paiement : </strong><span class="pay-response pay-response-cash">Non payée</span></p>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row jc-sa">
        <?php
            foreach ($product['sells'] as $item => $unit) {
                $elements = json_decode($product['products'], true);
                $closed = false;
                ?>

                <div class="product-result">
                    <div class="product-title">
                        <p><?php echo $unit['name'] ?></p>
                    </div>
                    <div class="product-details">
                        <p><strong>Quantité : </strong> <?php echo  $elements[$item][1] ?></p>
                    </div>
                    <div class="product-details">
                        <p>
                            <strong>Quantité manquante: </strong>
                            <?php echo  $elements[$item][2] ?>
                            <a href="/controllers/admin/AdminOrdersController.php?mode=update&id=<?php echo $product['id'] ?>&removequantity=<?php echo $elements[$item][0] ?>">
                                <i class="fa fa-minus"></i>
                            </a>
                        </p>
                    </div>
                </div>

                <?php
            }
        ?>
    </div>
</div>

<?php
include "../../elements/adminFooter.php";

