<?php
// arquivo: admin/dashboard.php

require_once 'header-admin.php';

// Contar vagas ativas
$stmt = $pdo->query("SELECT COUNT(*) FROM vagas WHERE ativa = TRUE");
$vagas_ativas = $stmt->fetchColumn();

// Contar vagas inativas
$stmt = $pdo->query("SELECT COUNT(*) FROM vagas WHERE ativa = FALSE");
$vagas_inativas = $stmt->fetchColumn();

// Contar candidaturas
$stmt = $pdo->query("SELECT COUNT(*) FROM candidaturas");
$total_candidaturas = $stmt->fetchColumn();

// Contar usuários
$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'usuario'");
$total_usuarios = $stmt->fetchColumn();
?>

<h2>Dashboard</h2>

<div class="row mt-4">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Vagas Ativas</h5>
                <p class="card-text display-4"><?= $vagas_ativas ?></p>
                <a href="vagas.php" class="text-white">Ver todas</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">Vagas Inativas</h5>
                <p class="card-text display-4"><?= $vagas_inativas ?></p>
                <a href="vagas.php" class="text-white">Ver todas</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Candidaturas</h5>
                <p class="card-text display-4"><?= $total_candidaturas ?></p>
                <a href="candidatos.php" class="text-white">Ver todas</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Usuários</h5>
                <p class="card-text display-4"><?= $total_usuarios ?></p>
                <a href="#" class="text-white">Ver todos</a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        Últimas Vagas Cadastradas
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Empresa</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT v.*, c.nome as categoria_nome 
                                    FROM vagas v 
                                    JOIN categorias c ON v.categoria_id = c.id 
                                    ORDER BY v.data_publicacao DESC LIMIT 5");
                $vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($vagas as $vaga):
                ?>
                <tr>
                    <td><?= htmlspecialchars($vaga['titulo']) ?></td>
                    <td><?= htmlspecialchars($vaga['empresa']) ?></td>
                    <td><?= date('d/m/Y', strtotime($vaga['data_publicacao'])) ?></td>
                    <td>
                        <span class="badge <?= $vaga['ativa'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $vaga['ativa'] ? 'Ativa' : 'Inativa' ?>
                        </span>
                    </td>
                    <td>
                        <a href="vagas.php?editar=<?= $vaga['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>