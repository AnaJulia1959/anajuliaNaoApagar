<?php
// arquivo: usuario/perfil.php

require_once '../includes/config.php';
require_once '../includes/funcoes.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

$erro = '';
$sucesso = '';

// Obter dados do usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    redirect('../logout.php');
}

// Atualizar perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $linkedin = trim($_POST['linkedin']);
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações básicas
    if (empty($nome) || empty($email)) {
        $erro = 'Nome e email são obrigatórios.';
    } else {
        // Verificar se email já existe (para outro usuário)
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        
        if ($stmt->fetch()) {
            $erro = 'Este email já está sendo usado por outro usuário.';
        } else {
            // Atualizar dados básicos
            $dados = [$nome, $email, $linkedin];
            $sql = "UPDATE usuarios SET nome = ?, email = ?, linkedin = ?";
            
            // Verificar se está tentando alterar a senha
            if (!empty($senha_atual)) {
                if (password_verify($senha_atual, $usuario['senha'])) {
                    if ($nova_senha === $confirmar_senha) {
                        if (strlen($nova_senha) >= 6) {
                            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                            $sql .= ", senha = ?";
                            $dados[] = $senha_hash;
                        } else {
                            $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
                        }
                    } else {
                        $erro = 'As senhas não coincidem.';
                    }
                } else {
                    $erro = 'Senha atual incorreta.';
                }
            }
            
            // Upload da nova foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $foto = uploadImagem($_FILES['foto'], '../uploads/usuarios/');
                if ($foto) {
                    // Remover foto antiga se existir
                    if ($usuario['foto'] && file_exists('../' . $usuario['foto'])) {
                        unlink('../' . $usuario['foto']);
                    }
                    $sql .= ", foto = ?";
                    $dados[] = $foto;
                    $_SESSION['user_foto'] = $foto;
                }
            }
            
            $sql .= " WHERE id = ?";
            $dados[] = $_SESSION['user_id'];
            
            if (empty($erro)) {
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($dados)) {
                    $_SESSION['user_nome'] = $nome;
                    $_SESSION['user_email'] = $email;
                    $sucesso = 'Perfil atualizado com sucesso!';
                    
                    // Recarregar dados do usuário
                    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $erro = 'Erro ao atualizar perfil. Tente novamente.';
                }
            }
        }
    }
}

require_once '../includes/header.php';
?>

<h2>Meu Perfil</h2>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <?php if ($usuario['foto']): ?>
                    <img src="../<?= $usuario['foto'] ?>" alt="Foto de perfil" class="rounded-circle mb-3" width="150" height="150">
                <?php else: ?>
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                        <span class="text-white display-4"><?= strtoupper(substr($usuario['nome'], 0, 1)) ?></span>
                    </div>
                <?php endif; ?>
                <h4><?= htmlspecialchars($usuario['nome']) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($usuario['email']) ?></p>
                <?php if ($usuario['linkedin']): ?>
                    <a href="<?= htmlspecialchars($usuario['linkedin']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Editar Perfil
            </div>
            <div class="card-body">
                <?php if ($erro): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php elseif ($sucesso): ?>
                    <div class="alert alert-success"><?= $sucesso ?></div>
                <?php endif; ?>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo *</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="linkedin" class="form-label">Link do LinkedIn</label>
                        <input type="url" class="form-control" id="linkedin" name="linkedin" 
                               value="<?= htmlspecialchars($usuario['linkedin']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto de Perfil</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Alterar Senha</h5>
                    <div class="mb-3">
                        <label for="senha_atual" class="form-label">Senha Atual</label>
                        <input type="password" class="form-control" id="senha_atual" name="senha_atual">
                        <small class="text-muted">Preencha apenas se desejar alterar a senha.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nova_senha" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="nova_senha" name="nova_senha">
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>