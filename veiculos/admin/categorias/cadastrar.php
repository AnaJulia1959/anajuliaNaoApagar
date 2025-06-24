<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Categoria (nome) VALUES (?)");
        $stmt->execute([$nome]);
        
        header("Location: listar.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $erro = "Esta categoria já existe.";
        } else {
            $erro = "Erro ao cadastrar categoria: " . $e->getMessage();
        }
    }
}

require_once '../../includes/header.php';
?>

<h2>Cadastrar Nova Categoria</h2>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<form method="post" class="form-categoria">
    <div class="form-group">
        <label for="nome">Nome da Categoria:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Cadastrar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once '../../includes/footer.php'; ?>