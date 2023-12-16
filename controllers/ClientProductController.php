<?php
class ClientProductController
{
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    public function getAgesSearchValues() {
        $query = $this->db->prepare("SELECT MAX(recommendedAge) FROM products");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $count = round($result['MAX(recommendedAge)'] ?? 0);
        $ages = [];

        for ($i = 0; $i < $count; $i++) {
            $calc = round($count / ($i+1));
            if (!in_array($calc,$ages))
            $ages[$i] = $calc;
        }

        return $ages;
    }

}