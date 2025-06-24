<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$categoria_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    
    try {
        $stmt = $pdo->prepare("UPDATE Categoria SET nome = ? WHERE id = ?");
        $stmt->execute([$nome, $categoria_id]);
        
        header("Location: listar.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $erro = "Esta categoria já existe.";
        } else {
            $erro = "Erro ao atualizar categoria: " . $e->getMessage();
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM Categoria WHERE id = ?");
$stmt->execute([$categoria_id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    header("Location: listar.php");
    exit();
}

require_once '../../includes/header.php';
?>

<h2>Editar Categoria</h2>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<form method="post" class="form-categoria">
    <div class="form-group">
        <label for="nome">Nome da Categoria:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($categoria['nome']); ?>" required>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Atualizar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once '../../includes/footer.php'; ?>