<?php

declare(strict_types=1);

use Residencia\Controller\Machote\MachoteReabrirController;

require_once __DIR__ . '/../../common/auth.php';
require_once __DIR__ . '/../../controller/machote/MachoteReabrirController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithStatus(null, ['reabrir_error' => 'invalid']);
}

$machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT);

if ($machoteId === null || $machoteId === false || $machoteId <= 0) {
    redirectWithStatus(null, ['reabrir_error' => 'invalid']);
}

try {
    $controller = new MachoteReabrirController();
    $resultado = $controller->reabrir($machoteId);

    if ($resultado['status'] === 'reopened') {
        redirectWithStatus($machoteId, ['reabrir_status' => 'reopened']);
    }

    if ($resultado['status'] === 'already_open') {
        redirectWithStatus($machoteId, ['reabrir_status' => 'already_open']);
    }

    throw new \RuntimeException('Estado no reconocido.');
} catch (Throwable $exception) {
    error_log('Error al reabrir machote: ' . $exception->getMessage());
    redirectWithStatus($machoteId, ['reabrir_error' => 'internal']);
}

function redirectWithStatus(?int $machoteId, array $params): void
{
    if ($machoteId !== null && $machoteId > 0) {
        $params['id'] = $machoteId;
        $target = '../../view/machote/machote_revisar.php';
    } else {
        $target = '../../view/machote/machote_list.php';
    }

    $query = http_build_query($params);
    header('Location: ' . $target . ($query !== '' ? '?' . $query : ''));
    exit;
}
