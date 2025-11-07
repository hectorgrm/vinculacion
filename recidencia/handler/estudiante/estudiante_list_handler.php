<?php
declare(strict_types=1);

// ==============================
// Handler: rp_estudiante_list_handler.php
// ==============================

require_once __DIR__ . '/../../../common/db_connect.php';

$viewErrors = [];
$estudiantes = [];
$empresas = [];
$convenios = [];

// Leer filtros (si existen)
$empresaSeleccionada = isset($_GET['empresa_id']) ? (int)$_GET['empresa_id'] : null;
$convenioSeleccionado = isset($_GET['convenio_id']) ? (int)$_GET['convenio_id'] : null;

try {
    // ==========================
    // 1️⃣ Obtener lista de empresas
    // ==========================
    $stmtEmp = $pdo->query("SELECT id, nombre FROM rp_empresa ORDER BY nombre ASC");
    $empresas = $stmtEmp->fetchAll(PDO::FETCH_ASSOC);

    // ==========================
    // 2️⃣ Obtener lista de convenios
    // ==========================
    $stmtConv = $pdo->query("SELECT id, folio, empresa_id FROM rp_convenio ORDER BY id DESC");
    $convenios = $stmtConv->fetchAll(PDO::FETCH_ASSOC);

    // ==========================
    // 3️⃣ Armar la consulta principal
    // ==========================
    $query = "
        SELECT 
            e.id,
            CONCAT(e.nombre, ' ', COALESCE(e.apellido_paterno, ''), ' ', COALESCE(e.apellido_materno, '')) AS nombre_completo,
            e.matricula,
            e.carrera,
            e.estatus,
            emp.nombre AS empresa_nombre,
            c.folio AS convenio_folio
        FROM rp_estudiante e
        INNER JOIN rp_empresa emp ON emp.id = e.empresa_id
        LEFT JOIN rp_convenio c ON c.id = e.convenio_id
        WHERE 1=1
    ";

    $params = [];

    // ==========================
    // 4️⃣ Filtros dinámicos
    // ==========================
    if (!empty($empresaSeleccionada)) {
        $query .= " AND e.empresa_id = :empresa_id";
        $params[':empresa_id'] = $empresaSeleccionada;
    }

    if (!empty($convenioSeleccionado)) {
        $query .= " AND e.convenio_id = :convenio_id";
        $params[':convenio_id'] = $convenioSeleccionado;
    }

    $query .= " ORDER BY e.creado_en DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    $viewErrors[] = 'database_error';
    error_log('Error en rp_estudiante_list_handler.php: ' . $e->getMessage());
}
