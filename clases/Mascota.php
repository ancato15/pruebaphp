<?php
require_once __DIR__ . '/../config/conexion.php';

class Mascota
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?? Conexion::conectar();
    }

    /**
     * Registra una nueva mascota en el sistema.
     */
    public function registrar($nombre, $especie, $raza, $edad, $propietario)
    {
        $nombre = trim((string)$nombre);
        $especie = trim((string)$especie);
        $raza = trim((string)$raza) !== '' ? trim((string)$raza) : null;
        $propietario = trim((string)$propietario);

        if ($nombre === '' || $especie === '' || $propietario === '' || !is_numeric($edad) || $edad < 0) {
            throw new InvalidArgumentException("Datos de mascota inválidos o incompletos.");
        }

        $stmt = $this->db->prepare("INSERT INTO mascotas (nombre, especie, raza, edad, propietario) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $especie, $raza, (int)$edad, $propietario]);
    }

    /**
     * Lista todas las mascotas registradas en el sistema.
     */
    public function listar()
    {
        $stmt = $this->db->query("SELECT id, nombre, especie, raza, edad, propietario FROM mascotas ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una mascota por su ID.
     */
    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT id, nombre, especie, raza, edad, propietario FROM mascotas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Actualiza los datos de una mascota existente.
     */
    public function actualizar($id, $nombre, $especie, $raza, $edad, $propietario)
    {
        $nombre = trim((string)$nombre);
        $especie = trim((string)$especie);
        $raza = trim((string)$raza) !== '' ? trim((string)$raza) : null;
        $propietario = trim((string)$propietario);

        if ($nombre === '' || $especie === '' || $propietario === '' || !is_numeric($edad) || $edad < 0) {
            throw new InvalidArgumentException("Datos de mascota inválidos o incompletos.");
        }

        $stmt = $this->db->prepare("UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, edad = ?, propietario = ? WHERE id = ?");
        return $stmt->execute([$nombre, $especie, $raza, (int)$edad, $propietario, (int)$id]);
    }

    /**
     * Elimina una mascota por su ID.
     */
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM mascotas WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
}
