<?php
declare(strict_types=1);

use PortalEmpresa\Controller\PortalEmpresaDashboardController;
use PortalEmpresa\Helpers\PortalEmpresaDashboardViewHelper;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../controller/PortalEmpresaDashboardController.php';
require_once __DIR__ . '/../helpers/portal_empresa_dashboard_view_helper.php';

$portalSession = portalEmpresaRequireSession('login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);
$portalReadOnly = portalEmpresaIsReadOnly($portalSession);
$portalReadOnlyMessage = $portalReadOnly
    ? 'Empresa en estatus Completada: el portal esta en modo solo lectura.'
    : null;

$dashboard = [
    'empresa' => null,
    'convenio' => null,
    'machote' => null,
    'stats' => [
        'comentarios_total' => 0,
        'comentarios_pendientes' => 0,
        'comentarios_resueltos' => 0,
        'avance' => 0,
        'estado_revision' => 'Sin documento',
    ],
    'documentosStats' => ['total' => 0, 'subidos' => 0, 'aprobados' => 0, 'porcentaje' => 0],
];
$dashboardError = null;

if ($empresaId > 0) {
    try {
        $controller = new PortalEmpresaDashboardController();
        $dashboard = $controller->loadDashboard($empresaId);
    } catch (\Throwable $exception) {
        $dashboardError = $exception->getMessage();
    }
} else {
    $dashboardError = 'La sesión de la empresa no es válida.';
}

$portalDashboardViewModel = PortalEmpresaDashboardViewHelper::createViewModel(
    $portalSession,
    $dashboard,
    $dashboardError,
);
