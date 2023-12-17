<?php
if (session_id() == "") {
    session_start();
}

// Check if the user is admin
function isAdmin() : bool {
    return isset($_SESSION) && array_key_exists('auth', $_SESSION) && $_SESSION['auth']->role == 'admin';
}

// Redirect the user to home page if he is not admin
function notAdminRedirection(): void
{
    if (!isAdmin()) {
        header('Location: /');
    }
}
