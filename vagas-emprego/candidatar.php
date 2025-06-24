<?php
require_once 'includes/config.php';
require_once 'includes/funcoes.php';

if (!isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg_tipo'] = 'erro';
    $_SESSION['msg'] = 'Vaga inválida';
    header("Location: index.php");
    exit();
}

$vaga_id = $_GET['id'];
$usuario_id = $_SESSION['user_id'];

if (jaCandidatou($pdo, $usuario_id, $vaga_id)) {
    $_SESSION['msg_tipo'] = 'aviso';
    $_SESSION['msg'] = 'Você já se candidatou a esta vaga!';
} else {
    try {
        $stmt = $pdo->prepare("INSERT INTO candidaturas (vaga_id, usuario_id) VALUES (?, ?)");
        $stmt->execute([$vaga_id, $usuario_id]);
        
        $_SESSION['msg_tipo'] = 'sucesso';
        $_SESSION['msg'] = 'Candidatura realizada com sucesso! ✅';
    } catch (PDOException $e) {
        $_SESSION['msg_tipo'] = 'erro';
        $_SESSION['msg'] = 'Erro ao se candidatar. Por favor, tente novamente.';
    }
}

header("Location: index.php");
exit();