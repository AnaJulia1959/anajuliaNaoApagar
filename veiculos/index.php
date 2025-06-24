<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

// Buscar veículos mais recentes
$stmt = $pdo->query("SELECT v.*, c.nome as categoria_nome FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id ORDER BY v.created_at DESC LIMIT 6");
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Veículos em Destaque</h2>
<div class="veiculos-grid">
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
            <a href="<?php echo BASE_URL; ?>veiculos/detalhes.php?id=<?php echo $veiculo['id']; ?>" class="btn">Ver Detalhes</a>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>