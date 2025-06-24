<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Usuario (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);
        
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_nome'] = $nome;
        $_SESSION['user_email'] = $email;
        
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $erro = "Este email já está em uso.";
        } else {
            $erro = "Erro ao registrar: " . $e->getMessage();
        }
    }
}
?>

<h2>Registrar</h2>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<form method="post" class="form-register">
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Registrar</button>
        <a href="login.php" class="btn btn-secondary">Já tem conta? Login</a>
    </div>
</form>

<?php require_once '../includes/footer.php'; ?>