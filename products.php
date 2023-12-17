<?php
require_once 'controllers/ProductsController.php';
require_once 'controllers/ClientProductController.php';
require_once 'utils/imageUtils.php';
require_once 'utils/security/HtmlMessage.php';

$productsController = new ProductsController();
$clientProductController = new ClientProductController();

if (!array_key_exists('results', $_GET)) {
    $products = $productsController->getProductsList();
} else {
    $products = json_decode($_GET['results'], true);
}
?>

<?php include 'elements/head.php';?>
<?php include 'elements/header.php';?>
<div class="express-messages">
    <?php
    if (array_key_exists('response', $_GET)) {
        if ($_GET['response'] == 'product-added') {
            HtmlMessage::successMessage('Le produit a bien été ajouté à votre panier !', '');
        } else if ($_GET['response'] == 'product-not-found') {
            HtmlMessage::errorMessage('Le produit n\'existe pas !', '');
        } else if ($_GET['response'] == 'product-stock-error') {
            HtmlMessage::errorMessage('Le produit n\'est plus en stock !', '');
        }
    }
    ?>
</div>

<div class="row body-title">
    <h2>Nos jouets</h2>
</div>
<div class="body-content">
    <div class="row" id="products-results">
        <aside class="search-product-box">
            <div class="search-product-box-title">
                <h3>Recherche</h3>
            </div>
            <form action="/search.php" method="post">
                <div class="search-box">
                    <label for="__maxAge" class="product-search-big-label">Age maximal</label>
                    <div class="row">
                        <?php $clientProductController->getAgesSearchValues() ?>
                        <select name="__maxAge" id="__maxAge">
                            <?php foreach ($clientProductController->getAgesSearchValues() as $age) {
                                echo '<option value="'. $age. '">'. $age. '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="search-box">
                    <label for="__name" class="product-search-big-label">Nom / Description</label>
                    <div class="row">
                        <input type="text" placeholder="Nom" name="__name" id="__name">
                    </div>
                </div>
                <div class="search-box">
                    <label for="__price" class="product-search-big-label">Prix max</label>
                    <div class="row">
                        <input type="number" placeholder="Prix max" name="__price" id="__price">
                    </div>
                </div>
                <div class="submit-box">
                    <button type="submit" class="button-search" name="__send" id="__send">Filtrer</button>
                </div>
            </form>
        </aside>
        <div class="products-container">
            <?php
            foreach($products as $product) {
                ?>
                <div class="product-container">
                    <div class="product-image">
                        <?php showImage(!empty($product['image']) ? $product['image'] : '', 'Image de présentation de '.$product['description'], 'product'); ?>
                    </div>
                    <div class="product-info">
                        <h3><?php echo $product['name'] ?></h3>
                        <p class="product-description"><?php echo $product['description'] ?></p>
                        <p class="product-price">Prix: <?php echo $product['price'] ?>€</p>
                        <?php
                            if (array_key_exists('auth', $_SESSION)) {
                                echo '<a href="/achat/edit.php?mode=0&id='.$product['id'].'">Ajouter au panier</a>';
                            } else {
                                echo '<a href="/account/login.php">Ajouter au panier</a>';
                            }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include 'elements/footer.php';?>