<?php
// arquivo: admin/candidatos.php

require_once 'header-admin.php';

// Se uma vaga específica for selecionada
$vaga_id = $_GET['vaga'] ?? null;
$vaga = null;
$candidatos = [];

if ($vaga_id) {
    // Obter detalhes da vaga (agora incluindo o status 'ativa')
    $stmt = $pdo->prepare("SELECT titulo, ativa FROM vagas WHERE id = ?");
    $stmt->execute([$vaga_id]);
    $vaga = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Obter candidatos para a vaga
    $stmt = $pdo->prepare("SELECT u.id, u.nome, u.email, u.foto, u.linkedin, c.data_candidatura 
                          FROM usuarios u 
                          JOIN candidaturas c ON u.id = c.usuario_id 
                          WHERE c.vaga_id = ? 
                          ORDER BY c.data_candidatura DESC");
    $stmt->execute([$vaga_id]);
    $candidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Listar todas as vagas para seleção
$stmt = $pdo->query("SELECT id, titulo FROM vagas ORDER BY titulo");
$vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Candidatos</h2>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                Selecionar Vaga
            </div>
            <div class="card-body">
                <form method="get" action="candidatos.php">
                    <div class="mb-3">
                        <label for="vaga" class="form-label">Vaga</label>
                        <select class="form-select" id="vaga" name="vaga" onchange="this.form.submit()">
                            <option value="">Selecione uma vaga</option>
                            <?php foreach ($vagas as $v): ?>
                                <option value="<?= $v['id'] ?>" 
                                    <?= $vaga_id == $v['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['titulo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <?php if ($vaga_id): ?>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span>Candidatos para: <?= htmlspecialchars($vaga['titulo']) ?></span>
                        <span class="badge bg-<?= $vaga['ativa'] ? 'success' : 'secondary' ?> ms-2">
                            <?= $vaga['ativa'] ? 'Ativa' : 'Inativa' ?>
                        </span>
                    </div>
                    <span class="badge bg-primary"><?= count($candidatos) ?> candidatos</span>
                </div>
                <div class="card-body">
                    <?php if (empty($candidatos)): ?>
                        <div class="alert alert-info">Nenhum candidato para esta vaga no momento.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>LinkedIn</th>
                                        <th>Data Candidatura</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($candidatos as $candidato): ?>
                                        <tr>
                                            <td>
                                                <?php if ($candidato['foto']): ?>
                                                    <img src="../<?= $candidato['foto'] ?>" alt="Foto" class="rounded-circle" width="40" height="40">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <span class="text-white"><?= strtoupper(substr($candidato['nome'], 0, 1)) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($candidato['nome']) ?></td>
                                            <td><a href="mailto:<?= htmlspecialchars($candidato['email']) ?>"><?= htmlspecialchars($candidato['email']) ?></a></td>
                                            <td>
                                                <?php if ($candidato['linkedin']): ?>
                                                    <a href="<?= htmlspecialchars($candidato['linkedin']) ?>" target="_blank">
                                                        <i class="fab fa-linkedin"></i> LinkedIn
                                                    </a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($candidato['data_candidatura'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Selecione uma vaga para visualizar os candidatos.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>