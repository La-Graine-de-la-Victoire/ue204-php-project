<?php
require_once '../../utils/dbUtilities.php';
require_once '../../utils/security/AdminSecurity.php';

/**
 * @class AdminOrdersController
 */
class AdminOrdersController
{
    private PDO $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    /**
     * Return associated string from order status
     * @param $status
     * @return string
     */
    private function __parseStatus($status) {
        return match ($status) {
            0 => 'Fermée',
            1 => 'Panier non-validé',
            2 => 'En cours de traitement',
            default => 'Erreur inconnue',
        };
    }

    /**
     * Return orders list showed in DataTable
     *
     * @param string $condition
     * @param $basketsMode
     * @return array|false
     */
    private function __getOrdersList(string $condition, $basketsMode = false) {
        // Get all orders
        $ordersQuery = $this->db->prepare('SELECT * FROM orders WHERE '.$condition);
        $ordersQuery->execute();
        $data = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);

        // Get all users
        $usersQuery = $this->db->prepare('SELECT * FROM users');
        $usersQuery->execute();
        $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as &$order) {
            foreach ($users as $user) {
                // Add link to user profile for associated orders
                if ($order['client'] == $user['id']) {
                    $order['client'] = '<a href="/admin/user/user.php?id='.$user['id'].' class="table-link" target="_blank">'.$user['firstName'].' '.$user['lastName'].'</a>';
                }
            }

            // Parse date to french format
            if ($basketsMode) {
                $order['closeDate'] = date('d/m/Y', strtotime($order['creationDate']));
            } else {
                $order['closeDate'] = date('d/m/Y', strtotime($order['closeDate']));
            }

            // Add str status & count DIFFERENT articles (not quantities)
            $order['status'] = $this->__parseStatus($order['status']);
            $order['quantity'] = count(json_decode($order['products'], true));
            // Link to see the order
            $order['action'] = '<a href="/admin/order/order.php?id='.$order['id'].'" class="table-btn table-btn-std">Voir</a>';
        }

        return $data;
    }

    /**
     * Return paid or closed orders list showed in DataTable
     * @return array|false
     */
    public function getOrdersList() {
        return $this->__getOrdersList('NOT status = 1');
    }

    /**
     * Return open not-paid list showed in DataTable
     * @return array|false
     */
    public function getNoConfirmedOrdersList() {
        return $this->__getOrdersList('status = 1', true);
    }

    /**
     * Return orders by user ID
     * @param $id
     * @return array|false
     */
    public function getOrderByUserId($id) {
        // Search order by client ID
        $query = $this->db->prepare("SELECT id, products, creationDate, closeDate, totalPrice, status FROM orders WHERE client = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$result) {
            // Add meta information to main array
            $result['quantity'] = count(explode(',', $result['products']));
            $result['creationDate'] = date('d/m/Y', strtotime($result['creationDate']));
            $result['closeDate'] = date('d/m/Y', strtotime($result['closeDate']));
            $result['status'] = $this->__parseStatus($result['status']);
            $result['action'] = '<a href="/admin/order/order.php?orderID='.$result['id'].'" class="table-btn table-btn-std">Visualiser</a>';
        }

        return $results;
    }

    /**
     * Return order by order ID
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getOrderByID($id) {
        // Search order by ID
        $query = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $item = $query->fetch(PDO::FETCH_ASSOC);

        // Entity found
        if ($item) {
            // Get all products
            $productsQuery = $this->db->prepare('SELECT * FROM products');
            $productsQuery->execute();
            $products = $productsQuery->fetchAll(PDO::FETCH_ASSOC);
            $productIdentifiers = json_decode($item['products'], true);

            for ($i = 0; $i < count($productIdentifiers); $i++) {
                foreach ($products as $product) {
                    // Associated product to order product
                    if ($productIdentifiers[$i][0] == $product['id']) {
                        $item['sells'][] = $product;
                    }
                }
            }

            $creationDate = new \DateTime($item['creationDate']);
            $creationDate = $creationDate->format('d/m/Y');
            if ($item['closeDate']!= null) {
                $closeDate = new \DateTime($item['creationDate']);
                $closeDate = $closeDate->format('d/m/Y');
            } else {
                $closeDate = 'Aucune date';
            }

            $item['creationDate'] = $creationDate;
            $item['closeDate'] = $closeDate;
            $item['statusStr'] = $this->__parseStatus($item['status']);
        }

        return $item;
    }

    /**
     * Remove missing quantity from order
     * @param $id
     * @param $item
     * @return void
     * @throws Exception
     */
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

        // Update
        if ($removable) {
            $query = $this->db->prepare("UPDATE orders SET status = 0 WHERE id = :id");
            $query->bindParam(':id', $id);
            $query->execute();
        }

        header('Location: /admin/order/order.php?id='.$id);
    }

}

/**
 * Route parser
 */
if (isset($_GET) && isAdmin()) {
    $controller = new AdminOrdersController();

    if (array_key_exists('user', $_GET)) {
        // Return orders by user ID
        echo json_encode($controller->getOrderByUserId($_GET['user']));
    } else if (array_key_exists('mode', $_GET) &&
        $_GET['mode'] == 'update' &&
        array_key_exists('id', $_GET)) {
        if (array_key_exists('removequantity', $_GET)) {
            // Remove quantity from order
            $controller->removeQuantity(htmlspecialchars($_GET['id']), htmlspecialchars($_GET['removequantity']));
        }
    } else {
        if (!array_key_exists('id', $_GET)) {
            if (array_key_exists('mode', $_GET) && $_GET['mode'] == 'baskets') {
                // Not-paid orders list
                echo json_encode($controller->getNoConfirmedOrdersList());
            } else {
                // Open and close orders list
                echo json_encode($controller->getOrdersList());
            }
        }
    }
} else {
    echo json_encode([]);
}