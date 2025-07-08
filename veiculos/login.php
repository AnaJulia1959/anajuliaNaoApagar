<?php
require_once 'config.php';

if (isAdmin()) {
    header("Location: admin.php");
    exit();
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM Admin WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['admin_logado'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="login-container">
        <h1>Login Administrativo</h1>
        <?php if ($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Usuário:</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div><br>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>