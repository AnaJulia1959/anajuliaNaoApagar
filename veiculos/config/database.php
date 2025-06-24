<?php

define('BASE_URL', '/veiculos/');
$host = 'localhost';
$dbname = 'sistema_veiculos'; // Nome correto do seu banco
$username = 'root';           // Seu usuário MySQL
$password = '';               // Sua senha MySQL


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

session_start();

// Funções de autenticação (movidas para cá)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>