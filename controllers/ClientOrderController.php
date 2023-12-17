<?php
require_once 'ProductsController.php';

/**
 * @ClientOrderController
 * Used to manage client orders
 */
class ClientOrderController
{
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    /**
     * Add product to client order
     * If the product is already in the client order, it will be incremented
     *
     * @param $productId
     * @return void
     */
    public function addProductToOrder($productId) {
        //
        // Search product
        $productController = new ProductsController();
        $product = $productController->getProductByID($productId);
        $str = 'product-added';

        // Product foudn in DB
        if ($product) {
            if ($product['quantity'] > 0) {
                // Get client order where the status is 1 (not-paid) and not closed
                $query = $this->db->prepare('SELECT * FROM orders WHERE client = :id AND status = 1 AND closeDate IS NULL');
                $query->bindParam(':id', $_SESSION['auth']->id);
                $query->execute();
                $order = $query->fetch(PDO::FETCH_ASSOC);

                // Order not found : create new order
                if (!$order) {
                    // Register product : ID / Quantity / Missing Quantity
                    $json = json_encode([[intval($productId), 1, 1]]);
                    $status = 1;
                    $creationDate = new \DateTime(); $creationDate = $creationDate->format('Y-m-d H:i:s');
                    $price = $product['price'];

                    // Prepare & save new order
                    $query = $this->db->prepare(
                        'INSERT INTO orders (client, products, status, creationDate, totalPrice)
                                    VALUES (:client, :products, :status, :creationDate, :totalPrice)'
                    );
                    $query->bindParam(':client', $_SESSION['auth']->id);
                    $query->bindParam(':products', $json);
                    $query->bindParam(':status', $status);
                    $query->bindParam(':creationDate', $creationDate);
                    $query->bindParam(':totalPrice', $price);
                    $query->execute();
                } else {
                    //
                    // Update order
                    $newProducts = json_decode($order['products'], true);
                    $in = false;

                    foreach ($newProducts as &$newProduct) {
                        if ($newProduct[0] == intval($productId)) {
                            // Product found : increment quantity and increment missing quantity
                            $newProduct[1]++;
                            $newProduct[2]++;
                            $in = true;
                        }
                    }

                    if (!$in) {
                        $newProducts[] = [intval($productId), 1, 1];
                    }

                    // Update total price
                    $total = $order['totalPrice'] + $product['price'];
                    $encoded = json_encode($newProducts);

                    // Save the update in DB
                    $query = $this->db->prepare('UPDATE orders SET products = :products, totalPrice = :totalPrice WHERE id = :id');
                    $query->bindParam(':products', $encoded);
                    $query->bindParam(':totalPrice', $total);
                    $query->bindParam(':id', $order['id']);
                    $query->execute();

                    // Reduce stock
                    $quantity = $product['quantity'] - 1;
                    $query = $this->db->prepare('UPDATE productsMeta SET quantity = :quantity WHERE id = :productId');
                    $query->bindParam(':quantity', $quantity);
                    $query->bindParam(':productId', $productId);
                    $query->execute();
                }
            } else {
                $str = 'product-stock-error';
            }
        } else {
            $str = 'product-not-found';
        }

        // Redirect
        if (array_key_exists('basket', $_GET)) {
            header('Location: /achat/panier.php?response='.$str);
        } else {
            header('Location: /products.php?response='.$str);
        }
        exit();
    }

    /**
     * Return the opened client order
     * @return array
     */
    public function getCurrentClientOrder() {
        // Found client order where the status is 1 (not-paid)
        $query = $this->db->prepare('SELECT * FROM orders WHERE client = :id AND status = 1');
        $query->bindParam(':id', $_SESSION['auth']->id);
        $query->execute();
        $order = $query->fetch(PDO::FETCH_ASSOC);
        $products = [];
        $quantity = 0;
        $productsInformation = [];

        // Order found
        if ($order) {
            $products = json_decode($order['products'], true);

            //
            // Get all products information & meta
            foreach ($products as $product) {
                // For all products get in DB there information & meta
                $query = $this->db->prepare('SELECT * FROM products WHERE id = :id');
                $query->bindParam(':id', $product[0]);
                $query->execute();
                $productData = $query->fetch(PDO::FETCH_ASSOC);

                $query = $this->db->prepare('SELECT * FROM productsMeta WHERE id = :id');
                $query->bindParam(':id', $productData['id']);
                $query->execute();
                $productMeta = $query->fetch(PDO::FETCH_ASSOC);

                // Save product information & meta in array
                $productsInformation[] = [
                    'data' => $productData,
                    'meta' => $productMeta
                ];

                $quantity += $product[1];
            }
        }

        // Return important information
        return [
            'products' => $productsInformation,
            'count' => $quantity,
            'order' => $order,
            'items' => $products
        ];
    }

    /**
     * Check if product exists in the client order & remove it
     *
     * @param $productId
     * @return void
     */
    public function removeProductFromOrder($productId)
    {
        // Get current order
        $query = $this->db->prepare('SELECT * FROM orders WHERE client = :id AND status = 1');
        $query->bindParam(':id', $_SESSION['auth']->id);
        $query->execute();
        $order = $query->fetch(PDO::FETCH_ASSOC);
        $str = 'article-remove-not-found';

        $totalPrice = $order['totalPrice'];

        // Order found
        if ($order) {
            $products = json_decode($order['products'], true);

            // Search in order products if the product exists
            foreach ($products as $key => &$product) {
                if ($product[0] == intval($productId)) {
                    // Remove 1 entity from quantity & missing quantity
                    $product[1]--;
                    $product[2]--;

                    if ($product[1] <= 0) {
                        unset($products[$key]);
                    }

                    // Get meta
                    $dbProduct = $this->db->prepare('SELECT * FROM productsMeta WHERE id = :id');
                    $dbProduct->bindParam(':id', $product[0]);
                    $dbProduct->execute();
                    $productData = $dbProduct->fetch(PDO::FETCH_ASSOC);

                    // Update order total price & product quantity
                    $totalPrice -= $productData['price'];
                    $newQuantity = ++$productData['quantity'];

                    // Save new meta information
                    $query = $this->db->prepare('UPDATE productsMeta SET quantity = :quantity WHERE id = :productId');
                    $query->bindParam(':quantity', $newQuantity);
                    $query->bindParam(':productId', $product[0]);
                    $query->execute();

                    // Update order
                    $newProducts = json_encode($products);
                    $query = $this->db->prepare('UPDATE orders SET products = :products, totalPrice = :totalPrice WHERE id = :id');
                    $query->bindParam(':products', $newProducts);
                    $query->bindParam(':totalPrice', $totalPrice);
                    $query->bindParam(':id', $order['id']);
                    $query->execute();

                    $str = 'article-removed';
                }
            }
        }

        // Redirect user
        if (array_key_exists('basket', $_GET)) {
            header('Location: /achat/panier.php?response='.$str);
        } else {
            header('Location: /products.php?response='.$str);
        }
        exit();
    }

    /**
     * Valid and paid order
     * @return void
     */
    public function purchase() {
        if (!empty($_POST['__sendPaypal'])) {
            $payMode = 1;
        } else {
            $payMode = 2;
        }
        $now = new \DateTime(); $now = $now->format('Y-m-d H:i:s');

        // Select current order
        $query = $this->db->prepare('SELECT * FROM orders WHERE client = :id AND status = 1');
        $query->bindParam(':id', $_SESSION['auth']->id);
        $query->execute();
        $order = $query->fetch(PDO::FETCH_ASSOC);

        $products = json_decode($order['products'], true);

        // get all products in order
        foreach ($products as &$product) {
            $dbProduct = $this->db->prepare('SELECT * FROM productsMeta WHERE id = :id');
            $dbProduct->bindParam(':id', $product[0]);
            $dbProduct->execute();
            $productData = $dbProduct->fetch(PDO::FETCH_ASSOC);

            // Increment sells from quantity
            $sells = $productData['sells'] + $product[1];

            // Update product sells
            $query = $this->db->prepare('UPDATE productsMeta SET sells = :sells WHERE id = :productId');
            $query->bindParam(':sells', $sells);
            $query->bindParam(':productId', $product[0]);
            $query->execute();
        }

        // Secure address
        $address = htmlspecialchars($_POST['__address']) . ' - ' . htmlspecialchars($_POST['__zip']). ' - '. htmlspecialchars($_POST['__city']) . ' | ' . htmlspecialchars($_POST['__country']);

        // Set address in order & close order & set status to 2 & register payment mode
        $query = $this->db->prepare('UPDATE orders SET status = 2, payementMode = :payMode, closeDate = :closeDate, address = :address WHERE id = :id');
        $query->bindParam(':payMode', $payMode);
        $query->bindParam(':id', $order['id']);
        $query->bindParam(':closeDate', $now);
        $query->bindParam(':address', $address);
        $query->execute();
    }

}