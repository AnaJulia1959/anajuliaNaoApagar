<?php
// arquivo: includes/config.php

session_start();

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'bd_vagas');
define('DB_USER', 'root');
define('DB_PASS', '');

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Função para redirecionar
function redirect($url) {
    header("Location: $url");
    exit();
}

// Verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Verificar se usuário é admin
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Adicione esta função no final do arquivo
function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: login.php");
        exit();
    }
}
?>

