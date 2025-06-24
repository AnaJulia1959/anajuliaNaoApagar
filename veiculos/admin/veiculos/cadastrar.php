<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';
redirectIfNotLoggedIn();

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
    
    // Processamento da imagem (upload ou URL)
    $imagem = '';
    
    // Verifica se foi enviado um arquivo de imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem = uniqid() . '.' . $extensao;
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../../assets/images/uploads/' . $imagem);
    } 
    // Verifica se foi informada uma URL de imagem
    elseif (!empty($_POST['imagem_url'])) {
        $imagem = $_POST['imagem_url'];
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Veiculo (placa, cor, modelo, marca, ano, preco, quilometragem, descricao, imagem, id_categoria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$placa, $cor, $modelo, $marca, $ano, $preco, $quilometragem, $descricao, $imagem, $id_categoria]);
        
        header("Location: listar.php");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar veículo: " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM Categoria");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
?>

<h2>Cadastrar Novo Veículo</h2>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="form-veiculo">
    <div class="form-group">
        <label for="placa">Placa:</label>
        <input type="text" id="placa" name="placa" required>
    </div>
    <div class="form-group">
        <label for="cor">Cor:</label>
        <input type="text" id="cor" name="cor" required>
    </div>
    <div class="form-group">
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" required>
    </div>
    <div class="form-group">
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" required>
    </div>
    <div class="form-group">
        <label for="ano">Ano:</label>
        <input type="number" id="ano" name="ano" required>
    </div>
    <div class="form-group">
        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="quilometragem">Quilometragem:</label>
        <input type="number" id="quilometragem" name="quilometragem">
    </div>
    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"></textarea>
    </div>
    <div class="form-group">
        <label for="id_categoria">Categoria:</label>
        <select id="id_categoria" name="id_categoria" required>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['nome']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="imagem">Imagem (upload):</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">
    </div>
    <div class="form-group">
        <label for="imagem_url">Ou URL da Imagem:</label>
        <input type="text" id="imagem_url" name="imagem_url" placeholder="https://exemplo.com/imagem.jpg">
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Cadastrar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once '../../includes/footer.php'; ?>