<?php 
require_once '../includes/config.php';
require_once '../includes/admin-auth.php';

echo "<pre>";
print_r($_GET);
print_r($_SESSION);
echo "</pre>";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID do animal inválido";
    header('Location: listar.php');
    exit();
}

$animal_id = $_GET['id'];

$stmt = $conn->prepare("SELECT foto FROM animais WHERE id = ?");
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Animal não encontrado";
    header('Location: listar.php');
    exit();
}

$animal = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM adocoes WHERE id_animal = ?");
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['total'];

if ($count > 0) {
    $_SESSION['error'] = "Não é possível excluir: existem solicitações de adoção para este animal";
    header('Location: listar.php');
    exit();
}


if ($animal['foto'] && $animal['foto'] != 'default.jpg') {
    $foto_path = '../assets/images/' . $animal['foto'];
    if (file_exists($foto_path)) {
        if (!unlink($foto_path)) {
            $_SESSION['error'] = "Erro ao excluir a foto do animal";
            header('Location: listar.php');
            exit();
        }
    }
}

$stmt = $conn->prepare("DELETE FROM animais WHERE id = ?");
$stmt->bind_param("i", $animal_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Animal excluído com sucesso!";
} else {
    $_SESSION['error'] = "Erro ao excluir o animal: " . $conn->error;
}

header('Location: listar.php');
exit();
?>