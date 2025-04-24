<?php
$host = 'localhost';
$dbname = 'ong_amigo_bicho';
$username = 'root';
$password = '1234';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

?>
