<?php

declare(strict_types=1);

use Residencia\Controller\PortalAcceso\PortalViewController;
use Residencia\Model\PortalAcceso\PortalViewModel;

/** @var \PDO $pdo */
$pdo = require_once dirname(__DIR__, 3) . '/common/model/db.php';
require_once __DIR__ . '/../../controller/portalacceso/PortalViewController.php';
require_once __DIR__ . '/../../model/portalacceso/PortalViewModel.php';

$viewData = [
    'portalId' => null,
    'portal' => null,
    'empresa' => null,
    'errors' => [],
    'successMessage' => null,
    'actionError' => null,
];

$portalId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT,
    [
        'options' => ['default' => null],
        'flags' => FILTER_NULL_ON_FAILURE,
    ]
);

if ($portalId === null || $portalId <= 0) {
    $viewData['errors'][] = 'invalid_id';

    return $viewData;
}

$viewData['portalId'] = $portalId;
$viewData['successMessage'] = isset($_GET['success_message']) && is_string($_GET['success_message'])
    ? trim((string) $_GET['success_message'])
    : null;
$viewData['actionError'] = isset($_GET['error_message']) && is_string($_GET['error_message'])
    ? trim((string) $_GET['error_message'])
    : null;

try {
    $model = new PortalViewModel($pdo);
    $controller = new PortalViewController($model);
    $result = $controller->obtenerDetalle($portalId);

    $viewData['portal'] = $result['portal'] ?? null;
    $viewData['empresa'] = $result['empresa'] ?? null;

    if ($viewData['portal'] === null) {
        $viewData['errors'][] = 'not_found';
    }
} catch (\Throwable $exception) {
    $viewData['errors'][] = 'database_error';
    error_log('Error en portal_view_handler.php: ' . $exception->getMessage());
}

return $viewData;
