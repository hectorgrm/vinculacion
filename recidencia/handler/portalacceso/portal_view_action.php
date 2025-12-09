<?php

declare(strict_types=1);

use Residencia\Model\PortalAcceso\PortalViewModel;

/** @var \PDO $pdo */
$pdo = require_once dirname(__DIR__, 3) . '/common/model/db.php';
require_once __DIR__ . '/../../model/portalacceso/PortalViewModel.php';
require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_functions.php';

$portalId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT,
    [
        'options' => ['default' => null],
        'flags' => FILTER_NULL_ON_FAILURE,
    ]
);

function portalAccessRedirect(string $base, string $messageKey, string $message): void
{
    $separator = strpos($base, '?') === false ? '?' : '&';
    header('Location: ' . $base . $separator . $messageKey . '=' . rawurlencode($message));
    exit;
}

$redirectBase = 'portal_view.php';
$redirectTarget = $portalId !== null
    ? $redirectBase . '?id=' . rawurlencode((string) $portalId)
    : $redirectBase;

if ($portalId === null || $portalId <= 0) {
    portalAccessRedirect($redirectTarget, 'error_message', 'El identificador no es válido.');
}

$action = isset($_POST['action']) ? trim((string) $_POST['action']) : '';

try {
    $model = new PortalViewModel($pdo);
    $record = $model->findById($portalId);
} catch (\Throwable $exception) {
    error_log('Error en portal_view_action.php (find): ' . $exception->getMessage());
    portalAccessRedirect($redirectTarget, 'error_message', 'No se pudo obtener el acceso solicitado.');
}

if ($record === null) {
    portalAccessRedirect($redirectBase, 'error_message', 'No se encontró el acceso solicitado.');
}

if ($action === 'delete') {
    try {
        $model->deleteAccess($portalId);
        portalAccessRedirect('portal_list.php', 'success_message', 'El acceso fue eliminado.');
    } catch (\Throwable $exception) {
        error_log('Error en portal_view_action.php (delete): ' . $exception->getMessage());
        portalAccessRedirect($redirectTarget, 'error_message', 'No se pudo eliminar el acceso.');
    }
}

$nip = isset($_POST['nip']) && is_string($_POST['nip']) ? trim($_POST['nip']) : '';
$nip = $nip !== '' ? $nip : (isset($record['nip']) && $record['nip'] !== null ? (string) $record['nip'] : '');
$activo = isset($_POST['activo']) ? 1 : 0;
$expiracionInput = isset($_POST['expiracion']) && is_string($_POST['expiracion']) ? $_POST['expiracion'] : '';
$expiracionDb = portalAccessNormalizeExpirationForDb($expiracionInput);

if ($nip !== '' && !portalAccessIsValidNip($nip)) {
    portalAccessRedirect($redirectTarget, 'error_message', 'El NIP debe tener entre 4 y 6 dígitos numéricos.');
}

$token = isset($record['token']) ? (string) $record['token'] : '';

if ($action === 'regenerate') {
    $token = portalAccessGenerateToken();
}

try {
    $model->updateAccess($portalId, [
        'token' => $token,
        'nip' => $nip !== '' ? $nip : null,
        'activo' => $activo,
        'expiracion' => $expiracionDb,
    ]);

    $successMessage = $action === 'regenerate'
        ? 'Se regeneró el token del acceso.'
        : 'El acceso se actualizó correctamente.';

    portalAccessRedirect($redirectTarget, 'success_message', $successMessage);
} catch (\PDOException $exception) {
    $duplicateErrors = portalAccessDuplicateErrors($exception);

    if ($duplicateErrors !== []) {
        portalAccessRedirect($redirectTarget, 'error_message', implode(' ', $duplicateErrors));
    }

    error_log('Error en portal_view_action.php (update): ' . $exception->getMessage());
    portalAccessRedirect($redirectTarget, 'error_message', 'No se pudo actualizar el acceso.');
} catch (\Throwable $exception) {
    error_log('Error en portal_view_action.php (update): ' . $exception->getMessage());
    portalAccessRedirect($redirectTarget, 'error_message', 'No se pudo actualizar el acceso.');
}
