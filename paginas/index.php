<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../clases/Mascota.php';

$mascotaModel = new Mascota();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['registrar'])) {
            $mascotaModel->registrar(
                $_POST['nombre'] ?? '',
                $_POST['especie'] ?? '',
                $_POST['raza'] ?? null,
                $_POST['edad'] ?? 0,
                $_POST['propietario'] ?? ''
            );
            $mensaje = "Mascota registrada correctamente.";
        } elseif (isset($_POST['eliminar']) && !empty($_POST['id'])) {
            $mascotaModel->eliminar($_POST['id']);
            $mensaje = "Mascota eliminada correctamente.";
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

$datos = $mascotaModel->listar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Veterinaria</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
    <div class="contenedor">
        <h2>Sistema de Veterinaria</h2>
        <p>Bienvenido: <?php echo htmlspecialchars($_SESSION["usuario"]); ?></p>
        <a href="cerrar_sesion.php">Cerrar sesión</a>

        <?php if ($mensaje !== ''): ?>
            <p><strong><?php echo htmlspecialchars($mensaje); ?></strong></p>
        <?php endif; ?>

        <h3>Registrar mascota</h3>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required><br><br>
            <input type="text" name="especie" placeholder="Especie" required><br><br>
            <input type="text" name="raza" placeholder="Raza (opcional)"><br><br>
            <input type="number" name="edad" placeholder="Edad" min="0" required><br><br>
            <input type="text" name="propietario" placeholder="Propietario" required><br><br>

            <button type="submit" name="registrar">Guardar</button>
        </form>

        <h3>Lista de mascotas</h3>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Edad</th>
                <th>Propietario</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($datos as $fila): ?>
                <tr>
                    <td><?php echo $fila["id"]; ?></td>
                    <td><?php echo htmlspecialchars($fila["nombre"]); ?></td>
                    <td><?php echo htmlspecialchars($fila["especie"]); ?></td>
                    <td><?php echo htmlspecialchars($fila["raza"] ?? 'N/A'); ?></td>
                    <td><?php echo $fila["edad"]; ?></td>
                    <td><?php echo htmlspecialchars($fila["propietario"]); ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('¿Eliminar mascota?')">
                            <input type="hidden" name="id" value="<?php echo $fila["id"]; ?>">
                            <button type="submit" name="eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>