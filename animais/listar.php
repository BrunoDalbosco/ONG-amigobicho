<?php 
require_once '../includes/config.php';
require_once '../includes/auth.php';

$stmt = $conn->query("SELECT * FROM animais ORDER BY data_cadastro DESC");
$animais = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Animais para Adoção - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>ONG Amigo Bicho</h1>
        <nav>
            <a href="../index.php">Sobre</a>
            <a href="listar.php">Animais para Adoção</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cadastrar.php">Cadastrar Animal</a>
                <a href="../users/perfil.php">Meu Perfil</a>
                <a href="../users/logout.php">Sair</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    
    <main>
        <h2>Animais Disponíveis para Adoção</h2>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="cadastrar.php" class="btn-add">+ Adicionar Animal</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        
        <div class="animal-grid">
            <?php foreach ($animais as $animal): ?>
                <div class="animal-card">
                <img src="../assets/images/<?= !empty($animal['foto']) ? htmlspecialchars($animal['foto']) : 'default.jpg' ?>" 
                    alt="<?= htmlspecialchars($animal['nome']) ?>"
                    style="max-width: 200px; height: auto; display: block; margin: 0 auto;">
                    <h3><?= $animal['nome'] ?></h3>
                    <p><?= $animal['especie'] ?> - <?= $animal['raça'] ?></p>
                    <p>Idade: <?= $animal['idade'] ?> anos</p>
                    <p><?= $animal['descricao'] ?></p>
                    <span class="status <?= $animal['status'] ?>"><?= $animal['status'] ?></span>
                    
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['tipo'] == 'admin'): ?>
                        <div class="actions">
                            <a href="editar.php?id=<?= $animal['id'] ?>" class="btn-edit">Editar</a>
                            <a href="excluir.php?id=<?= $animal['id'] ?>" class="btn-delete">Excluir</a>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['tipo'] == 'comum' && $animal['status'] == 'disponivel'): ?>
                        <a href="solicitar-adocao.php?id=<?= $animal['id'] ?>" class="btn-adotar">Solicitar Adoção</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    
    <footer>
        <p>ONG Amigo Bicho © 2025 - Todos os direitos reservados</p>
    </footer>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>