<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Oferta de Veículos</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Oferta de Veículos</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM Categoria");
                    while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li><a href="veiculos/categoria.php?id=' . $categoria['id'] . '">' . $categoria['nome'] . '</a></li>';
                    }
                    ?>
                    <li>
                        <form action="pesquisar.php" method="get" class="search-form">
                            <input type="text" name="modelo" placeholder="Pesquisar por modelo">
                            <button type="submit">Buscar</button>
                        </form>
                    </li>
                    <li>
                        <form action="pesquisar.php" method="get" class="search-form">
                            <input type="text" name="ano" placeholder="Pesquisar por ano">
                            <button type="submit">Buscar</button>
                        </form>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="admin/veiculos/listar.php">Gerenciar Veículos</a></li>
                        <li><a href="admin/categorias/listar.php">Gerenciar Categorias</a></li>
                        <li><a href="auth/logout.php">Sair</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php">Login</a></li>
                        <li><a href="auth/registrar.php">Registrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">


