<?php
require_once __DIR__ . '/../config/database.php';

class Postulante {
    private $conn;

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection(); // ✅ ahora tienes la conexión lista
        }
        public function guardarPerfil($data) {
            try {
                $fotoBinario = null;
                if (isset($data['Foto']) && $data['Foto']['error'] === 0) {
                    $fotoBinario = file_get_contents($data['Foto']['tmp_name']);
                }

                $sql = "CALL sp_guardarPerfilPostulante(
                            :IdUsuario,
                            :IdTipoDocumento,
                            :NumeroDocumento,
                            :IdGenero,
                            :IdEstadoCivil,
                            :Direccion,
                            :IdDepartamento,
                            :IdProvincia,
                            :IdDistrito,
                            :Telefono,
                            :Celular,
                            :Foto,
                            :IdUsuarioCreacion,
                            @resultado
                        )";

                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':IdUsuario', $data['IdUsuario']);
                $stmt->bindValue(':IdTipoDocumento', $data['IdTipoDocumento']);
                $stmt->bindValue(':NumeroDocumento', $data['NumeroDocumento']);
                $stmt->bindValue(':IdGenero', $data['IdGenero']);
                $stmt->bindValue(':IdEstadoCivil', $data['IdEstadoCivil']);
                $stmt->bindValue(':Direccion', $data['Direccion']);
                $stmt->bindValue(':IdDepartamento', $data['IdDepartamento']);
                $stmt->bindValue(':IdProvincia', $data['IdProvincia']);
                $stmt->bindValue(':IdDistrito', $data['IdDistrito']);
                $stmt->bindValue(':Telefono', $data['Telefono']);
                $stmt->bindValue(':Celular', $data['Celular']);
                $stmt->bindValue(':Foto', $fotoBinario, PDO::PARAM_LOB);
                $stmt->bindValue(':IdUsuarioCreacion', $data['IdUsuario']);

                $stmt->execute();

                $result = $this->conn->query("SELECT @resultado AS resultado")->fetch(PDO::FETCH_ASSOC);

                return ($result['resultado'] == 1) ? true : "Ya existe un perfil para este usuario.";
            } catch (PDOException $e) {
                return "Error en DB: " . $e->getMessage();
            }
        }

        public function esPrimerIngreso($idUsuario) {
            try {
                $stmt = $this->conn->prepare("CALL sp_esPrimerIngreso(:idUsuario, @resultado)");
                $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $stmt->execute();

                // Obtener el valor de salida del SP
                $result = $this->conn->query("SELECT @resultado AS resultado")->fetch(PDO::FETCH_ASSOC);

                return ($result['resultado'] == 1); // true si es primera vez
            } catch (PDOException $e) {
                error_log("Error esPrimerIngreso: " . $e->getMessage());
                return false;
            }   
        }
            public function obtenerIdPostulante($idUsuario) {
                $sql = "SELECT IdPostulante 
                        FROM Postulante 
                        WHERE IdUsuario = ? AND IdEliminado = 0 
                        LIMIT 1";
                $stmt = $this->conn->prepare($sql); // ✅ usa $this->conn
                $stmt->execute([$idUsuario]);
                return $stmt->fetchColumn();
            }



        // Actualizar datos postulante y usuario

                public function obtenerPerfil($idUsuario) {
                        $stmt = $this->conn->prepare("CALL sp_obtenerDatosPostulante(:idUsuario)");
                        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $stmt->execute();
                        return $stmt->fetch(PDO::FETCH_ASSOC);
                    }

                    // Actualizar datos postulante y usuario

                    public function actualizarPerfil($data) {
                try {
                    $fotoBinario = null;
                    if (isset($data['Foto']) && $data['Foto']['error'] === 0) {
                        $fotoBinario = file_get_contents($data['Foto']['tmp_name']);
                    }

                    $sql = "CALL sp_actualizarDatosPostulanteyUsuario(
                                :IdUsuario,
                                :Nombre,
                                :ApellidoPaterno,
                                :ApellidoMaterno,
                                :Contrasena,
                                :IdTipoDocumento,
                                :NumeroDocumento,
                                :IdGenero,
                                :IdEstadoCivil,
                                :Direccion,
                                :IdDepartamento,
                                :IdProvincia,
                                :IdDistrito,
                                :Telefono,
                                :Celular,
                                :Foto,
                                :IdUsuarioModificacion,
                                @resultado
                            )";

                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(':IdUsuario', $data['IdUsuario']);
                    $stmt->bindValue(':Nombre', $data['Nombre']);
                    $stmt->bindValue(':ApellidoPaterno', $data['ApellidoPaterno']);
                    $stmt->bindValue(':ApellidoMaterno', $data['ApellidoMaterno']);
                    $stmt->bindValue(':Contrasena', $data['Contrasena']); 
                    $stmt->bindValue(':IdTipoDocumento', $data['IdTipoDocumento']);
                    $stmt->bindValue(':NumeroDocumento', $data['NumeroDocumento']);
                    $stmt->bindValue(':IdGenero', $data['IdGenero']);
                    $stmt->bindValue(':IdEstadoCivil', $data['IdEstadoCivil']);
                    $stmt->bindValue(':Direccion', $data['Direccion']);
                    $stmt->bindValue(':IdDepartamento', $data['IdDepartamento']);
                    $stmt->bindValue(':IdProvincia', $data['IdProvincia']);
                    $stmt->bindValue(':IdDistrito', $data['IdDistrito']);
                    $stmt->bindValue(':Telefono', $data['Telefono']);
                    $stmt->bindValue(':Celular', $data['Celular']);

                    // ✅ Aquí el fix
                    if ($fotoBinario !== null) {
                        $stmt->bindParam(':Foto', $fotoBinario, PDO::PARAM_LOB);
                    } else {
                        $stmt->bindValue(':Foto', null, PDO::PARAM_NULL);
                    }

                    $stmt->bindValue(':IdUsuarioModificacion', $data['IdUsuario']);

                    $stmt->execute();

                    $result = $this->conn->query("SELECT @resultado AS resultado")->fetch(PDO::FETCH_ASSOC);

                    return ($result['resultado'] == 1);

                } catch (PDOException $e) {
                    error_log("Error actualizarPerfil: " . $e->getMessage());
                    return false;
                }
            }

        public function listarConvocatoriasVigentes($idPostulante, $idArea = 0) {
            try {
                $stmt = $this->conn->prepare("CALL sp_listarConvocatoriasVigentes(:IdPostulante, :IdArea)");
                $stmt->bindValue(':IdPostulante', $idPostulante, PDO::PARAM_INT);
                $stmt->bindValue(':IdArea', $idArea, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor(); // 👈 necesario con procedimientos almacenados

                return $result;
            } catch (PDOException $e) {
                error_log("Error listarConvocatoriasVigentes: " . $e->getMessage());
                return [];
            }
        }



        public function guardarPostulacion($idPostulante, $idConvocatoria, $cvPath) {
            try {
                $stmt = $this->conn->prepare("CALL sp_guardarPostulacion(:IdPostulante, :IdConvocatoria, :CVPath, :IdUsuarioCreacion, @resultado)");
                $stmt->bindValue(':IdPostulante', $idPostulante, PDO::PARAM_INT);
                $stmt->bindValue(':IdConvocatoria', $idConvocatoria, PDO::PARAM_INT);
                $stmt->bindValue(':CVPath', $cvPath, PDO::PARAM_STR);
                $stmt->bindValue(':IdUsuarioCreacion', $_SESSION['user']['IdUsuario'], PDO::PARAM_INT);

                $stmt->execute();
                $result = $this->conn->query("SELECT @resultado AS resultado")->fetch(PDO::FETCH_ASSOC);

                return $result['resultado'];
            } catch (PDOException $e) {
                error_log("Error guardarPostulacion: " . $e->getMessage());
                return 0;
            }
        }

        public function listarPostulaciones($idPostulante) {
            try {
                $stmt = $this->conn->prepare("CALL sp_listarPostulaciones(:IdPostulante)");
                $stmt->bindValue(':IdPostulante', $idPostulante, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                return $result;
            } catch (PDOException $e) {
                error_log("Error listarPostulaciones: " . $e->getMessage());
                return [];
            }
        }


        public function obtenerResumen($idPostulante) {
            try {
                $stmt = $this->conn->prepare("CALL sp_resumenPostulante(:idPostulante)");
                $stmt->bindValue(':idPostulante', $idPostulante, PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor(); // importante para liberar resultados del SP

                return $resultado;
            } catch (PDOException $e) {
                error_log("Error obtenerResumen: " . $e->getMessage());
                return [
                    'convocatoriasCount' => 0,
                    'postulacionesCount' => 0,
                    'ultimaPostulacion'  => 'Error al cargar'
                ];
            }
        }

        public function ConvocatoriaDetalle($idConvocatoria) {
            try {
                $stmt = $this->conn->prepare("CALL sp_ConvocatoriaDetalle(:IdConvocatoria)");
                $stmt->bindValue(':IdConvocatoria', $idConvocatoria, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                return $result;
            } catch (PDOException $e) {
                error_log("Error ConvocatoriaDetalle: " . $e->getMessage());
                return null;
            }
        }

}
