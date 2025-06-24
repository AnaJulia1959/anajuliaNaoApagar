<?php
// arquivo: usuario/candidaturas.php

require_once '../includes/config.php';
require_once '../includes/funcoes.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../index.php');
}

// Obter candidaturas do usuário
$stmt = $pdo->prepare("SELECT v.*, c.data_candidatura 
                      FROM vagas v 
                      JOIN candidaturas c ON v.id = c.vaga_id 
                      WHERE c.usuario_id = ? 
                      ORDER BY c.data_candidatura DESC");
$stmt->execute([$_SESSION['user_id']]);
$candidaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h2>Minhas Candidaturas</h2>

<div class="row mt-4">
    <div class="col-md-12">
        <?php if (empty($candidaturas)): ?>
            <div class="alert alert-info">Você ainda não se candidatou a nenhuma vaga.</div>
            <a href="vagas.php" class="btn btn-primary">Ver Vagas Disponíveis</a>
        <?php else: ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vagas que você se candidatou</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vaga</th>
                                    <th>Empresa</th>
                                    <th>Data da Candidatura</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidaturas as $vaga): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($vaga['titulo']) ?></td>
                                        <td><?= htmlspecialchars($vaga['empresa']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($vaga['data_candidatura'])) ?></td>
                                        <td>
                                            <span class="badge <?= $vaga['ativa'] ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= $vaga['ativa'] ? 'Ativa' : 'Inativa' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="detalhes_vaga.php?id=<?= $vaga['id'] ?>" class="btn btn-sm btn-primary">Ver Vaga</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>