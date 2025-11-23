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
    [$valorAnterior, $valorNuevo] = machoteAuditoriaValores($anterior, $nuevo);

    $payload = [
        'accion' => 'actualizar_machote_html',
        'entidad' => 'rp_convenio_machote',
        'entidad_id' => $machoteId,
        'detalles' => [
            [
                'campo' => 'contenido_html',
                'campo_label' => 'Contenido del machote',
                'valor_anterior' => $valorAnterior,
                'valor_nuevo' => $valorNuevo,
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

function machoteAuditoriaValores(string $anterior, string $nuevo, int $maxLength = 120): array
{
    $clean = static function (string $html): string {
        $text = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text) ?? '';
        return trim($text);
    };

    $len = static function (string $text): int {
        return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
    };

    $sub = static function (string $text, int $start, ?int $length = null): string {
        if (function_exists('mb_substr')) {
            return mb_substr($text, $start, $length ?? null, 'UTF-8');
        }
        return substr($text, $start, $length ?? strlen($text));
    };

    $wrapSnippet = static function (string $text) use ($len, $sub, $maxLength): string {
        if ($text === '') {
            return '—';
        }

        if ($len($text) > $maxLength) {
            return $sub($text, 0, $maxLength - 1) . '…';
        }

        return $text;
    };

    $a = $clean($anterior);
    $b = $clean($nuevo);

    // Alta: antes vacío y ahora con texto
    if ($a === '' && $b !== '') {
        return ['—', 'SE AGREGÓ: "' . $wrapSnippet($b) . '"'];
    }

    // Baja: antes con texto y ahora vacío
    if ($a !== '' && $b === '') {
        return [$wrapSnippet($a), '—'];
    }

    // Si ambos son vacíos, no hay diferencia relevante
    if ($a === '' && $b === '') {
        return ['—', '—'];
    }

    // Encontrar prefijo y sufijo comunes para aislar el fragmento cambiado
    $lenA = $len($a);
    $lenB = $len($b);
    $maxPrefix = min($lenA, $lenB);

    $prefix = 0;
    while ($prefix < $maxPrefix && $sub($a, $prefix, 1) === $sub($b, $prefix, 1)) {
        $prefix++;
    }

    $suffix = 0;
    while (
        $suffix < ($lenA - $prefix) &&
        $suffix < ($lenB - $prefix) &&
        $sub($a, $lenA - 1 - $suffix, 1) === $sub($b, $lenB - 1 - $suffix, 1)
    ) {
        $suffix++;
    }

    $deltaA = $sub($a, $prefix, $lenA - $prefix - $suffix);
    $deltaB = $sub($b, $prefix, $lenB - $prefix - $suffix);

    // Alta localizada
    if ($deltaA === '' && $deltaB !== '') {
        return ['—', 'SE AGREGÓ: "' . $wrapSnippet($deltaB) . '"'];
    }

    // Baja localizada
    if ($deltaA !== '' && $deltaB === '') {
        return [$wrapSnippet($deltaA), '—'];
    }

    // Cambio localizado
    return [$wrapSnippet($deltaA), $wrapSnippet($deltaB)];
}
