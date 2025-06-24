<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$categoria_id = $_GET['id'];

// Verificar se existem veículos nesta categoria
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM Veiculo WHERE id_categoria = ?");
$stmt->execute([$categoria_id]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $_SESSION['erro'] = "Não é possível excluir esta categoria pois existem veículos vinculados a ela.";
    header("Location: listar.php");
    exit();
}

$stmt = $pdo->prepare("DELETE FROM Categoria WHERE id = ?");
$stmt->execute([$categoria_id]);

header("Location: listar.php");
exit();
?>