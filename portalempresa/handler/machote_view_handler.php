<?php
declare(strict_types=1);

use PortalEmpresa\Controller\Machote\MachoteViewController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../controller/MachoteViewController.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);

$empresa = [];
$machote = [];
$convenio = [];
$documento = [];
$comentarios = [];
$stats = [
    'total' => 0,
    'pendientes' => 0,
    'resueltos' => 0,
    'progreso' => 0,
    'estado' => 'En revisión',
];
$permisos = [
    'puede_comentar' => false,
    'puede_confirmar' => false,
];

$machoteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$viewError = null;

try {
    if ($machoteId === null || $machoteId === false) {
        throw new \RuntimeException('Identificador de machote invÃ¡lido.');
    }

    $controller = new MachoteViewController();
    $data = $controller->handleView($machoteId, $empresaId);

    $empresa = isset($data['empresa']) && is_array($data['empresa']) ? $data['empresa'] : [];
    $machote = isset($data['machote']) && is_array($data['machote']) ? $data['machote'] : [];
    $convenio = isset($data['convenio']) && is_array($data['convenio']) ? $data['convenio'] : [];
    $documento = isset($data['documento']) && is_array($data['documento']) ? $data['documento'] : [];
    $comentarios = isset($data['comentarios']) && is_array($data['comentarios']) ? $data['comentarios'] : [];
    $stats = isset($data['stats']) && is_array($data['stats']) ? $data['stats'] : $stats;
    $permisos = isset($data['permisos']) && is_array($data['permisos']) ? array_merge($permisos, $data['permisos']) : $permisos;
} catch (\Throwable $exception) {
    $viewError = $exception->getMessage();
}

$empresaNombre = $empresa['nombre'] ?? (string) ($portalSession['empresa_nombre'] ?? 'Empresa');
$machoteActualId = $machote['id'] ?? ($machoteId ?? 0);
