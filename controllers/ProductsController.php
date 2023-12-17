<?php
require_once dirname(__DIR__).'/./utils/dbUtilities.php';
require_once dirname(__DIR__).'/utils/security/AdminSecurity.php';

/**
 * @class ProductsController
 * Used to manage products
 */
class ProductsController
{
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    /**
     * Return all products in DB with meta information and admin action (delete/edit)
     * @return array|false
     */
    public function getProductsList() {
        // Get products
        $query = $this->db->prepare("SELECT * FROM products");
        $query->execute();
        $products = $query->fetchAll(PDO::FETCH_ASSOC);

        // Get products meta
        $query = $this->db->prepare("SELECT * FROM productsMeta");
        $query->execute();
        $meta = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $key => &$product) {
            // Add price and quantity to products array
            $product['price'] = $meta[$key]['price'];
            $product['quantity'] = $meta[$key]['quantity'];

            // Generate links to edit and delete products
            $edit = '<a href="/admin/product/edit.php?id='. $product['id'].'" class="table-btn table-btn-std"><i class="fa fa-pencil-square"></i></a>';
            $delete = '<a href="/admin/product/edit.php?id='. $product['id'].'&delete=1&confirm=0" class="table-btn table-btn-trash"><i class="fa fa-trash"></i></a>';

            $product['actions'] = $edit . ' ' . $delete;
        }

        return $products;
    }

    /**
     * Delete product from DB by ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id) {
        // Search product meta by product ID
        $query = $this->db->prepare("DELETE FROM productsMeta WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        // Search product by ID
        $query = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        // Check if the product has been deleted
        $query = $this->db->prepare("SELECT id FROM products WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        // Return true if the product has been deleted
        return $query->rowCount() == 0;
    }

    /**
     * Short function to redirect admin after product edition
     * @param $status
     * @param $message
     * @param $editMode
     * @param $productID
     * @return void
     */
    private function __editRedirect($status, $message, $editMode, $productID = ''): void
    {
        $path = 'add.php?';
        if ($editMode) {
            $path = 'edit.php?id='.$productID.'&';
        }
        $url = '/admin/product/'.$path.'link='.urlencode('/admin/product/list.php');

        header('Location: '.$url.'&status='.$status.'&message='.urlencode($message));
    }

    /**
     * Edit and add product in DB by ID
     * @param array $request
     * @return void
     */
    public function edit(array $request): void {
        $updateMode = array_key_exists('update', $_GET);

        // Detect if all empty are not empty
        if (!empty($request['__productName']) &&
            !empty($request['__productEditor']) &&
            !empty($request['__productDescription']) &&
            !empty($request['__productMinAge']) &&
            !empty($request['__productPrice']) &&
            !empty($request['__productStock'])) {

            // Detect if all fields are in good type
            if (!is_string($request['__productName']) || !is_string($request['__productEditor']) || !is_string($request['__productDescription']) ||
                !is_int((int)$request['__productMinAge']) || !is_int((int)$request['__productStock']) ||
                !is_double((double)$request['__productPrice'])) {
                // At the end encode the result of all conditions
                $this->__editRedirect(400, 'Le format de certaines données n\'est pas valide !', $updateMode);
            }

            // Secure data
            $productName = htmlspecialchars($request['__productName']);
            $productEditor = htmlspecialchars($request['__productEditor']);
            $productDescription = htmlspecialchars($request['__productDescription']);
            $productMinAge = htmlspecialchars($request['__productMinAge']);
            $productPrice = htmlspecialchars($request['__productPrice']);
            $productStock = htmlspecialchars($request['__productStock']);

            // Max description length : 255
            if (strlen($productDescription) > 255) {
                $this->__editRedirect(400, 'La description du produit est trop longue !', $updateMode);
            }

            // If the image is not empty, upload the file and attribute
            // unique ID and save path in DB
            $image = NULL;
            if (array_key_exists('__productImage', $_FILES) &&!empty($_FILES['__productImage']) && $_FILES['__productImage']['error'] == UPLOAD_ERR_OK) {
                $fn = $_FILES['__productImage']['name'];
                $tmp = $_FILES['__productImage']['tmp_name'];

                $outShort = '/assets/uploads/'.uniqid().'__'.$fn;
                $out = dirname(__DIR__).$outShort;
                if (move_uploaded_file($tmp, $out)) {
                    $image = $outShort;
                } else {
                    $this->__editRedirect(400, 'Une erreur est survenue lors du téléchargement de l\'image!');
                }
            }

            $dflSells = 0;
            if ($updateMode) {
                // Edit product
                $metaSqlRequest = "UPDATE productsMeta SET price = :price, quantity = :quantity WHERE id = :id";
                $productSqlRequest = "UPDATE products SET name = :name, editor = :editor, description = :description, recommendedAge = :recommendedAge, image = :image WHERE id = :id";
            } else {
                // Add product
                $metaSqlRequest = "INSERT INTO productsMeta (price, quantity, sells) VALUES (:price, :quantity, :sells)";
                $productSqlRequest = "INSERT INTO products (id, name, editor, description, recommendedAge, image) VALUES (:id, :name, :editor, :description, :recommendedAge, :image)";
            }

            // Save in DB
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
            $query->bindParam(':image', $image);
            $query->execute();

            $this->__editRedirect(200,
            $updateMode? 'Le produit a bien été modifié !' : 'Le produit a bien été ajouté !', $updateMode, $id);

        } else {
            $this->__editRedirect(400, 'Tous les champs ne sont pas remplis !', $updateMode);
        }
    }

    /**
     * Return product information by ID
     * @param int $id
     * @return array|bool
     */
    public function getProductByID(int $id): array|bool {
        // Search product
        $query = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $product = $query->fetch(PDO::FETCH_ASSOC);

        // Product found
        if ($product) {
            // Search meta
            $query = $this->db->prepare("SELECT * FROM productsMeta WHERE id = :id");
            $query->bindParam(':id', $id);
            $query->execute();
            $meta = $query->fetch(PDO::FETCH_ASSOC);

            // Add price, quantity and sells to product array
            $product['price'] = $meta['price'];
            $product['quantity'] = $meta['quantity'];
            $product['sells'] = $meta['sells'];
        }

        return $product;
    }

    /**
     * Return the 4 last added products
     * @return array
     */
    public function getLastProducts(): array {
        // Search the last 4 products
        $query = $this->db->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 4");
        $query->execute();
        $products = $query->fetchAll(PDO::FETCH_ASSOC);

        // Products found
        if ($products) {
            // Search meta for each product and add price, quantity and sells to product array
            foreach ($products as $key =>$product) {
                $query = $this->db->prepare("SELECT * FROM productsMeta WHERE id = :id");
                $query->bindParam(':id', $product['id']);
                $query->execute();
                $meta = $query->fetch(PDO::FETCH_ASSOC);

                $products[$key]['price'] = $meta['price'];
                $products[$key]['quantity'] = $meta['quantity'];
                $products[$key]['sells'] = $meta['sells'];
            }
        }

        return $products;
    }

    /**
     * Return the 4 best selling products
     * @return array
     */
    public function getBestSells(): array {
        // Get products meta and order by sells
        $query = $this->db->prepare("SELECT * FROM productsMeta ORDER BY sells DESC LIMIT 4");
        $query->execute();
        $productsMeta = $query->fetchAll(PDO::FETCH_ASSOC);

        // Product meta found
        if ($productsMeta) {
            // Search product main information & add name, description and image to product array
            foreach ($productsMeta as $key =>$product) {
                $query = $this->db->prepare("SELECT * FROM products WHERE id = :id");
                $query->bindParam(':id', $product['id']);
                $query->execute();
                $item = $query->fetch(PDO::FETCH_ASSOC);

                if ($item) {
                    $productsMeta[$key]['name'] = $item['name'];
                    $productsMeta[$key]['description'] = $item['description'];
                    $productsMeta[$key]['image'] = $item['image'];
                }
            }
        }

        return $productsMeta;
    }

}

/**
 * Route parser
 */
if (isset($_GET) && isAdmin()) {
    $controller = new ProductsController();
    if (array_key_exists('getList', $_GET)) {
        // Products list

        if (array_key_exists('delete', $_GET) &&
            array_key_exists('confirm', $_GET) &&
            $_GET['delete'] &&
            $_GET['confirm']) {
            // Delete product with confirmation
            echo json_encode($controller->delete($_GET['id']));
        } else {
            // Deletion not confirmed
            echo json_encode($controller->getProductsList());
        }
    } else if (isset($_POST)) {
        if ((array_key_exists('add', $_GET) && $_GET['add'] == '1') ||
            (array_key_exists('update', $_GET) && $_GET['update'])) {
            // Edit product (or add) => detected in controller
            $controller->edit($_POST);
        }
    }
}