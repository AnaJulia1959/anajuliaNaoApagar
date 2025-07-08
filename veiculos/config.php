<?php
session_start();

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_veiculos');
define('DB_USER', 'root');
define('DB_PASS', '');

// Conexão com o banco
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Verifica se está logado
function isAdmin() {
    return isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true;
}

// Redireciona se não estiver logado
function requerLogin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit();
    }
}

// Formata preço
function formatarPreco($preco) {
    return 'R$ ' . number_format($preco, 2, ',', '.');
}

// Upload de imagem
function uploadImagem($file) {
    $targetDir = "uploads/";
    $fileName = uniqid() . '-' . basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    
    // Verifica se é uma imagem
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) return false;
    
    // Move o arquivo
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    }
    return false;
}


?>