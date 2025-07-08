
<?php
require_once 'config.php';
requerLogin();

// Ativar exibição de erros durante o desenvolvimento
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ação padrão (listar veículos)
$acao = $_GET['acao'] ?? 'veiculos';

// Processar formulários enviados por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($acao === 'add-veiculo') {
            $dados = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $imagem = uploadImagem($_FILES['imagem']);
            
            $stmt = $pdo->prepare("INSERT INTO Veiculo (placa, cor, modelo, marca, ano, preco, descricao, imagem, id_categoria) 
                VALUES (:placa, :cor, :modelo, :marca, :ano, :preco, :descricao, :imagem, :id_categoria)");
            
            $stmt->execute([
                ':placa' => $dados['placa'],
                ':cor' => $dados['cor'],
                ':modelo' => $dados['modelo'],
                ':marca' => $dados['marca'],
                ':ano' => (int)$dados['ano'],
                ':preco' => (float)$dados['preco'],
                ':descricao' => $dados['descricao'],
                ':imagem' => $imagem,
                ':id_categoria' => (int)$dados['id_categoria']
            ]);
            
            header("Location: admin.php?success=veiculo_adicionado");
            exit();
        } elseif ($acao === 'edit-veiculo') {
            $id = (int)$_GET['id'];
            $dados = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $stmt = $pdo->prepare("SELECT id FROM Veiculo WHERE id = :id");
            $stmt->execute([':id' => $id]);
            if (!$stmt->fetch()) {
                header("Location: admin.php?error=veiculo_nao_encontrado");
                exit();
            }

            if ($_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = uploadImagem($_FILES['imagem']);
                $stmt = $pdo->prepare("UPDATE Veiculo SET 
                    placa = :placa, 
                    cor = :cor, 
                    modelo = :modelo, 
                    marca = :marca, 
                    ano = :ano, 
                    preco = :preco, 
                    descricao = :descricao, 
                    imagem = :imagem, 
                    id_categoria = :id_categoria 
                    WHERE id = :id");

                $stmt->execute([
                    ':placa' => $dados['placa'],
                    ':cor' => $dados['cor'],
                    ':modelo' => $dados['modelo'],
                    ':marca' => $dados['marca'],
                    ':ano' => (int)$dados['ano'],
                    ':preco' => (float)$dados['preco'],
                    ':descricao' => $dados['descricao'],
                    ':imagem' => $imagem,
                    ':id_categoria' => (int)$dados['id_categoria'],
                    ':id' => $id
                ]);
            } else {
                $stmt = $pdo->prepare("UPDATE Veiculo SET 
                    placa = :placa, 
                    cor = :cor, 
                    modelo = :modelo, 
                    marca = :marca, 
                    ano = :ano, 
                    preco = :preco, 
                    descricao = :descricao, 
                    id_categoria = :id_categoria 
                    WHERE id = :id");

                $stmt->execute([
                    ':placa' => $dados['placa'],
                    ':cor' => $dados['cor'],
                    ':modelo' => $dados['modelo'],
                    ':marca' => $dados['marca'],
                    ':ano' => (int)$dados['ano'],
                    ':preco' => (float)$dados['preco'],
                    ':descricao' => $dados['descricao'],
                    ':id_categoria' => (int)$dados['id_categoria'],
                    ':id' => $id
                ]);
            }

            header("Location: admin.php?success=veiculo_atualizado");
            exit();
        } elseif ($acao === 'add-categoria') {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare("INSERT INTO Categoria (nome) VALUES (:nome)");
            $stmt->execute([':nome' => $nome]);
            header("Location: admin.php?acao=categorias&success=categoria_adicionada");
            exit();
        } elseif ($acao === 'edit-categoria') {
            $id = (int)$_GET['id'];
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);

            $stmt = $pdo->prepare("UPDATE Categoria SET nome = :nome WHERE id = :id");
            $stmt->execute([':nome' => $nome, ':id' => $id]);
            header("Location: admin.php?acao=categorias&success=categoria_atualizada");
            exit();
        }
    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    }
}

// DELETE via GET (corrigido)
if ($acao === 'delete-veiculo') {
    try {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM Veiculo WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header("Location: admin.php?success=veiculo_excluido");
        exit();
    } catch (PDOException $e) {
        die("Erro ao excluir veículo: " . $e->getMessage());
    }
}

if ($acao === 'delete-categoria') {
    try {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM Categoria WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header("Location: admin.php?acao=categorias&success=categoria_excluida");
        exit();
    } catch (PDOException $e) {
        die("Erro ao excluir categoria: " . $e->getMessage());
    }
}

// Buscar veículo para edição/exclusão
if ($acao === 'edit-veiculo' || $acao === 'delete-veiculo') {
    $id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM Veiculo WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$veiculo) {
            header("Location: admin.php?error=veiculo_nao_encontrado");
            exit();
        }
    } catch (PDOException $e) {
        die("Erro ao buscar veículo: " . $e->getMessage());
    }
}

if ($acao === 'edit-categoria' || $acao === 'delete-categoria') {
    $id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM Categoria WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$categoria) {
            header("Location: admin.php?acao=categorias&error=categoria_nao_encontrada");
            exit();
        }
    } catch (PDOException $e) {
        die("Erro ao buscar categoria: " . $e->getMessage());
    }
}

// Buscar listagens (✅ agora sempre executado e inicializado)
$veiculos = [];
$categorias = [];

try {
    $veiculos = $pdo->query("SELECT v.*, c.nome as categoria FROM Veiculo v JOIN Categoria c ON v.id_categoria = c.id")->fetchAll(PDO::FETCH_ASSOC);
    $categorias = $pdo->query("SELECT * FROM Categoria")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Painel Admin</h1>
            <nav class="admin-nav">
                <a href="admin.php?acao=veiculos">Veículos</a>
                <a href="admin.php?acao=categorias">Categorias</a>
                <a href="index.php" class="voltar">Voltar ao site</a>
                <a href="logout.php" class="logout-btn">Sair</a>
            </nav>
        </div>
    </header>

    <main class="container admin-main">
        <!-- Mensagens de status -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                <?php
                $messages = [
                    'veiculo_adicionado' => 'Veículo adicionado com sucesso!',
                    'veiculo_atualizado' => 'Veículo atualizado com sucesso!',
                    'veiculo_excluido' => 'Veículo excluído com sucesso!',
                    'categoria_adicionada' => 'Categoria adicionada com sucesso!',
                    'categoria_atualizada' => 'Categoria atualizada com sucesso!',
                    'categoria_excluida' => 'Categoria excluída com sucesso!'
                ];
                echo $messages[$_GET['success']] ?? 'Operação realizada com sucesso!';
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                <?php
                $errors = [
                    'veiculo_nao_encontrado' => 'Veículo não encontrado!',
                    'categoria_nao_encontrada' => 'Categoria não encontrada!'
                ];
                echo $errors[$_GET['error']] ?? 'Ocorreu um erro!';
                ?>
            </div>
        <?php endif; ?>

        <?php if ($acao === 'veiculos'): ?>
            <div class="admin-header">
                <h2>Gerenciar Veículos</h2>
                <a href="admin.php?acao=add-veiculo" class="btn-add">Adicionar Veículo</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Categoria</th>
                        <th>Ano</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($veiculos as $v): ?>
                        <tr>
                            <td><?= htmlspecialchars($v['id']) ?></td>
                            <td>
                                <?php if ($v['imagem']): ?>
                                    <img src="<?= htmlspecialchars($v['imagem']) ?>" alt="<?= htmlspecialchars($v['modelo']) ?>" class="table-img">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($v['modelo']) ?></td>
                            <td><?= htmlspecialchars($v['marca']) ?></td>
                            <td><?= htmlspecialchars($v['categoria']) ?></td>
                            <td><?= htmlspecialchars($v['ano']) ?></td>
                            <td><?= formatarPreco($v['preco']) ?></td>
                            <td class="actions">
                                <a href="admin.php?acao=edit-veiculo&id=<?= $v['id'] ?>" class="btn-edit">Editar</a>
                                <a href="admin.php?acao=delete-veiculo&id=<?= $v['id'] ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir este veículo?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php elseif ($acao === 'add-veiculo' || $acao === 'edit-veiculo'): ?>
            <h2><?= $acao === 'add-veiculo' ? 'Adicionar' : 'Editar' ?> Veículo</h2>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Placa</label>
                        <input type="text" name="placa" value="<?= htmlspecialchars($veiculo['placa'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Cor</label>
                        <input type="text" name="cor" value="<?= htmlspecialchars($veiculo['cor'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" value="<?= htmlspecialchars($veiculo['modelo'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" value="<?= htmlspecialchars($veiculo['marca'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Ano</label>
                        <input type="number" name="ano" value="<?= htmlspecialchars($veiculo['ano'] ?? '') ?>" required min="1900" max="<?= date('Y') + 1 ?>">
                    </div>
                    <div class="form-group">
                        <label>Preço</label>
                        <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($veiculo['preco'] ?? '') ?>" required min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Categoria</label>
                    <select name="id_categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= isset($veiculo) && $veiculo['id_categoria'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" rows="4"><?= htmlspecialchars($veiculo['descricao'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Imagem</label>
                    <input type="file" name="imagem">
                    <?php if (isset($veiculo) && $veiculo['imagem']): ?>
                        <div class="current-image">
                            <p>Imagem atual:</p>
                            <img src="<?= htmlspecialchars($veiculo['imagem']) ?>" alt="Imagem atual" style="max-width: 200px; margin-top: 10px;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn-save">Salvar</button>
                <a href="admin.php" class="btn-cancel">Cancelar</a>
            </form>

        <?php elseif ($acao === 'categorias'): ?>
            <div class="admin-header">
                <h2>Gerenciar Categorias</h2>
                <a href="admin.php?acao=add-categoria" class="btn-add">Adicionar Categoria</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['id']) ?></td>
                            <td><?= htmlspecialchars($cat['nome']) ?></td>
                            <td class="actions">
                                <a href="admin.php?acao=edit-categoria&id=<?= $cat['id'] ?>" class="btn-edit">Editar</a>
                                <a href="admin.php?acao=delete-categoria&id=<?= $cat['id'] ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php elseif ($acao === 'add-categoria' || $acao === 'edit-categoria'): ?>
            <h2><?= $acao === 'add-categoria' ? 'Adicionar' : 'Editar' ?> Categoria</h2>
            
            <form method="POST" class="admin-form">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($categoria['nome'] ?? '') ?>" required>
                </div>
                
                <button type="submit" class="btn-save">Salvar</button>
                <a href="admin.php?acao=categorias" class="btn-cancel">Cancelar</a>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>