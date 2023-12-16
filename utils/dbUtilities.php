<?php
require_once 'dabaseDriver.php';
class dbUtilities
{
    private $__pdo;

    public function __construct()
    {
        global $pdo;
        $this->__pdo = $pdo;
    }

    function countUsers() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM users");
        $exe->execute();
        return $exe->fetchColumn();
    }

    function countProducts() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM products");
        $exe->execute();
        return $exe->fetchColumn();
    }

    function countNotFinishedOrders() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 2");
        $exe->execute();
        return $exe->fetchColumn();
    }

    function countNotConfirmedBaskets() : int {
        $exe = $this->__pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 1");
        $exe->execute();
        return $exe->fetchColumn();
    }

}