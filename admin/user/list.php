<?php
require_once '../../utils/security/AdminSecurity.php';
notAdminRedirection();

require_once '../../utils/dbUtilities.php';
require_once '../../controllers/admin/AdminUsersController.php';
$counter = new dbUtilities();
$adminController = new AdminUsersController();

include "../../elements/adminTop.php";
?>

<div class="admin-body">
    <table id="__usersList">
        <thead>
            <tr>
                <th>Numéro d'identification</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script src="/assets/js/admin/lists.js"></script>
<script type="text/javascript">usersList()</script>

<?php
include "../../elements/adminFooter.php";
