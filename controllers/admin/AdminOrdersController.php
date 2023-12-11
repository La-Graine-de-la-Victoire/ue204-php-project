<?php
require_once '../../utils/dbUtilities.php';
require_once '../../utils/security/AdminSecurity.php';

class AdminOrdersController
{
    private PDO $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    public function getOrdersList() {
        return [];
    }

    public function getOrderByUserId($id) {
        $query = $this->db->prepare("SELECT id, products, creationDate, closeDate, totalPrice, status FROM orders WHERE client = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$result) {
            $result['quantity'] = count(explode(',', $result['products']));
            $result['creationDate'] = date('d/m/Y', strtotime($result['creationDate']));
            $result['closeDate'] = date('d/m/Y', strtotime($result['closeDate']));
            $result['status'] = match ($result['status']) {
                0 => 'Fermée',
                1 => 'Panier non-validé',
                2 => 'Confirmé',
                default => 'Erreur inconnue',
            };
            $result['action'] = '<a href="/admin/order/order.php?orderID='.$result['id'].'" class="table-btn table-btn-std">Visualiser</a>';
        }

        return $results;
    }

}

if (isset($_GET) && isAdmin()) {
    if (array_key_exists('getList', $_GET)) {
        $controller = new AdminOrdersController();

        if (array_key_exists('user', $_GET)) {
            echo json_encode($controller->getOrderByUserId($_GET['user']));
        } else {
            echo json_encode($controller->getOrdersList());
        }
    }
} else {
    echo json_encode([]);
}