<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$veiculo_id = $_GET['id'];

// Buscar veículo para obter o nome da imagem
$stmt = $pdo->prepare("SELECT imagem FROM Veiculo WHERE id = ?");
$stmt->execute([$veiculo_id]);
$veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($veiculo) {
    // Excluir a imagem se existir
    if ($veiculo['imagem'] && file_exists("../../assets/images/uploads/" . $veiculo['imagem'])) {
        unlink("../../assets/images/uploads/" . $veiculo['imagem']);
    }
    
    // Excluir o veículo do banco de dados
    $stmt = $pdo->prepare("DELETE FROM Veiculo WHERE id = ?");
    $stmt->execute([$veiculo_id]);
}

header("Location: listar.php");
exit();
?>