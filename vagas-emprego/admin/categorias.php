<?php
// arquivo: admin/categorias.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/funcoes.php';

require_once 'header-admin.php';


$erro = '';
$sucesso = '';

// Adicionar/Editar categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $id = $_POST['id'] ?? null;
    
    if (empty($nome)) {
        $erro = 'Por favor, informe o nome da categoria.';
    } else {
        if ($id) {
            // Atualizar categoria existente
            $stmt = $pdo->prepare("UPDATE categorias SET nome = ? WHERE id = ?");
            $stmt->execute([$nome, $id]);
            $sucesso = 'Categoria atualizada com sucesso!';
        } else {
            // Inserir nova categoria
            try {
                $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (?)");
                $stmt->execute([$nome]);
                $sucesso = 'Categoria cadastrada com sucesso!';
            } catch (PDOException $e) {
                if ($e->errorInfo[1] == 1062) {
                    $erro = 'Esta categoria já existe.';
                } else {
                    $erro = 'Erro ao cadastrar categoria.';
                }
            }
        }
    }
}

// Excluir categoria
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    
    // Verificar se há vagas associadas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM vagas WHERE categoria_id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->fetchColumn() > 0) {
        $erro = 'Não é possível excluir esta categoria pois existem vagas associadas a ela.';
    } else {
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        $sucesso = 'Categoria excluída com sucesso!';
    }
}

// Editar categoria - carregar dados
$categoria = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$categoria) {
        redirect('categorias.php');
    }
}

// Listar categorias
$categorias = getCategorias($pdo);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $categoria ? 'Editar Categoria' : 'Adicionar Nova Categoria' ?></h2>
    <a href="categorias.php" class="btn btn-secondary">Voltar para Lista</a>
</div>

<?php if ($erro): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php elseif ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id" value="<?= $categoria ? $categoria['id'] : '' ?>">
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Categoria *</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?= $categoria ? htmlspecialchars($categoria['nome']) : '' ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar Categoria</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Todas as Categorias
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td><?= $cat['id'] ?></td>
                                <td><?= htmlspecialchars($cat['nome']) ?></td>
                                <td>
                                    <a href="categorias.php?editar=<?= $cat['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="categorias.php?excluir=<?= $cat['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                        Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>