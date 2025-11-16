<?php

declare(strict_types=1);

use PortalEmpresa\Controller\PortalEstudianteViewController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../controller/PortalEstudianteViewController.php';
require_once __DIR__ . '/../helpers/estudiante_helper.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);
$empresaNombre = (string) ($portalSession['empresa_nombre'] ?? 'Empresa');

$controller = new PortalEstudianteViewController();
$estudiante = estudianteEmptyRecord();
$viewErrorMessage = null;
$estudianteId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

try {
    $estudiante = $controller->obtenerDetalle($empresaId, $estudianteId);
} catch (\Throwable $exception) {
    $viewErrorMessage = 'No se pudo cargar la información del estudiante solicitado.';
}

$nombreCompleto = estudianteNombreCompleto($estudiante);
$estatusBadgeClass = estudianteBadgeClass($estudiante['estatus'] ?? null);
$estatusBadgeLabel = estudianteBadgeLabel($estudiante['estatus'] ?? null);

require __DIR__ . '/../view/estudiante_view.php';
