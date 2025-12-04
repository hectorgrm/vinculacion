<?php

declare(strict_types=1);

use PortalEmpresa\Model\Machote\MachoteViewModel;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../model/MachoteViewModel.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);
$portalReadOnly = portalEmpresaIsReadOnly($portalSession);

$machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT);
$clausula = trim((string) ($_POST['asunto'] ?? ''));
$comentario = trim((string) ($_POST['comentario'] ?? ''));

$status = null;
$error = null;

if ($machoteId === null || $machoteId === false || $machoteId <= 0) {
    $error = 'invalid';
} elseif ($empresaId <= 0) {
    $error = 'session';
} elseif ($portalReadOnly) {
    $error = 'readonly';
} elseif ($comentario === '') {
    $error = 'invalid';
} else {
    $model = new MachoteViewModel();

    try {
        $machote = $model->getMachoteForEmpresa($machoteId, $empresaId);

        if ($machote === null) {
            throw new RuntimeException('Machote no disponible.');
        }

        $confirmado = (int) ($machote['confirmacion_empresa'] ?? 0) === 1;
        $estatusConvenio = (string) ($machote['convenio_estatus'] ?? '');
        $estaActivo = static function (?string $estatus): bool {
            if ($estatus === null) {
                return false;
            }

            $normalizado = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], (string) $estatus);
            $normalizado = function_exists('mb_strtolower') ? mb_strtolower($normalizado, 'UTF-8') : strtolower($normalizado);
            $normalizado = trim($normalizado);

            if ($normalizado === '') {
                return false;
            }

            if (str_contains($normalizado, 'activa')) {
                return true;
            }

            return str_contains($normalizado, 'revisi');
        };

        if ($confirmado || !$estaActivo($estatusConvenio)) {
            throw new RuntimeException('No se pueden agregar comentarios en este momento.');
        }

        $clausulaFinal = $clausula !== '' ? $clausula : 'General';
        $model->addComentario($machoteId, $empresaId, $clausulaFinal, $comentario, null);
        $status = 'added';
    } catch (\Throwable $exception) {
        $error = $error ?? 'internal';
    }
}

$query = http_build_query(array_filter([
    'comentario_status' => $status,
    'comentario_error' => $error,
]));

$redirect = '../view/machote_view.php?id=' . ($machoteId !== null && $machoteId !== false ? (int) $machoteId : 0);
$redirect .= $query !== '' ? '&' . $query : '';

header('Location: ' . $redirect);
exit;
