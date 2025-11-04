<?php

declare(strict_types=1);

use PortalEmpresa\Controller\EmpresaDocumentoListController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../helpers/empresadocumentofunction.php';
require_once __DIR__ . '/../controller/EmpresaDocumentoListController.php';

if (!isset($_SESSION['portal_empresa']) || !is_array($_SESSION['portal_empresa'])) {
    header('Location: ../view/login.php');
    exit;
}

$sessionEmpresa = $_SESSION['portal_empresa'];
$empresaId = isset($sessionEmpresa['empresa_id']) ? (int) $sessionEmpresa['empresa_id'] : 0;

if ($empresaId <= 0) {
    header('Location: ../view/login.php');
    exit;
}

$controller = new EmpresaDocumentoListController();
$rawFilters = $_GET ?? [];
$errorMessage = null;

try {
    $payload = $controller->buildViewData($empresaId, $rawFilters);
} catch (\Throwable $exception) {
    $payload = [
        'empresa' => [
            'nombre' => (string) ($sessionEmpresa['empresa_nombre'] ?? ''),
        ],
        'documentos' => [],
        'kpis' => ['aprobado' => 0, 'pendiente' => 0, 'rechazado' => 0],
        'filters' => empresaDocumentoNormalizeFilters($rawFilters),
        'statusOptions' => empresaDocumentoStatusOptions(),
    ];
    $errorMessage = 'No se pudo cargar la información de los documentos. Intenta nuevamente más tarde.';
}

$empresaNombre = $payload['empresa']['nombre'] ?? (string) ($sessionEmpresa['empresa_nombre'] ?? '');
$documentos = $payload['documentos'];
$kpis = $payload['kpis'];
$filterValues = $payload['filters'];
$statusOptions = $payload['statusOptions'];

require __DIR__ . '/../view/documentos_list.php';
