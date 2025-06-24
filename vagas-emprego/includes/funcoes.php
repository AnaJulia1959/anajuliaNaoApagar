<?php
// arquivo: includes/funcoes.php

require_once 'config.php';

// Função para upload de imagem
function uploadImagem($file, $pasta = 'uploads/') {
    if (!file_exists($pasta)) {
        mkdir($pasta, 0777, true);
    }
    
    $extensao = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nomeUnico = uniqid() . '.' . $extensao;
    $caminho = $pasta . $nomeUnico;
    
    if (move_uploaded_file($file['tmp_name'], $caminho)) {
        return $caminho;
    }
    
    return null;
}

// Função para buscar categorias
function getCategorias($pdo) {
    $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para buscar vagas ativas
function getVagasAtivas($pdo, $categoria_id = null) {
    $sql = "SELECT v.*, c.nome as categoria_nome 
            FROM vagas v 
            JOIN categorias c ON v.categoria_id = c.id 
            WHERE v.ativa = TRUE";
    
    if ($categoria_id) {
        $sql .= " AND v.categoria_id = :categoria_id";
    }
    
    $sql .= " ORDER BY v.data_publicacao DESC";
    
    $stmt = $pdo->prepare($sql);
    
    if ($categoria_id) {
        $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para verificar se usuário já se candidatou a vaga
function jaCandidatou($pdo, $usuario_id, $vaga_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM candidaturas 
                          WHERE usuario_id = ? AND vaga_id = ?");
    $stmt->execute([$usuario_id, $vaga_id]);
    return $stmt->fetchColumn() > 0;
}
?>