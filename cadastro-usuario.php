<?php
require_once  'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, telefone, username, password) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $telefone, $username, $password]);
        
        $_SESSION['success'] = "Cadastro realizado com sucesso! Faça login.";
        header('Location: login.php');
        exit();
    } catch (mysqli_error $e) {
        $error = "Erro ao cadastrar: " . $conn->connect_error();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastre-se</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Nome Completo:</label>
                <input type="text" name="nome" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Telefone:</label>
                <input type="text" name="telefone">
            </div>
            <div class="form-group">
                <label>Usuário:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
        
        <p>Já tem conta? <a href="login.php">Faça login</a></p>
    </div>
</body>
</html>