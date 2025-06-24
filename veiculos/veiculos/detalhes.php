<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$veiculo_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT v.*, c.nome as categoria_nome FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id WHERE v.id = ?");
$stmt->execute([$veiculo_id]);
$veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$veiculo) {
    header("Location: ../index.php");
    exit();
}
?>

<div class="veiculo-detalhes">
    <div class="veiculo-imagem">
        <img src="../assets/images/uploads/<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="<?php echo htmlspecialchars($veiculo['modelo']); ?>">
    </div>
    <div class="veiculo-info">
        <h2><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?></h2>
        <p><strong>Categoria:</strong> <?php echo htmlspecialchars($veiculo['categoria_nome']); ?></p>
        <p><strong>Ano:</strong> <?php echo htmlspecialchars($veiculo['ano']); ?></p>
        <p><strong>Cor:</strong> <?php echo htmlspecialchars($veiculo['cor']); ?></p>
        <p><strong>Placa:</strong> <?php echo htmlspecialchars($veiculo['placa']); ?></p>
        <p><strong>Quilometragem:</strong> <?php echo number_format($veiculo['quilometragem'], 0, ',', '.'); ?> km</p>
        <p><strong>Preço:</strong> R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></p>
        
        <h3>Descrição</h3>
        <p><?php echo nl2br(htmlspecialchars($veiculo['descricao'])); ?></p>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>