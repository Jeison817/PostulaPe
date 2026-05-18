<?php
require_once __DIR__ . '/../config/database.php';

class Catalogo {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getDepartamentos() {
        $stmt = $this->conn->query("CALL sp_getDepartamentos()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProvincias($idDepartamento) {
        $stmt = $this->conn->prepare("CALL sp_getProvincias(:idDepartamento)");
        $stmt->bindParam(':idDepartamento', $idDepartamento);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistritos($idProvincia) {
        $stmt = $this->conn->prepare("CALL sp_getDistritos(:idProvincia)");
        $stmt->bindParam(':idProvincia', $idProvincia);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTiposDocumento() {
        $stmt = $this->conn->query("CALL sp_getTipoDocumentos()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGeneros() {
        $stmt = $this->conn->query("CALL sp_getGeneros()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadosCiviles() {
        $stmt = $this->conn->query("CALL sp_getEstadosCiviles()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAreas() {
    $stmt = $this->conn->query("CALL sp_getAreas()");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
