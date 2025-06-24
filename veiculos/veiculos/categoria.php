<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$categoria_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT nome FROM Categoria WHERE id = ?");
$stmt->execute([$categoria_id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    header("Location: ../index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT v.*, c.nome as categoria_nome FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id WHERE v.id_categoria = ?");
$stmt->execute([$categoria_id]);
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Veículos na categoria: <?php echo htmlspecialchars($categoria['nome']); ?></h2>

<div class="veiculos-grid">
    <?php if (count($veiculos) > 0): ?>
        <?php foreach ($veiculos as $veiculo): ?>
            <div class="veiculo-card">
                <img src="../assets/images/uploads/<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="<?php echo htmlspecialchars($veiculo['modelo']); ?>">
                <h3><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?></h3>
                <p><?php echo htmlspecialchars($veiculo['ano']); ?> • <?php echo number_format($veiculo['quilometragem'], 0, ',', '.'); ?> km</p>
                <p class="preco">R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></p>
                <a href="detalhes.php?id=<?php echo $veiculo['id']; ?>" class="btn">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum veículo encontrado nesta categoria.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>