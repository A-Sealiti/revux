<?php
require 'auth/auth.php';
require 'modules/database.php';
require_once 'modules/functions.php';
function isAdmin(): bool
{
    // Controleer of je ingelogd bent en of de rol 'admin' is
    if (isset($_SESSION['user']) && !empty($_SESSION['user']))
        $user = $_SESSION['user'];
        if ($user->role === "admin")
        {
            return true;
        }
    else
        {
            return false;
        }
    return false;
    }
?>