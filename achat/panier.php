<?php
session_start();

if (!array_key_exists('auth', $_SESSION)) {
    header('Location: /account/login.php');
    exit();
}

require_once '../controllers/ClientOrderController.php';
require_once '../utils/imageUtils.php';
require_once '../utils/security/HtmlMessage.php';
$order = new ClientOrderController();
$currentOrder = $order->getCurrentClientOrder();
?>

<?php include '../elements/head.php';?>
<?php include '../elements/header.php';?>
    <div class="express-messages">
        <?php
        if (array_key_exists('response', $_GET)) {
            if ($_GET['response'] == 'product-added') {
                HtmlMessage::successMessage('Le produit a bien été ajouté à votre panier !', '');
            } else if ($_GET['response'] == 'product-not-found') {
                HtmlMessage::errorMessage('Le produit n\'existe pas !', '');
            } else if ($_GET['response'] == 'product-stock-error') {
                HtmlMessage::errorMessage('Le produit n\'est plus en stock !', '');
            } else if ($_GET['response'] == 'article-removed') {
                HtmlMessage::successMessage('Le produit a bien été retiré de votre panier !', '');
            } else if ($_GET['response'] == 'article-remove-not-found') {
                HtmlMessage::errorMessage('Le produit n\'existe pas dans votre panier!', '');
            }
        } else if (array_key_exists('result', $_GET)) {
            $data = json_decode($_GET['result'], true);
            if (count($data) > 0) {
                foreach ($data as $error) {
                    HtmlMessage::errorMessage($error, '');
                }
            } else {
                HtmlMessage::successMessage('Votre achat est bien confirmé', '');
            }
        }
        ?>
    </div>
    <div class="row body-title">
        <h2>Mon panier</h2>
    </div>

    <div class="basket-container">
        <?php
        if ($currentOrder['count'] == 0) {
            ?>
            <div class="empty-basket">
                <p>Votre panier est vide</p>
            </div>
            <?php
        } else {
            ?>
            <div class="basket-title">
                <h3 class="basket-count">Votre panier comporte <span class="basket-count-n"><?php echo $currentOrder['count']?> articles</span></h3>
            </div>
            <div class="basket-products">
                <?php
                foreach ($currentOrder['products'] as $key => $product) {
                    ?>
                    <div class="basket-product">
                        <div class="product-image">
                            <?php
                            showImage($product['data']['image'] ?? '', 'Représentation du produit', 'product-image');
                            ?>
                        </div>
                        <div class="product-title">
                            <h4><?php echo $product['data']['name'] ?></h4>
                        </div>
                        <div class="product-description">
                            <div class="product-description-line">
                                <span>Description du produit :</span>
                            </div>
                            <div class="product-description-line">
                                <p>
                                    <?php echo $product['data']['description']?>
                                </p>
                            </div>
                        </div>
                        <div class="product-actions">
                            <div class="product-description-line product-price-line">
                                <span>Quantité :</span><span><?php echo $currentOrder['items'][$key][1]?></span>
                            </div>
                            <div class="product-description-line">
                                <a href="/achat/edit.php?mode=1&basket=1&id=<?php echo $currentOrder['items'][$key][0] ?>">-</a>
                                <a href="/achat/edit.php?mode=0&basket=1&id=<?php echo $currentOrder['items'][$key][0] ?>">+</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="total-price">
                <div class="total-price-line">
                    <span>Total : </span>
                </div>
                <div class="total-price-line">
                    <p>
                        <?php echo $currentOrder['order']['totalPrice']?> €
                    </p>
                </div>
            </div>
            <div class="pay-form form-confirm-order">
                <form action="pay.php" method="post" name="__form">
                    <div class="form-title">
                        <h3>Chosir mon moyen de paiement</h3>
                    </div>
                    <div class="card-mode">
                        <button type="button" class="pay-button" id="__button_bank_card">Carte bancaire</button>
                        <button type="button" class="pay-button" id="__button_paypal">Paypal</button>
                    </div>
                    <div class="pay-form paypal-form" id="__paypal_form">
                        <div class="form-group">
                            <label for="__paypal_email">Email</label>
                            <input type="text" name="__paypal_email" id="__paypal_email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="__paypal_password">Mot de passe</label>
                            <input type="password" name="__paypal_password" id="__paypal_password" placeholder="Mot de passe">
                        </div>
                    </div>
                    <div class="pay-form bank-form" id="__bank_form">
                        <div class="form-group">
                            <label for="__bank_card_number">Numéro de carte bancaire</label>
                            <input type="number" name="__bank_card_number" id="__bank_card_number" placeholder="Numéro de carte bancaire">
                        </div>
                        <div class="form-group">
                            <label for="__bank_card_security">Code de sécurité</label>
                            <input type="number" name="__bank_card_security" id="__bank_card_security" placeholder="Numéro de sécurité">
                        </div>
                        <div class="form-group">
                            <label for="__bank_card_proprietary">Nom et prénom du détenteur de la carte</label>
                            <input type="text" name="__bank_card_proprietary" id="__bank_card_proprietary" placeholder="Nom et prénom du détenteur de la carte">
                        </div>
                    </div>
                    <div class="form-title">
                        <h3>Informations de livraison</h3>
                    </div>
                    <div class="form-group">
                        <label for="__country">Pays</label>
                        <select name="__country" id="__country">
                            <option value="France">France</option>
                            <option value="Allemagne">Allemagne</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="__city">Ville</label>
                        <input type="text" name="__city" id="__city" placeholder="Ville de livraison">
                    </div>
                    <div class="form-group">
                        <label for="__zip">Code postal</label>
                        <input type="number" name="__zip" id="__zip" placeholder="Code postal">
                    </div>
                    <div class="form-group">
                        <label for="__address">Adresse</label>
                        <input type="text" name="__address" id="__address" placeholder="Adresse de livraison">
                    </div>
                    <div class="form-group-submit">
                        <button type="submit" id="__send" class="pay-button" value="paypal">Confirmer et payer</button>
                    </div>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
    <script src="/assets/js/payForm.js"></script>
<?php include '../elements/footer.php';?>