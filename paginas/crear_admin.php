<?php
require_once __DIR__ . '/../config/conexion.php';

try {
    $db = Conexion::conectar();
    $hash = password_hash("1234", PASSWORD_DEFAULT);


    // Verifica si el usuario admin ya existe
    $stmt = $db->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute(["admin"]);

    if ($stmt->fetch()) {
        $sql = $db->prepare("UPDATE usuarios SET password = ? WHERE usuario = ?");
        $sql->execute([$hash, "admin"]);
        echo "Contraseña de administrador actualizada a '1234'.";
    } else {
        $sql = $db->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
        $sql->execute(["admin", $hash]);
        echo "Usuario administrador 'admin' creado con contraseña '1234'.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
