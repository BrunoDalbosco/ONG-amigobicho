<?php 
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit();
}

$animal_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM animais WHERE id = ?");
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();


if (!$animal) {
    header('Location: listar.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM adocoes WHERE id_animal = ? AND id_usuario = ?");
$stmt->bind_param("ii", $animal_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$existing = $result->fetch_assoc();


if ($existing) {
    $_SESSION['alert'] = "Você já solicitou a adoção deste animal";
    header('Location: listar.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO adocoes (id_animal, id_usuario) VALUES (?, ?)");
$stmt->bind_param("ii", $animal_id, $_SESSION['user_id']);
$stmt->execute();

    
    $_SESSION['success'] = "Solicitação de adoção enviada com sucesso!";
    header('Location: ../users/perfil.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Solicitar Adoção - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>ONG Amigo Bicho</h1>
        <nav>
            <a href="../index.php">Sobre</a>
            <a href="listar.php">Animais para Adoção</a>
            <a href="../users/perfil.php">Meu Perfil</a>
            <a href="../logout.php">Sair</a>
        </nav>
    </header>
    
    <main>
        <h2>Solicitar Adoção</h2>
        
        <div class="animal-info">
            <h3><?= $animal['nome'] ?></h3>
            <p><?= $animal['especie'] ?> - <?= $animal['raça'] ?></p>
            <p>Idade: <?= $animal['idade'] ?> anos</p>
        </div>
        
        <form method="POST" class="adocao-form">
            <p>Você está solicitando a adoção de <strong><?= $animal['nome'] ?></strong>.</p>
            <p>Nossa equipe entrará em contato para continuar o processo.</p>
            
            <button type="submit">Confirmar Solicitação</button>
            <a href="listar.php" class="btn-cancel">Cancelar</a>
        </form>
    </main>
    
    <footer>
        <p>ONG Amigo Bicho © 2025 - Todos os direitos reservados</p>
    </footer>
</body>
</html>