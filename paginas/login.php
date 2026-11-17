<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../clases/Usuario.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $usuario = new Usuario();
        if ($usuario->login($user, $password)) {
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario'] = $usuario->getUsuario();
            header('Location: index.php');
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } catch (Exception $e) {
        $error = "Ocurrió un error en el sistema: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinaria - Iniciar sesión</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
    <div class="contenedor">
        <h2>Veterinaria - Iniciar sesión</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required><br><br>
            <input type="password" name="password" placeholder="Contraseña" required><br><br>
            <button type="submit" name="ingresar">Ingresar</button>
        </form>
    </div>
</body>

</html>