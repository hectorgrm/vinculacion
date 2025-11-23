<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../common/auth.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoriafunctions.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_auditoria.php';

use Common\Model\Database;
use Residencia\Model\Convenio\ConvenioMachoteModel;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../view/convenio/convenio_list.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$contenido = isset($_POST['contenido']) ? (string) $_POST['contenido'] : '';
$redirect = isset($_POST['redirect']) ? (string) $_POST['redirect'] : '';

if ($id === false || $id === null) {
    redirectWithStatus(null, 'invalid_id', $redirect);
}

$connection = Database::getConnection();
$model = new ConvenioMachoteModel($connection);

$machoteActual = $model->getById($id);

if ($machoteActual === null) {
    redirectWithStatus(null, 'invalid_id', $redirect);
}

if ((int) ($machoteActual['confirmacion_empresa'] ?? 0) === 1) {
    redirectWithStatus($id, 'locked', $redirect);
}

try {
    $guardado = $model->updateContent($id, $contenido);
} catch (\PDOException) {
    $guardado = false;
}

$contenidoAnterior = (string) ($machoteActual['contenido_html'] ?? '');
if ($guardado && $contenidoAnterior !== $contenido) {
    registrarAuditoriaCambioMachote($id, $contenidoAnterior, $contenido);
}

$status = $guardado ? 'saved' : 'save_error';
redirectWithStatus($id, $status, $redirect);

function redirectWithStatus(?int $machoteId, string $status, string $redirect): void
{
    $params = [];

    if ($machoteId !== null) {
        $params['id'] = $machoteId;
    }

    if ($status === 'saved') {
        $params['machote_status'] = $status;
    } else {
        $params['machote_error'] = $status;
    }

    if ($machoteId === null) {
        $target = '../../view/convenio/convenio_list.php';
    } elseif ($redirect === 'machote_revisar') {
        $target = '../../view/machote/machote_revisar.php';
    } else {
        $target = '../../view/machote/machote_edit.php';
    }

    $query = http_build_query($params);
    $url = $target . ($query !== '' ? '?' . $query : '');

    header('Location: ' . $url);
    exit;
}

function registrarAuditoriaCambioMachote(int $machoteId, string $anterior, string $nuevo): void
{
    $contexto = convenioCurrentAuditContext();

    $payload = [
        'accion' => 'actualizar_machote_html',
        'entidad' => 'rp_convenio_machote',
        'entidad_id' => $machoteId,
        'detalles' => [
            [
                'campo' => 'contenido_html',
                'campo_label' => 'Contenido del machote',
                'valor_anterior' => machoteAuditoriaSnippet($anterior),
                'valor_nuevo' => machoteAuditoriaSnippet($nuevo),
            ],
        ],
    ];

    if (isset($contexto['actor_tipo'])) {
        $payload['actor_tipo'] = $contexto['actor_tipo'];
    }

    if (isset($contexto['actor_id'])) {
        $payload['actor_id'] = $contexto['actor_id'];
    }

    if (isset($contexto['ip'])) {
        $payload['ip'] = $contexto['ip'];
    }

    auditoriaRegistrarEvento($payload);
}

function machoteAuditoriaSnippet(string $html, int $maxLength = 200): string
{
    $text = strip_tags($html);
    $text = preg_replace('/\s+/', ' ', $text) ?? '';
    $text = trim($text);

    if ($text === '') {
        return '[Vacío]';
    }

    if (function_exists('mb_substr')) {
        $text = mb_substr($text, 0, $maxLength, 'UTF-8');
    } else {
        $text = substr($text, 0, $maxLength);
    }

    return $text;
}
