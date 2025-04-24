<?php 
require_once '../includes/config.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $raca = $_POST['raça'];
    $idade = $_POST['idade'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    $upload_dir = dirname(__DIR__) . '/assets/images/';
    
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; 
    $foto = 'default.jpg'; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $file_type = $finfo->file($_FILES['foto']['tmp_name']);
        
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
        
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto = 'animal_' . uniqid() . '.' . $ext;
        $destination = $upload_dir . $foto;
        
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
            $error = error_get_last();
            $_SESSION['error'] = "Erro ao mover o arquivo: " . $error['message'];
            header('Location: cadastrar.php');
            exit();
        }
    }

    $stmt = $conn->prepare("INSERT INTO animais (nome, especie, raça, idade, descricao, foto, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $nome, $especie, $raca, $idade, $descricao, $foto, $status);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Animal cadastrado com sucesso!";
        header('Location: listar.php');
        exit();
    } else {
        if ($foto != 'default.jpg' && file_exists($upload_dir . $foto)) {
            unlink($upload_dir . $foto);
        }
        $_SESSION['error'] = "Erro ao cadastrar animal: " . $conn->error;
        header('Location: cadastrar.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Animal - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>ONG Amigo Bicho</h1>
        <nav>
            <a href="../index.php">Sobre</a>
            <a href="listar.php">Animais para Adoção</a>
            <a href="cadastrar.php">Cadastrar Animal</a>
            <a href="../users/perfil.php">Meu Perfil</a>
            <a href="../users/logout.php">Sair</a>
        </nav>
    </header>
    
    <main>
        <h2>Cadastrar Novo Animal</h2>
        
        <form method="POST" enctype="multipart/form-data" class="animal-form">
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" required>
            </div>
            
            <div class="form-group">
                <label>Espécie:</label>
                <select name="especie" required>
                    <option value="Cachorro">Cachorro</option>
                    <option value="Gato">Gato</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Raça:</label>
                <input type="text" name="raça">
            </div>
            
            <div class="form-group">
                <label>Idade (anos):</label>
                <input type="number" name="idade" min="0" max="30">
            </div>
            
            <div class="form-group">
                <label>Descrição:</label>
                <textarea name="descricao" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status">
                    <option value="disponivel">Disponível</option>
                    <option value="adotado">Adotado</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Foto:</label>
                <input type="file" name="foto" accept="image/*">
            </div>
            
            <button type="submit">Salvar</button>
            <a href="listar.php" class="btn-cancel">Cancelar</a>
        </form>
    </main>
    
    <footer>
        <p>ONG Amigo Bicho © 2025 - Todos os direitos reservados</p>
    </footer>
</body>
</html>