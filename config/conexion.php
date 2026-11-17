<?php
class Conexion
{
    private static $instance = null;

    public static function conectar()

    {
        if (self::$instance === null) {
            $databaseUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? '');

            if ($databaseUrl !== '') {
                $dbparts = parse_url($databaseUrl);
                $host = $dbparts['host'];
                $port = $dbparts['port'] ?? 3306;
                $db   = ltrim($dbparts['path'], '/');
                $user = $dbparts['user'];
                $pass = $dbparts['pass'];
            } else {
                $host = 'cmwjjrovn028901pbnxcn0hu8-addon-mysql';
                $port = 3306;
                $db   = 'ethiodeploy';
                $user = 'ethiodeploy';
                $pass = '3279f9abe185222643d4b6af5eedc75b561c4ffce1a7caf9';
            }

            try {
                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new PDOException("Error de conexión BD: " . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
