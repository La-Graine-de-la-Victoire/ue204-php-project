<?php
require_once '../../utils/dbUtilities.php';
require_once '../../utils/security/AdminSecurity.php';

class AdminProductsController
{
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    public function getProductsList() {
        $query = $this->db->prepare("SELECT id, name, editor FROM products");
        $query->execute();
        $products = $query->fetchAll(PDO::FETCH_ASSOC);

        $query = $this->db->prepare("SELECT id, quantity, price FROM productsMeta");
        $query->execute();
        $meta = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $key => &$product) {
            $product['price'] = $meta[$key]['price'];
            $product['quantity'] = $meta[$key]['quantity'];
            $edit = '<a href="/admin/product/edit.php?id='. $product['id'].'" class="table-btn table-btn-std"><i class="fa fa-pencil-square"></i></a>';
            $delete = '<a href="/admin/product/edit.php?id='. $product['id'].'&delete=1&confirm=0" class="table-btn table-btn-trash"><i class="fa fa-trash"></i></a>';

            $product['actions'] = $edit . ' ' . $delete;
        }

        return $products;
    }

    public function delete(int $id) {
        $query = $this->db->prepare("DELETE FROM productsMeta WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        $query = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        // Check if the product has been deleted
        $query = $this->db->prepare("SELECT id FROM products WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->rowCount() == 0;
    }

    private function __addRedirect($status, $message) {
        $url = '/admin/product/add.php?link='.urlencode('/admin/product/list.php');

        header('Location: '.$url.'&status='.$status.'&message='.urlencode($message));
    }

    public function add(array $request): void {
        if (!empty($request['__productName']) &&
            !empty($request['__productEditor']) &&
            !empty($request['__productDescription']) &&
            !empty($request['__productMinAge']) &&
            !empty($request['__productPrice']) &&
            !empty($request['__productStock'])) {

            if (!is_string($request['__productName']) || !is_string($request['__productEditor']) || !is_string($request['__productDescription']) ||
                !is_int((int)$request['__productMinAge']) || !is_int((int)$request['__productStock']) ||
                !is_double((double)$request['__productPrice'])) {
                // At the end encode the result of all conditions
                $this->__addRedirect(400, 'Le format de certaines données n\'est pas valide !');
            }

            $productName = htmlspecialchars($request['__productName']);
            $productEditor = htmlspecialchars($request['__productEditor']);
            $productDescription = htmlspecialchars($request['__productDescription']);
            $productMinAge = htmlspecialchars($request['__productMinAge']);
            $productPrice = htmlspecialchars($request['__productPrice']);
            $productStock = htmlspecialchars($request['__productStock']);

            if (strlen($productDescription) > 255) {
                $this->__addRedirect(400, 'La description du produit est trop longue !');
            }

            $dflSells = 0;

            $query = $this->db->prepare("INSERT INTO productsMeta (price, quantity, sells) VALUES (:price, :quantity, :sells)");
            $query->bindParam(':price', $productPrice);
            $query->bindParam(':quantity', $productStock);
            $query->bindParam(':sells', $dflSells);
            $query->execute();

            $lastInsertId = $this->db->lastInsertId();

            $query = $this->db->prepare("INSERT INTO products (id, name, editor, description, recommendedAge) VALUES (:id, :name, :editor, :description, :recommendedAge)");
            $query->bindParam(':id', $lastInsertId);
            $query->bindParam(':name', $productName);
            $query->bindParam(':editor', $productEditor);
            $query->bindParam(':description', $productDescription);
            $query->bindParam(':recommendedAge', $productMinAge);
            $query->execute();

            $this->__addRedirect(200, 'Le produit a bien été ajouté !');

        } else {
            $this->__addRedirect(400, 'Tous les champs ne sont pas remplis !');
        }
    }

    public function getProductByID(int $id): array|bool {
        $query = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $product = $query->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $query = $this->db->prepare("SELECT * FROM productsMeta WHERE id = :id");
            $query->bindParam(':id', $id);
            $query->execute();
            $meta = $query->fetch(PDO::FETCH_ASSOC);

            $product['price'] = $meta['price'];
            $product['quantity'] = $meta['quantity'];
            $product['sells'] = $meta['sells'];
        }

        return $product;
    }

}

if (isset($_GET) && isAdmin()) {
    $controller = new AdminProductsController();
    if (array_key_exists('getList', $_GET)) {

        if (array_key_exists('delete', $_GET) &&
            array_key_exists('confirm', $_GET) &&
            $_GET['delete'] &&
            $_GET['confirm']) {
            echo json_encode($controller->delete($_GET['id']));
        } else {
            echo json_encode($controller->getProductsList());
        }
    } else if (array_key_exists('add', $_GET) && $_GET['add'] == '1' && isset($_POST)) {
        $controller->add($_POST);
    }
}