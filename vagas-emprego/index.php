<?php
require_once 'includes/config.php';
requireAuth(); // Adicionar esta linha no início

// Restante do código...
// arquivo: index.php

require_once 'includes/config.php';
require_once 'includes/funcoes.php';

$categorias = getCategorias($pdo);
$vagas = getVagasAtivas($pdo);

require_once 'includes/header.php';
?>

<h1 class="mb-4">Vagas de Emprego Disponíveis</h1>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
        <?php
// Exibir mensagem de feedback
if (isset($_SESSION['msg'])) {
    $alert_class = '';
    switch ($_SESSION['msg_tipo']) {
        case 'sucesso':
            $alert_class = 'alert-success';
            break;
        case 'erro':
            $alert_class = 'alert-danger';
            break;
        case 'aviso':
            $alert_class = 'alert-warning';
            break;
    }
    
    echo '<div class="alert '.$alert_class.' alert-dismissible fade show text-center" role="alert" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1000;">
            '.$_SESSION['msg'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    
    // Limpar a mensagem após exibir
    unset($_SESSION['msg']);
    unset($_SESSION['msg_tipo']);
}
?>
            <div class="card-header bg-primary text-white">
                Categorias
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action">Todas as Categorias</a>
                <?php foreach ($categorias as $categoria): ?>
                    <a href="index.php?categoria=<?= $categoria['id'] ?>" 
                       class="list-group-item list-group-item-action">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <?php if (isset($_GET['categoria'])): ?>
            <?php 
            $categoria_id = $_GET['categoria'];
            $vagas = getVagasAtivas($pdo, $categoria_id);
            ?>
            <h3>Vagas na categoria: <?= htmlspecialchars($categorias[array_search($categoria_id, array_column($categorias, 'id'))]['nome']) ?></h3>
        <?php endif; ?>

        <?php if (empty($vagas)): ?>
            <div class="alert alert-info">Nenhuma vaga disponível no momento.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($vagas as $vaga): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if ($vaga['logo_empresa']): ?>
                                <img src="<?= $vaga['logo_empresa'] ?>" class="card-img-top p-3" alt="Logo da empresa" style="max-height: 150px; object-fit: contain;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($vaga['titulo']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($vaga['empresa']) ?></h6>
                                <p class="card-text"><?= nl2br(htmlspecialchars(substr($vaga['descricao'], 0, 150))) ?>...</p>
                                <span class="badge bg-primary"><?= htmlspecialchars($vaga['categoria_nome']) ?></span>
                            </div>
                            <div class="card-footer bg-transparent">
                            <a href="candidatar.php?id=<?= $vaga['id'] ?>" class="btn btn-success">Candidatar-se</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>