<?php
require_once '../../utils/dbUtilities.php';
require_once '../../utils/security/AdminSecurity.php';

/**
 * @class AdminUsersController
 * Used to manage users from admin panel
 */
class AdminUsersController
{
    private PDO $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    /**
     * Get users list showed in DataTable
     *
     * @return array|false
     */
    public function getUsersList() {
        // Get main users information
        $query = $this->db->prepare("SELECT id, lastName, firstName, creationDate, role FROM users");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$result) {
            // Parse date to french format
            $result['creationDate'] = date('d/m/Y', strtotime($result['creationDate']));

            // Generate delete link from user ID
            $deleteLink = '<a href="/admin/user/user.php?id='.$result['id']. '&delete=true&confirm=false" class="table-btn table-btn-trash"><i class="fa fa-trash"></i></a>';
            if ($result['role'] == 'admin') {
                // If the user is an admin, don't show delete link => no remove
                $deleteLink = '';
            }

            // Add edit & delete links to table
            $result['profile'] = '<a href="/admin/user/user.php?id='. $result['id']. '" class="table-btn table-btn-std">Voir</a>'.$deleteLink;
        }

        return $results;
    }

    /**
     * Return user information by ID
     * @param $id
     * @return mixed
     */
    public function getUserById($id) {
        // Search user by ID
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // User found
        if ($result) {
            // Parse dates to french format
            $result['creationDate'] = date('d/m/Y', strtotime($result['creationDate']));
            if ($result['lastAccess']) {
                $result['lastAccess'] = date('d/m/Y h:i:y', strtotime($result['lastAccess']));
            } else {
                $result['lastAccess'] = 'Jamais connecté';
            }
        }

        return $result;
    }

    /**
     * Delete a user in DB with associated orders
     * @param $id
     * @return mixed|string[]
     */
    public function deleteUser($id) {
        // Get user
        $query = $this->db->prepare("SELECT role FROM users WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // We cannot delete an admin
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

/**
 * Route parser
 */
if (isset($_GET) && isAdmin()) {
    if (array_key_exists('getList', $_GET)) {
        $controller = new AdminUsersController();

        if (array_key_exists('delete', $_GET) &&
            array_key_exists('confirm', $_GET) &&
            $_GET['delete'] &&
            $_GET['confirm']) {
            // Delete user with confirmation
            echo json_encode($controller->deleteUser($_GET['id']));
        } else {
            // Deletion not confirmed
            echo json_encode($controller->getUsersList());
        }
    }
}