<?php
declare(strict_types=1);

use PortalEmpresa\Controller\PortalEmpresaDashboardController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../controller/PortalEmpresaDashboardController.php';

$portalSession = portalEmpresaRequireSession('login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);
$ultimoAccesoRaw = isset($portalSession['autenticado_en']) ? (string) $portalSession['autenticado_en'] : '';
$ultimoAccesoLabel = '';

if ($ultimoAccesoRaw !== '') {
    try {
        $ultimoAccesoLabel = (new DateTimeImmutable($ultimoAccesoRaw))->format('d/m/Y H:i');
    } catch (\Throwable) {
        $ultimoAccesoLabel = $ultimoAccesoRaw;
    }
}

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

$empresa = is_array($dashboard['empresa'] ?? null) ? $dashboard['empresa'] : null;
$empresaNombre = '';

if (is_array($empresa) && isset($empresa['nombre'])) {
    $empresaNombre = trim((string) $empresa['nombre']);
}

if ($empresaNombre === '') {
    $empresaNombre = trim((string) ($portalSession['empresa_nombre'] ?? ''));
}

if ($empresaNombre === '') {
    $empresaNombre = 'Empresa';
}

$convenio = is_array($dashboard['convenio'] ?? null) ? $dashboard['convenio'] : null;
$machoteResumen = is_array($dashboard['machote'] ?? null) ? $dashboard['machote'] : null;
$machoteStats = is_array($dashboard['stats'] ?? null)
    ? array_merge([
        'comentarios_total' => 0,
        'comentarios_pendientes' => 0,
        'comentarios_resueltos' => 0,
        'avance' => 0,
        'estado_revision' => 'Sin documento',
    ], $dashboard['stats'])
    : [
        'comentarios_total' => 0,
        'comentarios_pendientes' => 0,
        'comentarios_resueltos' => 0,
        'avance' => 0,
        'estado_revision' => 'Sin documento',
    ];
