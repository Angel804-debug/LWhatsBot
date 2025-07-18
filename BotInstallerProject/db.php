<?php
// db.php - Conexão com o banco
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'bot_php';
$conn = mysqli_connect($servidor, $usuario, $senha, $banco);
if (!$conn) {
    die('Erro na conexão com o banco de dados: ' . mysqli_connect_error());
}
?>