<?php
require_once '../utils/security/AdminSecurity.php';
notAdminRedirection();

require_once '../utils/dbUtilities.php';
$counter = new dbUtilities();

include "../elements/adminTop.php";
?>

<div class="admin-body">
    <section class="admin-cards-box">
        <div class="column">
            <div class="row jc-sa card-line">
                <div class="admin-card" id="countOfUsers">
                    <div class="card-title">
                        <p><?php echo $counter->countUsers(); ?></p>
                    </div>
                    <div class="card-body">
                        <p>Utilisateurs enregistrés</p>
                    </div>
                    <div class="card-footer">
                        <a href="/admin/user/list.php">Administrer</a>
                    </div>
                </div>
                <div class="admin-card"id="countOfProducts">
                    <div class="card-title">
                        <p><?php echo $counter->countProducts(); ?></p>
                    </div>
                    <div class="card-body">
                        <p>Produits enregistrés</p>
                    </div>
                    <div class="card-footer">
                        <a href="/admin/product/list.php">Administrer</a>
                    </div>
                </div>
            </div>
            <div class="row jc-sa card-line">
                <div class="admin-card" id="countOfOrders">
                    <div class="card-title">
                        <p><?php echo $counter->countNotFinishedOrders() ?></p>
                    </div>
                    <div class="card-body">
                        <p>Commandes non-abouties</p>
                    </div>
                    <div class="card-footer">
                        <a href="/admin/order/list.php">Gérer</a>
                    </div>
                </div>
                <div class="admin-card" id="countOfBaskets">
                    <div class="card-title">
                        <p><?php echo $counter->countNotConfirmedBaskets() ?></p>
                    </div>
                    <div class="card-body">
                        <p>paniers non-confirmés</p>
                    </div>
                    <div class="card-footer">
                        <a href="/admin/order/list.php">Visualiser</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include "../elements/adminFooter.php";
