<?php
require_once 'auth.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'admin') {
    header('Location: ../perfil.php');
    exit();
}
?>