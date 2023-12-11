<?php
require_once '../utils/security/AdminSecurity.php';
notAdminRedirection();

require_once '../utils/dbUtilities.php';
$counter = new dbUtilities();

include "../elements/adminTop.php";
?>

<div class="admin-body">
    <section id="admin-home-cards" class="admin-cards-box">
        <div class="admin-card">
            <div class="card-title">
                <?php echo $counter->countUsers(); ?>
            </div>
            <div class="card-body">
                <p>Utilisateurs enregistrés</p>
            </div>
            <div class="card-footer">
                <a href="/admin/user/list.php">Administrer</a>
            </div>
        </div>
        <div class="admin-card">
            <div class="card-title">
                <?php echo $counter->countProducts(); ?>
            </div>
            <div class="card-body">
                <p>Produits enregistrés</p>
            </div>
            <div class="card-footer">
                <a href="/admin/product/list.php">Administrer</a>
            </div>
        </div>
        <div class="admin-card">
            <div class="card-title">
                <?php echo $counter->countNotFinishedOrders() ?>
            </div>
            <div class="card-body">
                <p>Commandes non-abouties</p>
            </div>
            <div class="card-footer">
                <a href="/admin/order/list.php">Gérer</a>
            </div>
        </div>
        <div class="admin-card">
            <div class="card-title">
                <?php echo $counter->countNotConfirmedBaskets() ?>
            </div>
            <div class="card-body">
                <p>paniers non-confirmés</p>
            </div>
            <div class="card-footer">
                <a href="/admin/order/list.php">Visualiser</a>
            </div>
        </div>
    </section>
</div>

<?php
include "../elements/adminFooter.php";
