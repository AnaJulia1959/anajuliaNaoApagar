<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$veiculo_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'];
    $cor = $_POST['cor'];
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $preco = $_POST['preco'];
    $quilometragem = $_POST['quilometragem'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];
    
    // Upload da nova imagem (se fornecida)
    $imagem = $_POST['imagem_atual'];
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        // Remove a imagem antiga se existir
        if ($imagem && file_exists("../../assets/images/uploads/$imagem")) {
            unlink("../../assets/images/uploads/$imagem");
        }
        
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem = uniqid() . '.' . $extensao;
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../../assets/images/uploads/$imagem");
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE Veiculo SET placa = ?, cor = ?, modelo = ?, marca = ?, ano = ?, preco = ?, quilometragem = ?, descricao = ?, imagem = ?, id_categoria = ? WHERE id = ?");
        $stmt->execute([$placa, $cor, $modelo, $marca, $ano, $preco, $quilometragem, $descricao, $imagem, $id_categoria, $veiculo_id]);
        
        header("Location: listar.php");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar veículo: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM Veiculo WHERE id = ?");
$stmt->execute([$veiculo_id]);
$veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$veiculo) {
    header("Location: listar.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM Categoria");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
?>

<h2>Editar Veículo</h2>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="form-veiculo">
    <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($veiculo['imagem']); ?>">
    
    <div class="form-group">
        <label for="placa">Placa:</label>
        <input type="text" id="placa" name="placa" value="<?php echo htmlspecialchars($veiculo['placa']); ?>" required>
    </div>
    <div class="form-group">
        <label for="cor">Cor:</label>
        <input type="text" id="cor" name="cor" value="<?php echo htmlspecialchars($veiculo['cor']); ?>" required>
    </div>
    <div class="form-group">
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($veiculo['modelo']); ?>" required>
    </div>
    <div class="form-group">
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($veiculo['marca']); ?>" required>
    </div>
    <div class="form-group">
        <label for="ano">Ano:</label>
        <input type="number" id="ano" name="ano" value="<?php echo htmlspecialchars($veiculo['ano']); ?>" required>
    </div>
    <div class="form-group">
        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" value="<?php echo htmlspecialchars($veiculo['preco']); ?>" required>
    </div>
    <div class="form-group">
        <label for="quilometragem">Quilometragem:</label>
        <input type="number" id="quilometragem" name="quilometragem" value="<?php echo htmlspecialchars($veiculo['quilometragem']); ?>">
    </div>
    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($veiculo['descricao']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="id_categoria">Categoria:</label>
        <select id="id_categoria" name="id_categoria" required>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id']; ?>" <?php echo $categoria['id'] == $veiculo['id_categoria'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($categoria['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="imagem">Imagem:</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">
        <?php if ($veiculo['imagem']): ?>
            <p>Imagem atual:</p>
            <img src="../../assets/images/uploads/<?php echo htmlspecialchars($veiculo['imagem']); ?>" alt="Imagem do veículo" width="100">
        <?php endif; ?>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Atualizar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once '../../includes/footer.php'; ?>