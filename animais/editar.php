<?php 
require_once '../includes/config.php';
require_once '../includes/admin-auth.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $raca = $_POST['raça'];
    $idade = $_POST['idade'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    
$upload_dir = '../assets/images/';
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 2 * 1024 * 1024; 

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $file_type = $_FILES['foto']['type'];
    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['error'] = "Tipo de arquivo não permitido. Use apenas JPEG, PNG ou GIF.";
        header('Location: cadastrar.php');
        exit();
    }
    
    if ($_FILES['foto']['size'] > $max_size) {
        $_SESSION['error'] = "Arquivo muito grande. Tamanho máximo: 2MB.";
        header('Location: cadastrar.php');
        exit();
    }
    
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = uniqid('img_') . '.' . $ext;
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $foto)) {
    } else {
        $_SESSION['error'] = "Erro ao mover o arquivo. Verifique as permissões.";
        header('Location: cadastrar.php');
        exit();
    }
} else {
    $foto = 'default.jpg'; 
}
    
    $stmt = $conn->prepare("UPDATE animais SET nome = ?, especie = ?, raça = ?, idade = ?, 
                           descricao = ?, foto = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssisssi", $nome, $especie, $raca, $idade, $descricao, $foto, $status, $animal_id);
    $stmt->execute();
    
    $_SESSION['success'] = "Animal atualizado com sucesso!";
    header('Location: listar.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Animal - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>ONG Amigo Bicho</h1>
        <nav>
            <a href="../index.php">Sobre</a>
            <a href="listar.php">Animais para Adoção</a>
            <a href="cadastrar.php">Cadastrar Animal</a>
            <a href="../users/logout.php">Sair</a>
        </nav>
    </header>
    
    <main>
        <h2>Editar Animal</h2>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="animal-form">
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($animal['nome']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Espécie:</label>
                <select name="especie" required>
                    <option value="Cachorro" <?= $animal['especie'] == 'Cachorro' ? 'selected' : '' ?>>Cachorro</option>
                    <option value="Gato" <?= $animal['especie'] == 'Gato' ? 'selected' : '' ?>>Gato</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Raça:</label>
                <input type="text" name="raça" value="<?= htmlspecialchars($animal['raça']) ?>">
            </div>
            
            <div class="form-group">
                <label>Idade (anos):</label>
                <input type="number" name="idade" min="0" max="30" value="<?= $animal['idade'] ?>">
            </div>
            
            <div class="form-group">
                <label>Descrição:</label>
                <textarea name="descricao" rows="4"><?= htmlspecialchars($animal['descricao']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status">
                    <option value="disponivel" <?= $animal['status'] == 'disponivel' ? 'selected' : '' ?>>Disponível</option>
                    <option value="adotado" <?= $animal['status'] == 'adotado' ? 'selected' : '' ?>>Adotado</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Foto atual:</label>
                <img src="../assets/images/<?= $animal['foto'] ?: 'default.jpg' ?>" alt="<?= $animal['nome'] ?>" style="max-width: 200px; display: block; margin-bottom: 10px;">
                <label>Alterar foto:</label>
                <input type="file" name="foto" accept="image/*">
            </div>
            
            <button type="submit">Salvar Alterações</button>
            <a href="listar.php" class="btn-cancel">Cancelar</a>
        </form>
    </main>
    
    <footer>
        <p>ONG Amigo Bicho © 2025 - Todos os direitos reservados</p>
    </footer>
</body>
</html>