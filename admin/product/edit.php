<?php
require_once '../../utils/security/AdminSecurity.php';
notAdminRedirection();

require_once '../../utils/security/HtmlMessage.php';
require_once '../../controllers/ProductsController.php';
$productsController = new ProductsController();

include "../../elements/adminTop.php";
?>

<div class="admin-body">

    <?php

    if (array_key_exists('status', $_GET) && array_key_exists('message', $_GET)) {
        HtmlMessage::parseGetMessage();
    }

    if (!array_key_exists('id', $_GET) || empty($_GET['id'])) {
            HtmlMessage::errorMessage('Aucun produit sélectionné !', '/admin/product/list.php');
            return;
        }

        $id = htmlspecialchars($_GET['id']);
        $currentProduct = $productsController->getProductByID($id);

        if (!$currentProduct) {
            HtmlMessage::errorMessage('Le produit choisi est introuvable !', '/admin/product/list.php');
            return;
        }

        if (array_key_exists('delete', $_GET) && array_key_exists('confirm', $_GET)) {

            if ($_GET['delete'] == 1 && $_GET['confirm'] == 1) {
                if ($productsController->delete($id)) {
                    HtmlMessage::successMessage('Le produit a bien été supprimé !', '/admin/product/list.php');
                } else {
                    HtmlMessage::errorMessage('Une erreur est survenue lors de la suppression du produit !', '/admin/product/list.php');
                }
                return;
            } else {
                HtmlMessage::warningMessage(
                    'Voulez-vous supprimer le produit "'.$currentProduct['name'].'" ?',
                    '/admin/product/list.php',
                    '/admin/product/edit.php?id='.$currentProduct['id'].'&delete=1&confirm=1'
                );
                return;
            }

        }
    ?>

    <div class="column">
        <div class="form row justify-center">
            <form action="/controllers/ProductsController.php?update=1" class="form-box" method="post" enctype="multipart/form-data">
                <div class="column">
                    <div class="row form-title-box">
                        <h2>Généralités</h2>
                    </div>

                    <div class="form-block-file">
                        <div class="row">
                            <label for="__productName">Image de présentation</label>
                        </div>
                        <div class="file-selector">
                            <input type="file" name="__productImage" id="__productImage" accept="image/*" placeholder>
                        </div>
                    </div>

                    <div class="form-block">
                        <input type="text" name="__productName" id="__productName" value="<?php echo $currentProduct['name'] ?>" placeholder>
                        <label for="__productName">Nom du produit</label>
                    </div>

                    <div class="form-block">
                        <input type="text" name="__productEditor" id="__productEditor" placeholder value="<?php echo $currentProduct['editor'] ?>">
                        <label for="__productEditor">Producteur / Éditeur du produit</label>
                    </div>

                    <div class="form-block">
                        <textarea name="__productDescription" id="__productDescription" cols="75" rows="10" placeholder><?php echo $currentProduct['name'] ?></textarea>
                        <label for="__productDescription">Description du produit</label>
                        <span id="__descriptionLimits">255</span>
                    </div>

                    <div class="form-block">
                        <input type="number" name="__productMinAge" id="__productMinAge" placeholder value="<?php echo $currentProduct['recommendedAge'] ?>">
                        <label for="__productMinAge">Âge minimal</label>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <h2>Informations commerciales</h2>
                    </div>
                    <div class="form-block">
                        <input type="number" name="__productPrice" id="__productPrice" placeholder value="<?php echo $currentProduct['price'] ?>">
                        <label for="__productPrice">Prix de vente (€)</label>
                    </div>
                    <div class="form-block">
                        <input type="number" name="__productStock" id="__productStock" placeholder value="<?php echo $currentProduct['quantity'] ?>">
                        <label for="__productStock">Quantité en stock</label>
                    </div>
                    <div class="form-block">
                        <input type="hidden" name="__productID" id="__productID" style="display: none" value="<?php echo $currentProduct['id'] ?>">
                    </div>
                </div>
                <div class="row mtop-2 justify-center">
                    <button class="button button-default" id="__add" name="__add" type="submit">Modifier</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php

include "../../elements/adminFooter.php";