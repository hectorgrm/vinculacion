<?php

declare(strict_types=1);

use PortalEmpresa\Model\Machote\MachoteViewModel;
use function Residencia\Common\Helpers\Machote\resumenComentarios;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../model/MachoteViewModel.php';
require_once __DIR__ . '/../../recidencia/common/helpers/machote/machote_revisar_helper.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);

$machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT);
$status = null;
$error = null;

if ($machoteId === null || $machoteId === false || $machoteId <= 0) {
    $error = 'invalid';
} elseif ($empresaId <= 0) {
    $error = 'session';
} else {
    $model = new MachoteViewModel();

    try {
        $machote = $model->getMachoteForEmpresa($machoteId, $empresaId);

        if ($machote === null) {
            throw new \RuntimeException('Machote no disponible.');
        }

        if ((int) ($machote['confirmacion_empresa'] ?? 0) === 1) {
            $status = 'already';
        } else {
            $comentarios = $model->getComentariosByMachote($machoteId);
            $resumen = resumenComentarios($comentarios);

            if ((int) ($resumen['pendientes'] ?? 0) > 0) {
                $error = 'pending';
            } else {
                $model->confirmarMachote($machoteId, $empresaId);
                $status = 'confirmed';
            }
        }
    } catch (\Throwable $exception) {
        $error = $error ?? 'internal';
    }
}

$query = http_build_query(array_filter([
    'confirm_status' => $status,
    'confirm_error' => $error,
]));

$redirect = '../view/machote_view.php?id=' . ($machoteId !== null && $machoteId !== false ? (int) $machoteId : 0);
$redirect .= $query !== '' ? '&' . $query : '';

header('Location: ' . $redirect);
exit;
