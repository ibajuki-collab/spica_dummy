<?php
session_start();

function require_login() {
    if (empty($_SESSION["admin_logged_in"])) {
        header("Location: login.php");
        exit;
    }
}
?>