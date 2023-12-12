<?php
require_once '../../utils/security/AdminSecurity.php';
notAdminRedirection();

include "../../elements/adminTop.php";
?>

    <div class="admin-body">

        <div class="row justify-right mbottom-5">
            <a href="/admin/product/add.php" class="button button-default">Ajouter un produit</a>
        </div>

        <table id="__productsList">
            <thead>
                <tr>
                    <th>Numéro d'identification</th>
                    <th>Nom</th>
                    <th>Editeur</th>
                    <th>Prix</th>
                    <th>Quantité en stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <script src="/assets/js/admin/lists.js"></script>
    <script type="text/javascript">getProductsList()</script>

<?php
include "../../elements/adminFooter.php";
