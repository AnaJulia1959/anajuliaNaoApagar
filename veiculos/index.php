<?php
require_once 'config.php';

// Busca veículos com filtros
$where = [];
$params = [];

if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
    $where[] = "id_categoria = ?";
    $params[] = $_GET['categoria'];
}

if (isset($_GET['modelo']) && !empty($_GET['modelo'])) {
    $where[] = "modelo LIKE ?";
    $params[] = '%' . $_GET['modelo'] . '%';
}

if (isset($_GET['ano']) && is_numeric($_GET['ano'])) {
    $where[] = "ano = ?";
    $params[] = $_GET['ano'];
}

$sql = "SELECT v.*, c.nome as categoria FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$veiculos = $stmt->fetchAll();

// Busca categorias para o menu
$categorias = $pdo->query("SELECT * FROM Categoria")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta de Veículos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">
            <img src="uploads/logo.png" alt="AJ Motors" class="logo-img">
            </a>
            <nav>
                <ul>
                    <li><a href="index.php">Todos</a></li>
                    <?php foreach ($categorias as $cat): ?>
                        <li><a href="index.php?categoria=<?= $cat['id'] ?>"><?= $cat['nome'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div class="search">
                <form method="GET">
                    <input type="text" name="modelo" placeholder="Pesquisar modelo">
                    <input type="number" name="ano" placeholder="Pesquisar ano">
                    <button type="submit">Buscar</button>
                </form>
            </div>
            <?php if (isAdmin()): ?>
                <a href="admin.php" class="admin-btn">Admin</a>
                <a href="logout.php" class="logout-btn">Sair</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <h2>Veículos Disponíveis</h2>
        
        <?php if (empty($veiculos)): ?>
            <p>Nenhum veículo encontrado.</p>
        <?php else: ?>
            <div class="veiculos-grid">
                <?php foreach ($veiculos as $veiculo): ?>
                    <div class="veiculo-card">
                        <a href="veiculo.php?id=<?= $veiculo['id'] ?>">
                            <div class="veiculo-img">
                                <?php if ($veiculo['imagem']): ?>
                                    <img src="<?= $veiculo['imagem'] ?>" alt="<?= $veiculo['modelo'] ?>">
                                <?php else: ?>
                                    <div class="sem-img">Sem imagem</div>
                                <?php endif; ?>
                            </div>
                            <div class="veiculo-info">
                                <h3><?= $veiculo['marca'] ?> <?= $veiculo['modelo'] ?></h3>
                                <p><?= $veiculo['categoria'] ?> • <?= $veiculo['ano'] ?></p>
                                <p class="preco"><?= formatarPreco($veiculo['preco']) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>