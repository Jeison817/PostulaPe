<?php

require_once __DIR__ . '/../config/database.php';

class Convocatoria
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todas las convocatorias activas (no eliminadas)
    public function obtenerTodas()
    {
    try {
        $sql = "CALL sp_admin_obtener_convocatorias()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        error_log("Error obtenerTodas Convocatoria: " . $e->getMessage());
        return [];
        }
    }

    // Obtener convocatoria por Id
    public function obtenerPorId($id)
    {
    try {
        $sql = "CALL sp_admin_obtener_convocatoria_por_id(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        error_log("Error obtenerPorId Convocatoria: " . $e->getMessage());
        return null;
        }
    }

    // Crear una nueva convocatoria (puedes luego cambiar por SP)
    public function crear($data)
    {
    try {
        $sql = "CALL sp_admin_crear_convocatoria(:titulo, :descripcion, :fechaInicio, :fechaFin, :idArea, :idUsuarioCreacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':titulo', $data['Titulo']);
        $stmt->bindValue(':descripcion', $data['Descripcion']);
        $stmt->bindValue(':fechaInicio', $data['FechaInicio']);
        $stmt->bindValue(':fechaFin', $data['FechaFin']);
        $stmt->bindValue(':idArea', $data['IdArea'], PDO::PARAM_INT);
        $stmt->bindValue(':idUsuarioCreacion', $data['IdUsuarioCreacion'], PDO::PARAM_INT);
        return $stmt->execute();
        } catch (PDOException $e) {
        error_log("Error crear Convocatoria: " . $e->getMessage());
        return false;
        }
    }

    // Actualizar convocatoria existente
    public function actualizar($id, $data)
    {
    try {
        $sql = "CALL sp_admin_actualizar_convocatoria(:id, :titulo, :descripcion, :fechaInicio, :fechaFin, :idArea, :idUsuarioModificacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $data['Titulo']);
        $stmt->bindValue(':descripcion', $data['Descripcion']);
        $stmt->bindValue(':fechaInicio', $data['FechaInicio']);
        $stmt->bindValue(':fechaFin', $data['FechaFin']);
        $stmt->bindValue(':idArea', $data['IdArea'], PDO::PARAM_INT);
        $stmt->bindValue(':idUsuarioModificacion', $data['IdUsuarioModificacion'], PDO::PARAM_INT);
        return $stmt->execute();
        } catch (PDOException $e) {
        error_log("Error actualizar Convocatoria: " . $e->getMessage());
        return false;
        }
    }

    
    // Eliminar convocatoria lógicamente
    public function eliminar($id, $idUsuario)
{
    try {
        $stmt = $this->conn->prepare("CALL sp_eliminar_convocatoria_logica(:id, :idUsuario)");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error al ejecutar sp_eliminar_convocatoria_logica: " . $e->getMessage());
        return false;
    }
}
   

    // Metodo buscar Convocatoria por Area
public function buscarPorArea($idArea)
{
    try {
        $sql = "CALL sp_admin_buscar_convocatorias_por_area(:idArea)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idArea', $idArea, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error buscarPorArea Convocatoria: " . $e->getMessage());
        return [];
    }
}

// Metodo de contar convocatorias Activas 
public function contarConvocatoriasActivas()
{
    try {
        $sql = "CALL sp_admin_contar_convocatorias_no_eliminadas()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total_activas'] ?? 0);
    } catch (PDOException $e) {
        error_log("Error contarTodasActivas Convocatoria: " . $e->getMessage());
        return 0;
    }
}

// **********************
// ***** Agregados ******
// **********************

// Metodo Obtener resultados de postulantes seleccionados por convocatoria
// Metodo Obtener resultados de postulantes seleccionados por convocatoria 
public function obtenerResultadosPorConvocatoria($idConvocatoria)
{
    try {
        $sql = "CALL sp_obtener_resultados_por_convocatoria(:idConvocatoria)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idConvocatoria', $idConvocatoria, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error obtenerResultadosPorConvocatoria (SP): " . $e->getMessage());
        return [];
    }
}

// Metodo para Obtener todos los resultados de postulantes seleccionados
// Método para obtener todos los resultados de postulantes seleccionados usando SP
public function obtenerTodosResultadosGenerales() {
    try {
        $sql = "CALL sp_obtener_todos_resultados_generales()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Libera los resultados si fuera necesario (por compatibilidad)
        while ($stmt->nextRowset()) { }

        return $resultados;
    } catch (PDOException $e) {
        error_log("Error obtenerTodosResultadosGenerales (SP): " . $e->getMessage());
        return [];
    }
}



}
