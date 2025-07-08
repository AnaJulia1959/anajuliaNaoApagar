<?php
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT v.*, c.nome as categoria FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id WHERE v.id = ?");
$stmt->execute([$_GET['id']]);
$veiculo = $stmt->fetch();

if (!$veiculo) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']) ?></title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>AutoFuture</h1>
            <div class="admin-nav">
                <a href="index.php" class="voltar">Voltar ao site</a>
                <?php if (isAdmin()): ?>
                    <a href="admin.php" class="admin-btn">Painel</a>
                    <a href="logout.php" class="logout-btn">Sair</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container veiculo-detalhe">
        <div class="veiculo-imagem">
            <?php if ($veiculo['imagem']): ?>
                <img src="<?= htmlspecialchars($veiculo['imagem']) ?>" alt="<?= htmlspecialchars($veiculo['modelo']) ?>">
            <?php else: ?>
                <div class="sem-img">Sem imagem disponível</div>
            <?php endif; ?>
        </div>

        <div class="veiculo-dados">
            <h1><?= htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']) ?></h1>
            <p class="preco"><?= formatarPreco($veiculo['preco']) ?></p>

            <div class="dados-grid">
                <div>
                    <span>Categoria:</span>
                    <strong><?= htmlspecialchars($veiculo['categoria']) ?></strong>
                </div>
                <div>
                    <span>Ano:</span>
                    <strong><?= htmlspecialchars($veiculo['ano']) ?></strong>
                </div>
                <div>
                    <span>Cor:</span>
                    <strong><?= htmlspecialchars($veiculo['cor']) ?></strong>
                </div>
                <div>
                    <span>Placa:</span>
                    <strong><?= htmlspecialchars($veiculo['placa']) ?></strong>
                </div>
            </div>

            <div class="descricao">
                <h3>Descrição</h3>
                <p><?= $veiculo['descricao'] ? nl2br(htmlspecialchars($veiculo['descricao'])) : 'Nenhuma descrição disponível.' ?></p>
            </div>
        </div>
    </main>
</body>
</html>
