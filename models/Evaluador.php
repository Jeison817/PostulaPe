<?php
require_once "config/database.php";

class Evaluador {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function contarEvaluacionesPorPostulacion($idPostulacion) {
        try {
            $sql = "SELECT COUNT(*) as count FROM Evaluacion WHERE IdPostulacion = :idPostulacion AND IdEliminado = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":idPostulacion", $idPostulacion, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("contarEvaluacionesPorPostulacion: IdPostulacion=$idPostulacion, Count={$result['count']}");
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error al contar evaluaciones para IdPostulacion=$idPostulacion: " . $e->getMessage());
            return 0;
        }
    }

    public function rechazarPostulacion($data) {
        try {
            $sql = "CALL sp_rechazar_postulacion(:IdPostulacion, :IdUsuarioModificacion, @resultado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":IdPostulacion", $data['IdPostulacion'], PDO::PARAM_INT);
            $stmt->bindParam(":IdUsuarioModificacion", $data['IdUsuarioModificacion'], PDO::PARAM_INT);
            $stmt->execute();
            $result = $this->conn->query("SELECT @resultado AS resultado")->fetch(PDO::FETCH_ASSOC);
            error_log("rechazarPostulacion: IdPostulacion={$data['IdPostulacion']}, IdUsuarioModificacion={$data['IdUsuarioModificacion']}, Resultado={$result['resultado']}");
            return $result['resultado'] === 'Postulación rechazada exitosamente.';
        } catch (PDOException $e) {
            error_log("Error al rechazar postulación: " . $e->getMessage());
            return false;
        }
    }

    public function createConvocatoria($data) {
        $sql = "CALL sp_insertar_convocatoria(:Titulo, :Descripcion, :FechaInicio, :FechaFin, :IdArea, :IdUsuarioCreacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":Titulo", $data["titulo"]);
        $stmt->bindParam(":Descripcion", $data["descripcion"]);
        $stmt->bindParam(":FechaInicio", $data["fechaInicio"]);
        $stmt->bindParam(":FechaFin", $data["fechaFin"]);
        $stmt->bindParam(":IdArea", $data["idArea"]);
        $stmt->bindParam(":IdUsuarioCreacion", $data["idUsuarioCreacion"], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listarConvocatorias() {
        $sql = "CALL sp_listar_convocatorias()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConvocatoriaById($id) {
        $sql = "CALL sp_obtener_convocatoria_por_id(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateConvocatoria($id, $data) {
        $sql = "CALL sp_actualizar_convocatoria(:IdConvocatoria, :Titulo, :Descripcion, :FechaInicio, :FechaFin, :IdArea, :IdUsuarioModificacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":IdConvocatoria", $id, PDO::PARAM_INT);
        $stmt->bindParam(":Titulo", $data["titulo"]);
        $stmt->bindParam(":Descripcion", $data["descripcion"]);
        $stmt->bindParam(":FechaInicio", $data["fechaInicio"]);
        $stmt->bindParam(":FechaFin", $data["fechaFin"]);
        $stmt->bindParam(":IdArea", $data["idArea"]);
        $stmt->bindParam(":IdUsuarioModificacion", $data["idUsuarioModificacion"], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteConvocatoria($id) {
        $sql = "CALL sp_eliminar_convocatoria(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listarAreas() {
        $sql = "CALL sp_listarAreas1()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    
     public function listarConvocatoriasPorArea($idArea) {
        try {
            $sql = "CALL sp_listar_convocatorias_por_area(:idArea)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Depuración: Registrar el resultado
            error_log("listarConvocatoriasPorArea: idArea=$idArea, Convocatorias obtenidas, count=" . count($result));
            // Mostrar los datos devueltos para depuración
            error_log("listarConvocatoriasPorArea: Datos devueltos=" . json_encode($result));
            return $result;
        } catch (PDOException $e) {
            error_log("Error en listarConvocatoriasPorArea: idArea=$idArea, " . $e->getMessage());
            return [];
        }
    }

    public function listarPostulacionesPorDocumento($idConvocatoria, $numeroDocumento) {
    try {
        $sql = "CALL sp_listar_postulaciones_por_documento(:idConvocatoria, :numeroDocumento)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idConvocatoria", $idConvocatoria, PDO::PARAM_INT);
        $stmt->bindParam(":numeroDocumento", $numeroDocumento, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($stmt->nextRowset()) {} // limpiar resultados pendientes
        return $result;
    } catch (PDOException $e) {
        error_log("❌ Error en listarPostulacionesPorDocumento: " . $e->getMessage());
        return [];
    }
}

    public function createArea($data) {
        $sql = "CALL sp_insertar_area(:Nombre, :Descripcion, :IdUsuarioCreacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":Nombre", $data["nombre"]);
        $stmt->bindParam(":Descripcion", $data["descripcion"]);
        $stmt->bindParam(":IdUsuarioCreacion", $data["idUsuarioCreacion"], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listarTodasAreas() {
        $sql = "CALL sp_listar_areas()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAreaById($id) {
        $sql = "CALL sp_obtener_area_por_id(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateArea($id, $data) {
        $sql = "CALL sp_actualizar_area(:IdArea, :Nombre, :Descripcion, :IdUsuarioModificacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":IdArea", $id, PDO::PARAM_INT);
        $stmt->bindParam(":Nombre", $data["nombre"]);
        $stmt->bindParam(":Descripcion", $data["descripcion"]);
        $stmt->bindParam(":IdUsuarioModificacion", $data["idUsuarioModificacion"], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteArea($id) {
        $sql = "CALL sp_eliminar_area(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listarPostulacionesPorConvocatoria($idConvocatoria) {
        $sql = "CALL sp_listar_postulaciones_por_convocatoria(:idConvocatoria)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idConvocatoria', $idConvocatoria, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        while ($stmt->nextRowset()) {;}
        return $result;
    }

    public function listarConvocatoriasActivas() {
        $sql = "CALL sp_listar_convocatorias_activas()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPostulacion($idPostulacion) {
        $sql = "CALL sp_obtener_postulacion(:idPostulacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idPostulacion", $idPostulacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertarEvaluacion($data) {
        $sql = "CALL sp_insertar_evaluacion(:IdPostulacion, :IdEtapa, :Calificacion, :Comentario, :IdEvaluador, :IdUsuarioCreacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":IdPostulacion", $data["IdPostulacion"]);
        $stmt->bindParam(":IdEtapa", $data["IdEtapa"]);
        $stmt->bindParam(":Calificacion", $data["Calificacion"]);
        $stmt->bindParam(":Comentario", $data["Comentario"]);
        $stmt->bindParam(":IdEvaluador", $data["IdEvaluador"]);
        $stmt->bindParam(":IdUsuarioCreacion", $data["IdUsuarioCreacion"]);
        $ok = $stmt->execute();

    // 🔹 Actualizar estado automáticamente
    if ($ok) {
        $this->actualizarEstadoPostulacion($data["IdPostulacion"]);
    }

    return $ok;
}
    public function listarEtapas() {
        $sql = "SELECT IdEtapa, Nombre FROM Etapa";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEvaluacion($data) {
    $sql = "CALL sp_actualizar_evaluacion(:IdEvaluacion, :IdPostulacion, :IdEtapa, :Calificacion, :Comentario, :IdEvaluador)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":IdEvaluacion", $data["IdEvaluacion"], PDO::PARAM_INT);
    $stmt->bindParam(":IdPostulacion", $data["IdPostulacion"], PDO::PARAM_INT);
    $stmt->bindParam(":IdEtapa", $data["IdEtapa"], PDO::PARAM_INT);
    $stmt->bindParam(":Calificacion", $data["Calificacion"]);
    $stmt->bindParam(":Comentario", $data["Comentario"]);
    $stmt->bindParam(":IdEvaluador", $data["IdEvaluador"], PDO::PARAM_INT);
     $ok = $stmt->execute();

    // 🔹 Actualizar estado automáticamente
    if ($ok) {
        $this->actualizarEstadoPostulacion($data["IdPostulacion"]);
    }

    return $ok;
}

    public function getEvaluacionById($idEvaluacion) {
        $sql = "CALL sp_obtener_evaluacion_por_id(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $idEvaluacion, PDO::PARAM_INT);
        $stmt->execute();
        $evaluacion = $stmt->fetch(PDO::FETCH_ASSOC);
        while ($stmt->nextRowset()) {}
        return $evaluacion;
    }

    public function obtenerEvaluacionPorId($idEvaluacion) {
        $sql = "CALL sp_obtener_evaluacion_por_id(:IdEvaluacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":IdEvaluacion", $idEvaluacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarEvaluacionesPorPostulacion($idPostulacion) {
        $sql = "CALL sp_listar_evaluaciones_por_postulacion(:idPostulacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idPostulacion", $idPostulacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstadoPostulacion($idPostulacion) {
    $sql = "CALL sp_actualizar_estado_postulacion(:id)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $idPostulacion, PDO::PARAM_INT);
    return $stmt->execute();
}

public function contarEvaluaciones($idPostulacion) {
    $sql = "CALL sp_contar_evaluaciones(:idPostulacion)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":idPostulacion", $idPostulacion, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    while ($stmt->nextRowset()) {} // limpiar más resultados
    return $row['Total'] ?? 0;
}

 // SP para obtener el detalle de un postulante
   public function getDetallePostulante($idPostulante) {
    $sql = "CALL sp_detalle_postulante(:idPostulante)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idPostulante', $idPostulante, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>