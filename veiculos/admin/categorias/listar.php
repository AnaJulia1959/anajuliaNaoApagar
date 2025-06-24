<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

$stmt = $pdo->query("SELECT * FROM Categoria ORDER BY nome");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
?>

<h2>Gerenciar Categorias</h2>
<a href="cadastrar.php" class="btn">Cadastrar Nova Categoria</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $categoria): ?>
            <tr>
                <td><?php echo $categoria['id']; ?></td>
                <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                <td>
                    <a href="editar.php?id=<?php echo $categoria['id']; ?>" class="btn btn-sm">Editar</a>
                    <a href="excluir.php?id=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../../includes/footer.php'; ?>