<?php
// arquivo: admin/vagas.php

require_once 'header-admin.php';
// Substitua esta linha (ou adicione se não existir):
require_once '../includes/funcoes.php';

$erro = '';
$sucesso = '';
$categorias = getCategorias($pdo);

// Adicionar/Editar vaga
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $requisitos = trim($_POST['requisitos']);
    $salario = trim($_POST['salario']);
    $empresa = trim($_POST['empresa']);
    $localizacao = trim($_POST['localizacao']);
    $contato_email = trim($_POST['contato_email']);
    $contato_telefone = trim($_POST['contato_telefone']);
    $categoria_id = $_POST['categoria_id'];
    $ativa = isset($_POST['ativa']) ? 1 : 0;
    $id = $_POST['id'] ?? null;
    
    // Validações
    if (empty($titulo) || empty($descricao) || empty($requisitos) || empty($empresa) || empty($categoria_id)) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    } else {
        // Upload do logo da empresa
        $logo_empresa = null;
        if (isset($_FILES['logo_empresa']) && $_FILES['logo_empresa']['error'] === UPLOAD_ERR_OK) {
            $logo_empresa = uploadImagem($_FILES['logo_empresa'], '../uploads/empresas/');
        } elseif ($id) {
            // Manter o logo existente se não for enviado um novo
            $stmt = $pdo->prepare("SELECT logo_empresa FROM vagas WHERE id = ?");
            $stmt->execute([$id]);
            $vaga_existente = $stmt->fetch();
            $logo_empresa = $vaga_existente['logo_empresa'];
        }
        
        if ($id) {
            // Atualizar vaga existente
            $stmt = $pdo->prepare("UPDATE vagas SET 
                                  titulo = ?, descricao = ?, requisitos = ?, salario = ?, 
                                  empresa = ?, logo_empresa = ?, localizacao = ?, 
                                  contato_email = ?, contato_telefone = ?, 
                                  categoria_id = ?, ativa = ? 
                                  WHERE id = ?");
            $stmt->execute([
                $titulo, $descricao, $requisitos, $salario, 
                $empresa, $logo_empresa, $localizacao, 
                $contato_email, $contato_telefone, 
                $categoria_id, $ativa, $id
            ]);
            $sucesso = 'Vaga atualizada com sucesso!';
        } else {
            // Inserir nova vaga
            $stmt = $pdo->prepare("INSERT INTO vagas 
                                  (titulo, descricao, requisitos, salario, empresa, 
                                  logo_empresa, localizacao, contato_email, contato_telefone, 
                                  categoria_id, ativa, admin_id) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $titulo, $descricao, $requisitos, $salario, $empresa, 
                $logo_empresa, $localizacao, $contato_email, $contato_telefone, 
                $categoria_id, $ativa, $_SESSION['user_id']
            ]);
            $sucesso = 'Vaga cadastrada com sucesso!';
        }
    }
}

// Editar vaga - carregar dados
$vaga = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM vagas WHERE id = ?");
    $stmt->execute([$id]);
    $vaga = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vaga) {
        redirect('vagas.php');
    }
}

// Listar vagas
$stmt = $pdo->query("SELECT v.*, c.nome as categoria_nome 
                     FROM vagas v 
                     JOIN categorias c ON v.categoria_id = c.id 
                     ORDER BY v.data_publicacao DESC");
$vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $vaga ? 'Editar Vaga' : 'Adicionar Nova Vaga' ?></h2>
    <a href="vagas.php" class="btn btn-secondary">Voltar para Lista</a>
</div>

<?php if ($erro): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php elseif ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $vaga ? $vaga['id'] : '' ?>">
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título da Vaga *</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" 
                               value="<?= $vaga ? htmlspecialchars($vaga['titulo']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição da Vaga *</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="5" required><?= $vaga ? htmlspecialchars($vaga['descricao']) : '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="requisitos" class="form-label">Requisitos *</label>
                        <textarea class="form-control" id="requisitos" name="requisitos" rows="5" required><?= $vaga ? htmlspecialchars($vaga['requisitos']) : '' ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="salario" class="form-label">Salário</label>
                            <input type="text" class="form-control" id="salario" name="salario" 
                                   value="<?= $vaga ? htmlspecialchars($vaga['salario']) : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categoria_id" class="form-label">Categoria *</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id'] ?>" 
                                        <?= ($vaga && $vaga['categoria_id'] == $categoria['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($categoria['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="empresa" class="form-label">Empresa *</label>
                        <input type="text" class="form-control" id="empresa" name="empresa" 
                               value="<?= $vaga ? htmlspecialchars($vaga['empresa']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="logo_empresa" class="form-label">Logo da Empresa</label>
                        <input type="file" class="form-control" id="logo_empresa" name="logo_empresa" accept="image/*">
                        <?php if ($vaga && $vaga['logo_empresa']): ?>
                            <div class="mt-2">
                                <img src="../<?= $vaga['logo_empresa'] ?>" alt="Logo da empresa" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="localizacao" class="form-label">Localização</label>
                            <input type="text" class="form-control" id="localizacao" name="localizacao" 
                                   value="<?= $vaga ? htmlspecialchars($vaga['localizacao']) : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ativa" name="ativa" 
                                       <?= ($vaga && $vaga['ativa']) || !$vaga ? 'checked' : '' ?>>
                                <label class="form-check-label" for="ativa">Vaga Ativa</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contato_email" class="form-label">Email para Contato</label>
                            <input type="email" class="form-control" id="contato_email" name="contato_email" 
                                   value="<?= $vaga ? htmlspecialchars($vaga['contato_email']) : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contato_telefone" class="form-label">Telefone para Contato</label>
                            <input type="text" class="form-control" id="contato_telefone" name="contato_telefone" 
                                   value="<?= $vaga ? htmlspecialchars($vaga['contato_telefone']) : '' ?>">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar Vaga</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Todas as Vagas
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach ($vagas as $v): ?>
                        <a href="vagas.php?editar=<?= $v['id'] ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($v['titulo']) ?>
                            <span class="badge <?= $v['ativa'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $v['ativa'] ? 'Ativa' : 'Inativa' ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>