<?php
require_once 'db.php';
require_once 'funcoes.php';

$numero_telefone = filter_input(INPUT_GET, 'telefone', FILTER_SANITIZE_STRING);
$msg = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING);
$usuario = filter_input(INPUT_GET, 'usuario', FILTER_SANITIZE_EMAIL);

$usuarioData = buscarUsuario($conn, $numero_telefone);

if (!$usuarioData) {
    inserirUsuario($conn, $numero_telefone);
    $status = 1;
} else {
    $status = $usuarioData['status'];
}

$resposta = obterRespostaPorStatus($status);

echo $resposta;

// Atualiza status (reset se passou do menu final)
$novo_status = ($status >= 5) ? 1 : $status + 1;
atualizarStatus($conn, $numero_telefone, $novo_status);

registrarHistorico($conn, $numero_telefone, $msg, $resposta);
?>