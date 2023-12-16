<?php
if (session_id() == "") {
    session_start();
}

function isAdmin() : bool {
    return isset($_SESSION) && array_key_exists('auth', $_SESSION) && $_SESSION['auth']->role == 'admin';
}

function notAdminRedirection(): void
{
    if (!isAdmin()) {
        header('Location: /');
    }
}
