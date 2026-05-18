<?php
// config/database.php
class Database {
    private $host = "localhost";
    private $dbname = "PostulaPeBD";
    private $user = "root";
    private $pass = "123456";
    private $charset = "utf8";
    private static $instance = null; // Singleton

    public function getConnection() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}",
                    $this->user,
                    $this->pass
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}

// 👉 Función global para encriptar contraseñas
function hashPassword($password) {
    return hash('sha512', $password);
}
