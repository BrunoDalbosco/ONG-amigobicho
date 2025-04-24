<?php 
require_once '../includes/config.php';
require_once '../includes/auth.php';


$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT a.*, ani.nome as animal_nome 
                        FROM adocoes a 
                        JOIN animais ani ON a.id_animal = ani.id 
                        WHERE a.id_usuario = ? 
                        ORDER BY a.data_solicitacao DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$adocoes = [];
while ($row = $result->fetch_assoc()) {
    $adocoes[] = $row;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Meu Perfil - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>ONG Amigo Bicho</h1>
        <nav>
            <a href="../index.php">Sobre</a>
            <a href="../animais/listar.php">Animais para Adoção</a>
            <a href="perfil.php">Meu Perfil</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    
    <main>
        <h2>Meu Perfil</h2>
        
        <div class="profile-info">
            <h3>Informações Pessoais</h3>
            <p><strong>Nome:</strong> <?= $usuario['nome'] ?></p>
            <p><strong>Email:</strong> <?= $usuario['email'] ?></p>
            <p><strong>Telefone:</strong> <?= $usuario['telefone'] ?: 'Não informado' ?></p>
        </div>
        
        <div class="adocoes">
            <h3>Minhas Solicitações de Adoção</h3>
            
            <?php if (empty($adocoes)): ?>
                <p>Você ainda não solicitou adoção de nenhum animal.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Animal</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adocoes as $adoção): ?>
                            <tr>
                                <td><?= $adoção['animal_nome'] ?></td>
                                <td><?= date('d/m/Y', strtotime($adoção['data_solicitacao'])) ?></td>
                                <td class="status-<?= $adoção['status'] ?>">
                                    <?= ucfirst($adoção['status']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
    
    <footer>
        <p>ONG Amigo Bicho © 2025 - Todos os direitos reservados</p>
    </footer>
</body>
</html>