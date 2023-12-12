<?php
require_once '../../utils/security/AdminSecurity.php';
notAdminRedirection();

require_once '../../utils/security/HtmlMessage.php';
require_once '../../controllers/admin/AdminProductsController.php';
$productsController = new AdminProductsController();

include "../../elements/adminTop.php";
?>

<div class="admin-body">

    <?php
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
            <form action="/controllers/admin/AdminProductsController.php?add=1" class="form-box" method="post">
                <div class="column">
                    <div class="row form-title-box">
                        <h2>Généralités</h2>
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
                        <textarea name="__productDescription" id="__productDescription" cols="75" rows="10" placeholder>
                             <?php echo $currentProduct['name'] ?>
                        </textarea>
                        <label for="__productDescription">Description du produit</label>
                        <span id="__descriptionLimits">255</span>
                    </div>

                    <div class="form-block">
                        <input type="number" name="__productMinAge" id="__productMinAge" placeholder>
                        <label for="__productMinAge">Âge minimal</label>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <h2>Informations commerciales</h2>
                    </div>
                    <div class="form-block">
                        <input type="number" name="__productPrice" id="__productPrice" placeholder>
                        <label for="__productPrice">Prix de vente</label>
                    </div>
                    <div class="form-block">
                        <input type="number" name="__productStock" id="__productStock" placeholder>
                        <label for="__productStock">Quantité en stock</label>
                    </div>
                </div>
                <div class="row mtop-2 justify-center">
                    <button class="button button-default" id="__add" name="__add" type="submit">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php

include "../../elements/adminFooter.php";