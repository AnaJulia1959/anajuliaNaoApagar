<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

$stmt = $pdo->query("SELECT v.*, c.nome as categoria_nome FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id ORDER BY v.created_at DESC");
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
?>

<h2>Gerenciar Veículos</h2>
<a href="cadastrar.php" class="btn">Cadastrar Novo Veículo</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Imagem</th>
            <th>Modelo</th>
            <th>Marca</th>
            <th>Categoria</th>
            <th>Ano</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($veiculos as $veiculo): ?>
            <tr>
                <td><?php echo $veiculo['id']; ?></td>
                <td><img src="assets/images/uploads/<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="<?php echo htmlspecialchars($veiculo['modelo']); ?>" width="50"></td>
                <td><?php echo htmlspecialchars($veiculo['modelo']); ?></td>
                <td><?php echo htmlspecialchars($veiculo['marca']); ?></td>
                <td><?php echo htmlspecialchars($veiculo['categoria_nome']); ?></td>
                <td><?php echo htmlspecialchars($veiculo['ano']); ?></td>
                <td>R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></td>
                <td>
                    <a href="editar.php?id=<?php echo $veiculo['id']; ?>" class="btn btn-sm">Editar</a>
                    <a href="excluir.php?id=<?php echo $veiculo['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este veículo?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../../includes/footer.php'; ?>