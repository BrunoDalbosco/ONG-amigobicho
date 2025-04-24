<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['tipo'] = $user['tipo'];
        
        header('Location: animais/listar.php');
        exit();
    } else {
        $error = "Usuário ou senha inválidos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>ONG Amigo Bicho</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Usuário:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        
        <p>Não tem conta? <a href="cadastro-usuario.php">Cadastre-se</a></p>
    </div>
</body>
</html>