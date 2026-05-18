<?php
// models/Area.php

class Area {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todas las áreas (activas por defecto)
    public function obtenerTodas($incluirInactivas = false) {
    $sql = "CALL sp_admin_obtener_areas(:incluir)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':incluir', $incluirInactivas, PDO::PARAM_BOOL);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 

    // Obtener área por Id
    public function obtenerPorId($id) {
        $sql = "CALL sp_admin_obtener_area_por_id(:id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nueva área
    public function crear($data) {
    $sql = "CALL sp_admin_crear_area(:Nombre, :Descripcion, :IdUsuarioCreacion)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':Nombre', $data['Nombre']);
    $stmt->bindParam(':Descripcion', $data['Descripcion']);
    $stmt->bindParam(':IdUsuarioCreacion', $data['IdUsuarioCreacion']);
    return $stmt->execute();
    }

    // Actualizar área
    public function actualizar($id, $data) {
        $sql = "CALL sp_admin_actualizar_area(:IdArea, :Nombre, :Descripcion, :IdUsuarioModificacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':IdArea', $id);
        $stmt->bindParam(':Nombre', $data['Nombre']);
        $stmt->bindParam(':Descripcion', $data['Descripcion']);
        $stmt->bindParam(':IdUsuarioModificacion', $data['IdUsuarioModificacion']);
        return $stmt->execute();
    }


    // Eliminar área (soft delete)
    public function eliminar($id, $idUsuarioModificacion) {
        $sql = "UPDATE Area SET IdEliminado = 1, IdUsuarioModificacion = ?, FechaModificacion = NOW() WHERE IdArea = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idUsuarioModificacion, $id]);
    }

    // Reactivar área (soft undelete)
    public function reactivar($id, $idUsuarioModificacion) {
        $sql = "UPDATE Area SET IdEliminado = 0, IdUsuarioModificacion = ?, FechaModificacion = NOW() WHERE IdArea = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idUsuarioModificacion, $id]);
    }
    // ------------------------------------------------------------------
    //-------------------------------------------------------------------

    // Buscar áreas por nombre
    public function buscarPorNombre($nombre) {
    $sql = "CALL sp_admin_buscar_area_por_nombre(:nombre)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // historial de Areas
    public function obtenerHistorialEliminados() {
    $sql = "CALL sp_admin_obtener_historial_eliminados()";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Contar Convocatorias Inactivas
    public function contarAreasInactivas() {
        $sql = "CALL sp_admin_contar_areas_inactivas()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['cantidad'] ?? 0;
    }

    // Contar Convocatorias Activas
    public function contarAreasActivas() {
        $sql = "CALL sp_admin_contar_areas_activas()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['cantidad'] ?? 0;
    }



}
