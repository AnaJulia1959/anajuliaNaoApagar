<?php
// arquivo: usuario/vagas.php

require_once '../includes/config.php';
require_once '../includes/funcoes.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../index.php');
}

$categoria_id = $_GET['categoria'] ?? null;
$categorias = getCategorias($pdo);
$vagas = getVagasAtivas($pdo, $categoria_id);

require_once '../includes/header.php';
?>

<h2>Vagas Disponíveis</h2>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Categorias
            </div>
            <div class="list-group list-group-flush">
                <a href="vagas.php" class="list-group-item list-group-item-action">Todas as Categorias</a>
                <?php foreach ($categorias as $categoria): ?>
                    <a href="vagas.php?categoria=<?= $categoria['id'] ?>" 
                       class="list-group-item list-group-item-action <?= $categoria_id == $categoria['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <?php if ($categoria_id): ?>
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
                                <img src="../<?= $vaga['logo_empresa'] ?>" class="card-img-top p-3" alt="Logo da empresa" style="max-height: 150px; object-fit: contain;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($vaga['titulo']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($vaga['empresa']) ?></h6>
                                <p class="card-text"><?= nl2br(htmlspecialchars(substr($vaga['descricao'], 0, 150))) ?>...</p>
                                <span class="badge bg-primary"><?= htmlspecialchars($vaga['categoria_nome']) ?></span>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="detalhes_vaga.php?id=<?= $vaga['id'] ?>" class="btn btn-primary">Ver Detalhes</a>
                                <?php if (jaCandidatou($pdo, $_SESSION['user_id'], $vaga['id'])): ?>
                                    <span class="badge bg-success ms-2">Já candidatado</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>