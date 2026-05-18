<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function login($usuario, $password) {
        $passwordHash = hashPassword($password);

        $stmt = $this->conn->prepare("CALL sp_loginUsuario(:usuario, :pass)");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam(":pass", $passwordHash, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $user;
    }

        public function registrarPostulante($usuario, $contrasena, $nombre, $apellidoPaterno, $apellidoMaterno) {
            try {
                $sql = "CALL sp_registrarUsuarioPostulante(:usuario, :contrasena, :nombre, :apellidoPaterno, :apellidoMaterno, @resultado)";
                $stmt = $this->conn->prepare($sql);

                $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR); // el SP lo hashea
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':apellidoPaterno', $apellidoPaterno, PDO::PARAM_STR);
                $stmt->bindParam(':apellidoMaterno', $apellidoMaterno, PDO::PARAM_STR);

                $stmt->execute();

                // Leer resultado
                $result = $this->conn->query("SELECT @resultado AS resultado")->fetch(PDO::FETCH_ASSOC);

                if ($result['resultado'] == 1) {
                    return true;           // Usuario creado
                } elseif ($result['resultado'] == 2) {
                    return "existe";       // Usuario ya existe
                } else {
                    return false;          // Otro error
                }
            } catch (PDOException $e) {
                error_log("Error registrarPostulante: " . $e->getMessage());
                return false;
            }
        }



    // ********************************/
    // listar de solo usuarios activos    
    public function obtenerTodosExcluyendoActual($idUsuarioActual, $incluirInactivos = false) {
        $sql = "CALL sp_admin_obtener_usuarios_excluyendo_actual(:incluir, :idUsuarioActual)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':incluir', $incluirInactivos, PDO::PARAM_BOOL);
        $stmt->bindValue(':idUsuarioActual', $idUsuarioActual, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // Importante para poder llamar otro procedimiento después
        return $result;
    }

    // Obtener usuario por Id
    public function obtenerPorId($id) {
        $sql = "CALL sp_admin_obtener_usuario_por_id(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo usuario
    public function crear($data) {
        $sql = "CALL sp_admin_crear_usuario(:Usuario, :ContrasenaHash, :Nombre, :ApellidoPaterno, :ApellidoMaterno, :IdPerfil, :IdUsuarioCreacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':Usuario', $data['Usuario']);
        $stmt->bindValue(':ContrasenaHash', $data['ContrasenaHash']);
        $stmt->bindValue(':Nombre', $data['Nombre']);
        $stmt->bindValue(':ApellidoPaterno', $data['ApellidoPaterno']);
        $stmt->bindValue(':ApellidoMaterno', $data['ApellidoMaterno']);
        $stmt->bindValue(':IdPerfil', $data['IdPerfil'], PDO::PARAM_INT);
        $stmt->bindValue(':IdUsuarioCreacion', $data['IdUsuarioCreacion'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Actualizar usuario
        public function actualizar($id, $data) {
            $sql = "CALL sp_admin_actualizar_usuario(:IdUsuario, :Usuario, :Nombre, :ApellidoPaterno, :ApellidoMaterno, :IdPerfil, :IdUsuarioModificacion)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':IdUsuario', $id, PDO::PARAM_INT);
            $stmt->bindValue(':Usuario', $data['Usuario']);
            $stmt->bindValue(':Nombre', $data['Nombre']);
            $stmt->bindValue(':ApellidoPaterno', $data['ApellidoPaterno']);
            $stmt->bindValue(':ApellidoMaterno', $data['ApellidoMaterno']);
            $stmt->bindValue(':IdPerfil', $data['IdPerfil'], PDO::PARAM_INT);
            $stmt->bindValue(':IdUsuarioModificacion', $data['IdUsuarioModificacion'], PDO::PARAM_INT);
            return $stmt->execute();
        }         

    // Eliminar usuario (soft delete)
        public function eliminar($id, $idUsuarioModificacion) {
            $sql = "CALL sp_admin_eliminar_usuario(:IdUsuario, :IdUsuarioModificacion)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':IdUsuario', $id, PDO::PARAM_INT);
            $stmt->bindValue(':IdUsuarioModificacion', $idUsuarioModificacion, PDO::PARAM_INT);
            return $stmt->execute();
        }

        // Reactivar usuario
        public function reactivar($id, $idUsuarioModificacion) {
            $sql = "CALL sp_admin_reactivar_usuario(:IdUsuario, :IdUsuarioModificacion)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':IdUsuario', $id, PDO::PARAM_INT);
            $stmt->bindValue(':IdUsuarioModificacion', $idUsuarioModificacion, PDO::PARAM_INT);
            return $stmt->execute();
        }

    // Buscar por nombre (o parte del nombre)
        public function buscarPorNombre($nombre) {
        $sql = "CALL sp_admin_buscar_usuarios_por_nombre(:nombre)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();  // Muy importante para poder ejecutar otra consulta después
        return $result;
        }



        // Listar usuarios eliminados
        public function obtenerHistorialEliminados() {
        $sql = "CALL sp_admin_obtener_usuarios_eliminados()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
        }

        // Contar usuarios activos
        public function contarUsuariosActivos() {
        $sql = "CALL sp_admin_contar_usuarios_activos()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result['totalActivos'] ?? 0;
        }

    // Contar usuarios inactivos
        public function contarUsuariosInactivos() {
        $sql = "CALL sp_admin_contar_usuarios_inactivos()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result['totalInactivos'] ?? 0;
        }

}
