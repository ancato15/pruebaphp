<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario
{
    private $db;
    private $id;
    private $usuario;
    private $password;

    public function __construct($db = null)
    {
        $this->db = $db ?? Conexion::conectar();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = trim($usuario);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Registra un nuevo usuario en el sistema.
     */
    public function registrar($usuario, $password)
    {
        $usuario = trim($usuario);

        if (empty($usuario) || empty($password)) {
            throw new InvalidArgumentException("Todos los campos obligatorios deben estar llenos.");
        }

        if (strlen($password) < 6) {
            throw new InvalidArgumentException("La contraseña debe tener al menos 6 caracteres.");
        }

        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        if ($stmt->fetch()) {
            throw new Exception("El nombre de usuario ya se encuentra registrado.");
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
        return $stmt->execute([$usuario, $passwordHash]);
    }

    /**
     * Autentica un usuario en el sistema.
     */
    public function login($usuario, $password)
    {
        $usuario = trim((string)$usuario);
        $password = (string)$password;

        if ($usuario === '' || $password === '') {
            throw new InvalidArgumentException("Credenciales incompletas.");
        }

        $stmt = $this->db->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password'])) {
            $this->id = $row['id'];
            $this->usuario = $row['usuario'];
            return true;
        }

        return false;
    }

    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT id, usuario FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}