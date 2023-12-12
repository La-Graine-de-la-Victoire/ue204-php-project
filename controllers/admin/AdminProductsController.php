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

    private function __editRedirect($status, $message, $editMode, $productID = ''): void
    {
        $path = 'add.php?';
        if ($editMode) {
            $path = 'edit.php?id='.$productID.'&';
        }
        $url = '/admin/product/'.$path.'link='.urlencode('/admin/product/list.php');

        header('Location: '.$url.'&status='.$status.'&message='.urlencode($message));
    }

    public function edit(array $request): void {
        $updateMode = array_key_exists('update', $_GET);

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
                $this->__editRedirect(400, 'Le format de certaines données n\'est pas valide !');
            }

            $productName = htmlspecialchars($request['__productName']);
            $productEditor = htmlspecialchars($request['__productEditor']);
            $productDescription = htmlspecialchars($request['__productDescription']);
            $productMinAge = htmlspecialchars($request['__productMinAge']);
            $productPrice = htmlspecialchars($request['__productPrice']);
            $productStock = htmlspecialchars($request['__productStock']);

            if (strlen($productDescription) > 255) {
                $this->__editRedirect(400, 'La description du produit est trop longue !');
            }

            $dflSells = 0;

            if ($updateMode) {
                $metaSqlRequest = "UPDATE productsMeta SET price = :price, quantity = :quantity WHERE id = :id";
                $productSqlRequest = "UPDATE products SET name = :name, editor = :editor, description = :description, recommendedAge = :recommendedAge WHERE id = :id";
            } else {
                $metaSqlRequest = "INSERT INTO productsMeta (price, quantity, sells) VALUES (:price, :quantity, :sells)";
                $productSqlRequest = "INSERT INTO products (id, name, editor, description, recommendedAge) VALUES (:id, :name, :editor, :description, :recommendedAge)";
            }

            $query = $this->db->prepare($metaSqlRequest);
            $query->bindParam(':price', $productPrice);
            $query->bindParam(':quantity', $productStock);
            if (!$updateMode) {
                $query->bindParam(':sells', $dflSells);
            } else {
                $query->bindParam(':id', $request['__productID']);
            }
            $query->execute();

            if (!$updateMode) {
                $id = $this->db->lastInsertId();
            } else {
                $id = $request['__productID'];
            }

            $query = $this->db->prepare($productSqlRequest);
            $query->bindParam(':id', $id);
            $query->bindParam(':name', $productName);
            $query->bindParam(':editor', $productEditor);
            $query->bindParam(':description', $productDescription);
            $query->bindParam(':recommendedAge', $productMinAge);
            $query->execute();

            $this->__editRedirect(200,
            $updateMode? 'Le produit a bien été modifié !' : 'Le produit a bien été ajouté !', $updateMode, $id);

        } else {
            $this->__editRedirect(400, 'Tous les champs ne sont pas remplis !', $updateMode);
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
    } else if (isset($_POST)) {
        if ((array_key_exists('add', $_GET) && $_GET['add'] == '1') ||
            (array_key_exists('update', $_GET) && $_GET['update'])) {
            $controller->edit($_POST);
        }
    }
}