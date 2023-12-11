<?php
require_once '../../utils/dbUtilities.php';
require_once '../../utils/security/AdminSecurity.php';

class AdminUsersController
{
    private PDO $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getUsersList() {
        $query = $this->db->prepare("SELECT id, lastName, firstName, creationDate, role FROM users");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$result) {
            $result['creationDate'] = date('d/m/Y', strtotime($result['creationDate']));

            $deleteLink = '<a href="/admin/user/user.php?id='.$result['id']. '&delete=true&confirm=false" class="table-btn table-btn-trash"><i class="fa fa-trash"></i></a>';
            if ($result['role'] == 'admin') {
                $deleteLink = '';
            }

            $result['profile'] = '<a href="/admin/user/user.php?id='. $result['id']. '" class="table-btn table-btn-std">Voir</a>'.$deleteLink;
        }

        return $results;
    }

    public function getUserById($id) {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $result['creationDate'] = date('d/m/Y', strtotime($result['creationDate']));
            if ($result['lastAccess']) {
                $result['lastAccess'] = date('d/m/Y h:i:y', strtotime($result['lastAccess']));
            } else {
                $result['lastAccess'] = 'Jamais connecté';
            }
        }

        return $result;
    }

    public function deleteUser($id) {
        // Check if is not admin
        $query = $this->db->prepare("SELECT role FROM users WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result['role'] == 'admin') {
            return ['error'];
        }

        // Delete user
        $query = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        // Delete user orders
        $query = $this->db->prepare("DELETE FROM orders WHERE client = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        // Check if user as been deleted
        $query = $this->db->prepare("SELECT id FROM users WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

}

if (isset($_GET) && isAdmin()) {
    if (array_key_exists('getList', $_GET)) {
        $controller = new AdminUsersController();

        if (array_key_exists('delete', $_GET) &&
            array_key_exists('confirm', $_GET) &&
            $_GET['delete'] &&
            $_GET['confirm']) {
            echo json_encode($controller->deleteUser($_GET['id']));
        } else {
            echo json_encode($controller->getUsersList());
        }
    }
} else {
    echo json_encode([]);
}