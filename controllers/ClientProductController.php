<?php

/**
 * @class ClientProductController
 * Currently used to search products by filter
 */
class ClientProductController
{
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    /**
     * Calculate results from max product age found in DB
     * Used for products filter to search by max recommended product age
     * @return array
     */
    public function getAgesSearchValues() {
        // Search the product with the max recommended age found
        $query = $this->db->prepare("SELECT MAX(recommendedAge) FROM products");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Round to integer if is not null, elle set to 0 to fix error
        $count = round($result['MAX(recommendedAge)'] ?? 0);
        $ages = [];

        for ($i = 0; $i < $count; $i++) {
            // Calculate the age from max age
            $calc = round($count / ($i+1));
            if (!in_array($calc,$ages))
            $ages[$i] = $calc;
        }

        return $ages;
    }

}