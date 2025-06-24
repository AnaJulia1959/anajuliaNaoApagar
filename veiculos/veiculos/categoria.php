<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

$categoria_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT nome FROM Categoria WHERE id = ?");
$stmt->execute([$categoria_id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT v.*, c.nome as categoria_nome FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id WHERE v.id_categoria = ?");
$stmt->execute([$categoria_id]);
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container categoria-page">
    <h1 class="categoria-title">Veículos na categoria: <?php echo htmlspecialchars($categoria['nome']); ?></h1>
    
    <div class="categoria-grid">
        <?php if (count($veiculos) > 0): ?>
            <?php foreach ($veiculos as $veiculo): ?>
                <div class="categoria-veiculo-card">
                    <?php if (!empty($veiculo['imagem'])): ?>
                        <?php if (filter_var($veiculo['imagem'], FILTER_VALIDATE_URL)): ?>
                            <img src="<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="<?php echo htmlspecialchars($veiculo['modelo']); ?>" class="categoria-veiculo-imagem">
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>assets/images/uploads/<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="<?php echo htmlspecialchars($veiculo['modelo']); ?>" class="categoria-veiculo-imagem">
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="sem-imagem">Imagem não disponível</div>
                    <?php endif; ?>
                    <div class="categoria-veiculo-info">
                        <h3><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?></h3>
                        <p><?php echo htmlspecialchars($veiculo['ano']); ?> • <?php echo number_format($veiculo['quilometragem'], 0, ',', '.'); ?> km</p>
                        <p class="categoria-preco">R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></p>
                        <a href="<?php echo BASE_URL; ?>veiculos/detalhes.php?id=<?php echo $veiculo['id']; ?>" class="categoria-btn">Ver Detalhes</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="categoria-sem-veiculos">Nenhum veículo encontrado nesta categoria.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>