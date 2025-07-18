<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>




<?php
require_once 'db.php';

$filtro_telefone = $_GET['telefone'] ?? '';
$filtro_data = $_GET['data'] ?? '';

$sql = "SELECT * FROM historico WHERE 1=1";

if (!empty($filtro_telefone)) {
    $telefone = mysqli_real_escape_string($conn, $filtro_telefone);
    $sql .= " AND telefone LIKE '%$telefone%'";
}

if (!empty($filtro_data)) {
    $data = mysqli_real_escape_string($conn, $filtro_data);
    $sql .= " AND data = '$data'";
}

$sql .= " ORDER BY id DESC";
$resultado = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel do Bot</title>
  <link rel="stylesheet" href="css/logout.css">
  <link rel="stylesheet" href="css/painel.css">
</head>
<body>
  <div style="text-align: right; padding: 16px;">
    <a href="logout.php" class="logout-btn">Sair</a>
</div>
  <h1>Painel do Bot</h1>

  <form method="GET" action="painel.php">
    <input type="text" name="telefone" placeholder="Filtrar por telefone" value="<?= htmlspecialchars($filtro_telefone) ?>">
    <input type="date" name="data" value="<?= htmlspecialchars($filtro_data) ?>">
    <button type="submit">Filtrar</button>
    <a href="exportar_csv.php?telefone=<?= $filtro_telefone ?>&data=<?= $filtro_data ?>">📁 Exportar CSV</a>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Telefone</th>
        <th>Mensagem Cliente</th>
        <th>Mensagem Bot</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($resultado)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['telefone'] ?></td>
        <td><?= htmlspecialchars($row['msg_cliente']) ?></td>
        <td><?= htmlspecialchars($row['msg_bot']) ?></td>
        <td><?= $row['data'] ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>

