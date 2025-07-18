<?php
$chave_secreta = '6Lc3HHcrAAAAABQ708jlooufJKRzCobDiFfyyjeY';



session_start();
require_once 'db.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if (isset($_POST['login'])) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($res);

        if ($admin && password_verify($senha, $admin['senha_hash'])) {
            $_SESSION['admin'] = $admin['email'];
            header("Location: painel.php");
            exit;
        } else {
            $msg = "Credenciais inválidas.";
        }
    }

    if (isset($_POST['registro'])) {
        $recaptcha = $_POST['g-recaptcha-response'];
        $resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$chave_secreta&response=$recaptcha");
        $resposta = json_decode($resposta);
    
    if (!$resposta->success) {
        $msg = "Por favor, confirme que você não é um robô.";
    } else {
        // Prossegue com o registro...
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO admin (email, senha_hash) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $email, $senha_hash);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "Registrado com sucesso! Faça login.";
        } else {
            $msg = "Erro ao registrar.";
        }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta charset="UTF-8">
    <title>Login / Registro</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="login-container">
    <h2>Painel de Acesso</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Seu e-mail" required><br>
        <input type="password" name="senha" placeholder="Sua senha" required><br>
        <div class="g-recaptcha" data-sitekey="6Lc3HHcrAAAAAEcBt7VyNGiqXnBQt5i_If3w_WZD"></div>
        <button type="submit" name="login">Entrar</button>
        <button type="submit" name="registro">Registrar</button>
    </form>
    <p><?= $msg ?></p>
</div>
</body>
</html>