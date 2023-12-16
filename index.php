<?php include 'elements/head.php';?>
<?php include 'elements/header.php';?>

<?php
require_once './controllers/ProductsController.php';
require_once './utils/imageUtils.php';
$controller = new ProductsController();

$lastProducts = $controller->getLastProducts();
$bestSells = $controller->getBestSells();
?>

<section class="slideshow">
    <div class="slideshow-container">
        <div class="slide">
            <img src="./assets/images/header-un.png" alt="header">
            <div class="slide-text">
                <h1>-20% sur tous nos jouets </h1>
                <p class="accroche">Rejoignez-nous dans l'univers magique de JoueTopia et offrez à vos enfants le plaisir infini du jeu !</p>
            </div>
        </div>
        <div class="slide">
            <img src="./assets/images/header-deux.png" alt="header-deux">
            <div class="slide-text">
                <h2>Cultivez l'épanouissement de votre enfant</h2>
                <p class="accroche">Offrez à votre enfant bien plus qu'un simple jouet : favorisez son éveil, sa créativité et son apprentissage tout en lui offrant des moments inoubliables de bonheur et de découverte !</p>
            </div>
        </div>
        <div class="slide">
            <img src="./assets/images/header-trois.png" alt="header-trois">
            <div class="slide-text">
                <h2>Émerveillez les yeux de vos enfants : offrez-leur des jouets magiques !</h2>
                <p class="accroche">Transformez Noël en un festival de joie et d'émerveillement pour vos enfants en leur offrant des jouets qui feront briller leurs yeux de bonheur et de surprise !</p>
            </div>
        </div>
    </div>
</section>

<section class="jouets-new">
    <h2>Nos dernières nouveautés </h2>
    <div class="articles-container">
        <?php
        foreach ($lastProducts as $product) {
            ?>
            <div class="article">
                <?php showImage(!empty($product['image']) ? $product['image'] : '', 'Image de présentation de '.$product['description'], 'product'); ?>
                <!--                    <div class="rating">-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9734;</span>-->
                <!--                    </div>-->
                <p class="description-product"><?php echo $product['description']?></p>
                <p class="price"><?php echo $product['price']?> €</p>
                <?php
                if (array_key_exists('auth', $_SESSION)) {
                    echo '<a href="/achat/edit.php?mode=0&id='.$product['id'].'" class="button-buy">Ajouter au panier</a>';
                } else {
                    echo '<a href="/account/login.php" class="button-buy">Ajouter au panier</a>';
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</section>

<section class="jouets-new">
    <h2>Nos meilleurs ventes </h2>
    <div class="articles-container">
        <?php
        foreach ($bestSells as $product) {
            ?>
            <div class="article">
                <?php showImage(!empty($product['image']) ? $product['image'] : '', 'Image de présentation de '.$product['description'], 'product'); ?>
                <!--                    <div class="rating">-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9733;</span>-->
                <!--                        <span class="star">&#9734;</span>-->
                <!--                    </div>-->
                <p class="description-product"><?php echo $product['description']?></p>
                <p class="price"><?php echo $product['price']?> €</p>
                <?php
                if (array_key_exists('auth', $_SESSION)) {
                    echo '<a href="/achat/edit.php?mode=0&id='.$product['id'].'" class="button-buy">Ajouter au panier</a>';
                } else {
                    echo '<a href="/account/login.php" class="button-buy">Ajouter au panier</a>';
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</section>


<?php include 'elements/footer.php';?>
