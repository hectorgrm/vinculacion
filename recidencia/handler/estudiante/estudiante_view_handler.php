<?php

declare(strict_types=1);

use Residencia\Controller\Estudiante\EstudianteViewController;
use Residencia\Model\Estudiante\EstudianteViewModel;

/** @var \PDO $pdo */
$pdo = require_once dirname(__DIR__, 3) . '/common/model/db.php';
require_once dirname(__DIR__, 2) . '/controller/estudiante/EstudianteViewController.php';
require_once dirname(__DIR__, 2) . '/model/estudiante/EstudianteViewModel.php';

$viewData = [
    'estudianteId' => null,
    'estudiante' => null,
    'empresa' => null,
    'convenio' => null,
    'errors' => [],
];

$estudianteId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT,
    [
        'options' => ['default' => null],
        'flags' => FILTER_NULL_ON_FAILURE,
    ]
);

if ($estudianteId === null || $estudianteId <= 0) {
    $viewData['errors'][] = 'invalid_id';

    return $viewData;
}

$viewData['estudianteId'] = $estudianteId;

try {
    $model = new EstudianteViewModel($pdo);
    $controller = new EstudianteViewController($model);
    $resultado = $controller->obtenerDetalle($estudianteId);

    if (isset($resultado['estudiante'])) {
        $viewData['estudiante'] = $resultado['estudiante'];
    }

    if (isset($resultado['empresa'])) {
        $viewData['empresa'] = $resultado['empresa'];
    }

    if (isset($resultado['convenio'])) {
        $viewData['convenio'] = $resultado['convenio'];
    }

    if ($viewData['estudiante'] === null) {
        $viewData['errors'][] = 'not_found';
    }
} catch (\Throwable $exception) {
    $viewData['errors'][] = 'database_error';
    error_log('Error en estudiante_view_handler.php: ' . $exception->getMessage());
}

return $viewData;
