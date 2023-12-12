<?php
require_once '../../utils/security/AdminSecurity.php';
notAdminRedirection();

include "../../elements/adminTop.php";
?>

    <div class="admin-body">
        <table id="__ordersList">
            <thead>
            <tr>
                <th>Numéro d'identification</th>
                <th>Nombre d'articles</th>
                <th>Client</th>
                <th>Date d'ouverture</th>
                <th>Prix total (€)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <script src="/assets/js/admin/lists.js"></script>
    <script type="text/javascript">
        <?php
            if (array_key_exists('mode', $_GET) && $_GET['mode'] == 'baskets') {
                echo 'ordersList("&mode=baskets")';
            } else {
                echo 'ordersList()';
            }
        ?>
    </script>

<?php
include "../../elements/adminFooter.php";
