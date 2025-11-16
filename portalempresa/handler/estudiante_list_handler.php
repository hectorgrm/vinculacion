<?php

declare(strict_types=1);

use PortalEmpresa\Controller\PortalEstudianteListController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../controller/PortalEstudianteListController.php';
require_once __DIR__ . '/../helpers/estudiante_helper.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);
$empresaNombre = (string) ($portalSession['empresa_nombre'] ?? 'Empresa');

$controller = new PortalEstudianteListController();

$activos = [];
$historico = [];
$kpiActivos = 0;
$kpiFinalizados = 0;
$kpiTotal = 0;
$listErrorMessage = null;

try {
    $listado = $controller->obtenerListadoPorEmpresa($empresaId);

    $activos = $listado['activos'];
    $historico = $listado['historico'];
    $kpiActivos = (int) ($listado['kpi']['activos'] ?? count($activos));
    $kpiFinalizados = (int) ($listado['kpi']['finalizados'] ?? 0);
    $kpiTotal = (int) ($listado['kpi']['total'] ?? ($kpiActivos + count($historico)));
} catch (\Throwable $exception) {
    $listErrorMessage = 'No se pudo cargar el listado de estudiantes. Intenta nuevamente más tarde.';
}

require __DIR__ . '/../view/estudiantes_list.php';
