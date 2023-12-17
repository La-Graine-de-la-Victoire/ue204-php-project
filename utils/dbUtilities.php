<?php
require_once 'dabaseDriver.php';

/**
 * @class dbUtilities
 *  Used to count different tables entities
 */
class dbUtilities
{
    private $__pdo;

    public function __construct()
    {
        // Build PDO connection
        global $pdo;
        $this->__pdo = $pdo;
    }

    /**
     * Count number of users saved in database
     * @return int
     */
    function countUsers() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM users");
        $exe->execute();
        return $exe->fetchColumn();
    }

    /**
     * Count number of products saved in database
     * @return int
     */
    function countProducts() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM products");
        $exe->execute();
        return $exe->fetchColumn();
    }

    /**
     * Count number of order saved in database with status = 2
     * /NOTE\ 0 : closed ; 1 : not-paid ; 2 : paid but not closed
     * @return int
     */
    function countNotFinishedOrders() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 2");
        $exe->execute();
        return $exe->fetchColumn();
    }

    /**
     * Count number of order saved in database with status = 1
     * @return int
     */
    function countNotConfirmedBaskets() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 1");
        $exe->execute();
        return $exe->fetchColumn();
    }

}