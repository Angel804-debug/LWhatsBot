<?php
require_once 'db.php';

$telefone = $_GET['telefone'] ?? '';
$data = $_GET['data'] ?? '';

$sql = "SELECT * FROM historico WHERE 1=1";

if (!empty($telefone)) {
    $telefone = mysqli_real_escape_string($conn, $telefone);
    $sql .= " AND telefone LIKE '%$telefone%'";
}

if (!empty($data)) {
    $data = mysqli_real_escape_string($conn, $data);
    $sql .= " AND data = '$data'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=historico.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Telefone', 'Mensagem Cliente', 'Mensagem Bot', 'Data']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit;