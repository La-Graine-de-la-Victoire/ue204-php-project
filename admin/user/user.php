<?php
require_once '../../utils/security/AdminSecurity.php';
notAdminRedirection();

if (!isset($_GET) || !array_key_exists('id', $_GET)) {
    include "../../elements/adminTop.php";
    ?>
    <div class="admin-body">
        <div class="column">
            <div class="row justify-center">
                <div class="admin-alert admin-alert-error">
                    <div class="admin-alert-title">
                        <h2>Erreur !</h2>
                    </div>
                    <div class="admin-alert-content">
                        <p>Aucun utilisateur n'a été sélectionné</p>
                    </div>
                    <div class="admin-alert-footer">
                        <a href="/admin/user/list.php" class="button button-std">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include "../../elements/adminFooter.php";
    return;
}

require_once '../../utils/dbUtilities.php';
require_once '../../controllers/admin/AdminUsersController.php';
$adminController = new AdminUsersController();
$currentUser = $adminController->getUserById(htmlspecialchars($_GET['id']));

include "../../elements/adminTop.php";
?>

<?php
    if (!$currentUser) {
        ?>
        <div class="admin-body">
            <div class="column">
                <div class="row justify-center">
                    <div class="admin-alert admin-alert-error">
                        <div class="admin-alert-title">
                            <h2>Erreur !</h2>
                        </div>
                        <div class="admin-alert-content">
                            <p>Utilisateur introuvable</p>
                        </div>
                        <div class="admin-alert-footer">
                            <a href="/admin/user/list.php" class="button button-std">Retour</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return;
    }
?>

<?php
    if (!array_key_exists('delete', $_GET) || !$_GET['delete'] || !array_key_exists('confirm', $_GET) ||!$_GET['confirm']) {
        ?>
        <div class="admin-body">
            <div class="row">
                <h2><?php echo $currentUser['firstName'] ?> <span class="uppercase"><?php echo $currentUser['lastName'] ?></span></h2>
            </div>
            <div class="column">
                <div class="row">
                    <div class="box-info">
                        <div class="box-info-title">
                            <h3>Informations importantes</h3>
                        </div>
                        <div class="box-info-content">
                            <div class="column interval-box-lines">
                                <div class="row">
                                    <p><strong class="important-text">Identité : </strong> <span class="response-text"><?php echo $currentUser['firstName'].$currentUser['lastName'] ?></span></p>
                                </div>
                                <div class="row">
                                    <p><strong class="important-text">Adresse e-mail : </strong> <span class="response-text"><?php echo $currentUser['email'] ?></span></p>
                                </div>
                                <div class="row">
                                    <p><strong class="important-text">Adresse e-mail : </strong> <span class="response-text"><?php echo $currentUser['email'] ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-info">
                        <div class="box-info-title">
                            <h3>Activités récentes</h3>
                        </div>
                        <div class="box-info-content">
                            <div class="column interval-box-lines">
                                <div class="row">
                                    <p><strong class="important-text">Date de création du compte : </strong> <span class="response-text"><?php echo $currentUser['creationDate'] ?></span></p>
                                </div>
                                <div class="row">
                                    <p><strong class="important-text">Dernière visite : </strong> <span class="response-text"><?php echo $currentUser['lastAccess'] ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <h3>Commandes</h3>
                <div id="__userID" style="display: none"><?php echo $currentUser['id'] ?></div>
                <table  id="__ordersList">
                    <thead>
                        <tr>
                            <th>Nombre de produits</th>
                            <th>Prix total</th>
                            <th>Date d'ouverture</th>
                            <th>Date de clotûre</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <?php
            if ($currentUser['role'] != 'admin') {
                ?>
            <div class="column ai-center mtop-5">
                        <div class="row">
                            <a href="/admin/user/user.php?id='.$currentUser['id'].'&delete=true&confirm=false" class="button button-alert">Supprimer l\'utilisateur</a>
                        </div>
            </div>
            <?php
                }
            ?>
        </div>

        <script src="/assets/js/admin/lists.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                getUserOrders()
            });
        </script>
        <?php
    } else {
?>

        <?php
            if (array_key_exists('confirm', $_GET) && $_GET['confirm'] == 'true' && array_key_exists('delete', $_GET) && $_GET['delete'] == 'true') {
                if ($adminController->deleteUser($currentUser['id']) == null) {
                    ?>
                    <div class="admin-body">
                        <div class="column">
                            <div class="row justify-center">
                                <div class="admin-alert admin-alert-success">
                                    <div class="admin-alert-title">
                                        <h2>Succès !</h2>
                                    </div>
                                    <div class="admin-alert-content">
                                        <p>L'utilisateur a bien été supprimé !</p>
                                    </div>
                                    <div class="admin-alert-footer">
                                        <a href="/admin/user/list.php" class="button button-std">Retour</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="admin-body">
                        <div class="column">
                            <div class="row justify-center">
                                <div class="admin-alert admin-alert-error">
                                    <div class="admin-alert-title">
                                        <h2>Erreur !</h2>
                                    </div>
                                    <div class="admin-alert-content">
                                        <p>L'utilisateur n'a pas pu être supprimé !</p>
                                    </div>
                                    <div class="admin-alert-footer">
                                        <a href="/admin/user/list.php" class="button button-std">Retour</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="admin-body">
                    <div class="column">
                        <div class="row justify-center">
                            <div class="admin-alert admin-alert-warning">
                                <div class="admin-alert-title">
                                    <h2>Cette action nécessite votre attention !</h2>
                                </div>
                                <div class="admin-alert-content">
                                    <p>Voulez-vous supprimer l'utilisateur <strong><?php echo $currentUser['firstName'] . ' ' . $currentUser['lastName'] ?></strong> ?</p>
                                </div>
                                <div class="admin-alert-footer">
                                    <a href="/admin/user/user.php?id=5&delete=true&confirm=true" class="button button-alert">Supprimer</a>
                                    <a href="/admin/user/list.php" class="button button-std">Retour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>

<?php
    }
?>

<?php
include "../../elements/adminFooter.php";
