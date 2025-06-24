<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$termo_modelo = isset($_GET['modelo']) ? trim($_GET['modelo']) : '';
$termo_ano = isset($_GET['ano']) ? trim($_GET['ano']) : '';

if (!empty($termo_modelo)) {
    $stmt = $pdo->prepare("SELECT v.*, c.nome as categoria_nome FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id WHERE v.modelo LIKE ? ORDER BY v.created_at DESC");
    $stmt->execute(["%$termo_modelo%"]);
    $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $titulo = "Resultados para modelo: " . htmlspecialchars($termo_modelo);
} elseif (!empty($termo_ano)) {
    $stmt = $pdo->prepare("SELECT v.*, c.nome as categoria_nome FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id WHERE v.ano = ? ORDER BY v.created_at DESC");
    $stmt->execute([$termo_ano]);
    $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $titulo = "Resultados para ano: " . htmlspecialchars($termo_ano);
} else {
    header("Location: index.php");
    exit();
}
?>

<h2><?php echo $titulo; ?></h2>

<div class="veiculos-grid">
    <?php if (count($veiculos) > 0): ?>
        <?php foreach ($veiculos as $veiculo): ?>
            <div class="veiculo-card">
            <?php if (!empty($veiculo['imagem'])): ?>
                <?php if (filter_var($veiculo['imagem'], FILTER_VALIDATE_URL)): ?>
                    <!-- Se for uma URL externa -->
                    <img src="<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="<?php echo htmlspecialchars($veiculo['modelo']); ?>" class="veiculo-imagem">
                <?php else: ?>
                    <!-- Se for um arquivo local -->
                    <img src="<?php echo BASE_URL; ?>assets/images/uploads/<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="<?php echo htmlspecialchars($veiculo['modelo']); ?>" class="veiculo-imagem">
                <?php endif; ?>
            <?php else: ?>
                <!-- Placeholder caso não tenha imagem -->
                <div class="sem-imagem">Imagem não disponível</div>
            <?php endif; ?>
                <h3><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?></h3>
                <p><?php echo htmlspecialchars($veiculo['ano']); ?> • <?php echo number_format($veiculo['quilometragem'], 0, ',', '.'); ?> km</p>
                <p class="preco">R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></p>
                <a href="veiculos/detalhes.php?id=<?php echo $veiculo['id']; ?>" class="btn">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum veículo encontrado com os critérios de pesquisa.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>