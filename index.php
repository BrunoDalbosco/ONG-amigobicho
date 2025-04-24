<?php require_once  'includes/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Sobre - ONG Amigo Bicho</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>ONG Amigo Bicho</h1>
        <nav>
            <a href="index.php">Sobre</a>
            <a href="animais/listar.php">Animais para Adoção</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="animais/cadastrar.php">Cadastrar Animal</a>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    
    <main>
        <section class="about">
            <h2>Protegendo e Transformando Vidas</h2>
            
            <p>A ONG Amigo Bicho é uma entidade sem fins lucrativos fundada em 2003, 
            localizada em Içara/SC. Tem como missão resgatar, reabilitar e 
            encaminhar para adoção animais vítimas de abandono e maus-tratos.</p>
            
            <h3>Nossos Serviços</h3>
            <ul>
                <li>Resgate e cuidados veterinários</li>
                <li>Adoção responsável</li>
                <li>Castração comunitária</li>
                <li>Educação e conscientização sobre proteção animal</li>
                <li>Campanhas de arrecadação de recursos</li>
            </ul>
        </section>
    </main>
    
    <footer>
        <p>ONG Amigo Bicho © 2025 - Todos os direitos reservados</p>
    </footer>
</body>
</html>