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

    private function __parseStatus($status) {
        return match ($status) {
            0 => 'Fermée',
            1 => 'Panier non-validé',
            2 => 'En cours de traitement',
            default => 'Erreur inconnue',
        };
    }

    private function __getOrdersList(string $condition, $basketsMode = false) {
        $ordersQuery = $this->db->prepare('SELECT * FROM orders WHERE '.$condition);
        $ordersQuery->execute();
        $data = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);

        $usersQuery = $this->db->prepare('SELECT * FROM users');
        $usersQuery->execute();
        $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as &$order) {
            foreach ($users as $user) {
                if ($order['client'] == $user['id']) {
                    $order['client'] = '<a href="/admin/user/user.php?id='.$user['id'].' class="table-link" target="_blank">'.$user['firstName'].' '.$user['lastName'].'</a>';
                }
            }
            if ($basketsMode) {
                $order['closeDate'] = date('d/m/Y', strtotime($order['creationDate']));
            } else {
                $order['closeDate'] = date('d/m/Y', strtotime($order['closeDate']));
            }

            $order['status'] = $this->__parseStatus($order['status']);
            $order['quantity'] = count(json_decode($order['products'], true));
            $order['action'] = '<a href="/admin/order/order.php?id='.$order['id'].'" class="table-btn table-btn-std">Voir</a>';
        }

        return $data;
    }

    public function getOrdersList() {
        return $this->__getOrdersList('NOT status = 1');
    }

    public function getNoConfirmedOrdersList() {
        return $this->__getOrdersList('status = 1', true);
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
            $result['status'] = $this->__parseStatus($result['status']);
            $result['action'] = '<a href="/admin/order/order.php?orderID='.$result['id'].'" class="table-btn table-btn-std">Visualiser</a>';
        }

        return $results;
    }

    public function getOrderByID($id) {
        $query = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if ($item) {

            $productsQuery = $this->db->prepare('SELECT * FROM products');
            $productsQuery->execute();
            $products = $productsQuery->fetchAll(PDO::FETCH_ASSOC);
            $productIdentifiers = json_decode($item['products'], true);

            for ($i = 0; $i < count($productIdentifiers); $i++) {
                foreach ($products as $product) {
                    if ($productIdentifiers[$i][0] == $product['id']) {
                        $item['sells'][] = $product;
                    }
                }
            }

            $item['creationDate'] = date('d/m/Y', strtotime($item['creationDate']));
            $item['closeDate'] = !empty(date('d/m/Y', strtotime($item['closeDate']))) ?
                date('d/m/Y', strtotime($item['closeDate'])) : 'Aucune date';
            $item['statusStr'] = $this->__parseStatus($item['status']);
        }

        return $item;
    }

    public function removeQuantity($id, $item) {
        // get current quantity in json
        $productIdentifiers = json_decode($this->getOrderByID($id)['products'], true);

        // remove quantity from json
        for ($i = 0; $i < count($productIdentifiers); $i++) {
            if ($productIdentifiers[$i][0] == $item) {
                if ($productIdentifiers[$i][2] - 1 >= 0) {
                    $productIdentifiers[$i][2]--;
                }
            }
        }
        // update json
        $query = $this->db->prepare("UPDATE orders SET products = :products WHERE id = :id");
        $encoded = json_encode($productIdentifiers);
        $query->bindParam(':products', $encoded);
        $query->bindParam(':id', $id);
        $query->execute();

        // Detect if all products have been removed
        $query = $this->db->prepare("SELECT products FROM orders WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $products = $query->fetch(PDO::FETCH_ASSOC);
        $products = json_decode($products['products'], true);

        $removable = true;

        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i][2] > 0) {
                $removable = false;
            }
        }

        if ($removable) {
            $query = $this->db->prepare("UPDATE orders SET status = 0 WHERE id = :id");
            $query->bindParam(':id', $id);
            $query->execute();
        }

        header('Location: /admin/order/order.php?id='.$id);
    }

}

if (isset($_GET) && isAdmin()) {
    $controller = new AdminOrdersController();

    if (array_key_exists('user', $_GET)) {
        echo json_encode($controller->getOrderByUserId($_GET['user']));
    } else if (array_key_exists('mode', $_GET) &&
        $_GET['mode'] == 'update' &&
        array_key_exists('id', $_GET)) {
        if (array_key_exists('removequantity', $_GET)) {
            $controller->removeQuantity(htmlspecialchars($_GET['id']), htmlspecialchars($_GET['removequantity']));
        }
    } else {
        if (!array_key_exists('id', $_GET)) {
            if (array_key_exists('mode', $_GET) && $_GET['mode'] == 'baskets') {
                echo json_encode($controller->getNoConfirmedOrdersList());
            } else {
                echo json_encode($controller->getOrdersList());
            }
        }
    }
} else {
    echo json_encode([]);
}