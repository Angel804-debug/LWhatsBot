<?php
// funcoes.php
function buscarUsuario($conn, $telefone) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM usuario WHERE telefone = ?");
    mysqli_stmt_bind_param($stmt, "s", $telefone);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($resultado);
}

function inserirUsuario($conn, $telefone) {
    $status = 1;
    $stmt = mysqli_prepare($conn, "INSERT INTO usuario (telefone, status) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "si", $telefone, $status);
    return mysqli_stmt_execute($stmt);
}

function atualizarStatus($conn, $telefone, $status) {
    $stmt = mysqli_prepare($conn, "UPDATE usuario SET status = ? WHERE telefone = ?");
    mysqli_stmt_bind_param($stmt, "is", $status, $telefone);
    return mysqli_stmt_execute($stmt);
}

function registrarHistorico($conn, $telefone, $msg_cliente, $msg_bot) {
    $data = date('Y-m-d');
    $stmt = mysqli_prepare($conn, "INSERT INTO historico (telefone, msg_cliente, msg_bot, data) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $telefone, $msg_cliente, $msg_bot, $data);
    return mysqli_stmt_execute($stmt);
}

function obterRespostaPorStatus($status) {
    $menus = [
        1 => 'Acredito que nossa empresa pode te ajudar com o que você precisa! Digite *sim* para verificar disponibilidade!',
        2 => 'Possuo disponibildade para te ajudar! Quer continuar?',
        3 => 'Em nosso site possuimos todo tipo de auxilio que desejar, quer receber o link?',
        4 => "Pois aqui está! o link: https://www.drogasil.com.br/central-de-atendimento",
        5 => '',
    ];
    return $menus[$status] ?? 'Muito obrigado pela sua atenção!';
}
?>