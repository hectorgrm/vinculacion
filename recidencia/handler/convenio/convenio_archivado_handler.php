<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/convenio/ConvenioArchivarController.php';
require_once __DIR__ . '/../../common/helpers/convenio/convenio_archivar_helper.php';

use Residencia\Controller\Convenio\ConvenioArchivarController;
use function Residencia\Common\Helpers\Convenio\sanitizePositiveInt;

$archivoId = sanitizePositiveInt($_GET['id'] ?? null);
$errors = [];
$archivo = null;
$controllerError = null;

try {
    $controller = new ConvenioArchivarController();
    if ($archivoId > 0) {
        $archivo = $controller->obtenerArchivoPorId($archivoId);
    }
    if ($archivo === null) {
        $errors[] = 'No se encontró el archivo solicitado.';
    }
} catch (\Throwable $throwable) {
    $controllerError = $throwable->getMessage();
    $errors[] = $controllerError !== '' ? $controllerError : 'No se pudo obtener el convenio archivado.';
}

$snapshot = $archivo['snapshot_data'] ?? [];

/**
 * @param mixed $value
 * @return array<int, mixed>
 */
$normalizeList = static function ($value): array {
    return is_array($value) ? $value : [];
};

$snapshotConvenio = $normalizeList($snapshot['convenio'] ?? null);
$snapshotMachote = $normalizeList($snapshot['machote'] ?? null);
$snapshotRevisiones = $normalizeList($snapshot['machote_revisiones'] ?? null);
$snapshotRevisionMsgs = $normalizeList($snapshot['machote_revision_mensajes'] ?? null);
$snapshotRevisionFiles = $normalizeList($snapshot['machote_revision_archivos'] ?? null);
$snapshotComentarios = $normalizeList($snapshot['comentarios'] ?? null);
$snapshotDocumentos = $normalizeList($snapshot['documentos'] ?? null);
$snapshotEstudiantes = $normalizeList($snapshot['estudiantes'] ?? null);
$snapshotAsignaciones = $normalizeList($snapshot['asignaciones'] ?? null);
$snapshotAuditoria = $normalizeList($snapshot['auditoria_detalle'] ?? ($snapshot['bitacora'] ?? null));

$rawMetadata = $snapshot['metadata'] ?? [];
if (!is_array($rawMetadata)) {
    $rawMetadata = [];
}
$snapshotMetadata = $rawMetadata !== [] && array_keys($rawMetadata) !== range(0, count($rawMetadata) - 1)
    ? [$rawMetadata]
    : $normalizeList($rawMetadata);

return [
    'errors' => $errors,
    'controllerError' => $controllerError,
    'archivo' => $archivo,
    'snapshot' => $snapshot,
    'snapshotConvenio' => $snapshotConvenio,
    'snapshotMachote' => $snapshotMachote,
    'snapshotRevisiones' => $snapshotRevisiones,
    'snapshotRevisionMsgs' => $snapshotRevisionMsgs,
    'snapshotRevisionFiles' => $snapshotRevisionFiles,
    'snapshotComentarios' => $snapshotComentarios,
    'snapshotDocumentos' => $snapshotDocumentos,
    'snapshotEstudiantes' => $snapshotEstudiantes,
    'snapshotAsignaciones' => $snapshotAsignaciones,
    'snapshotAuditoria' => $snapshotAuditoria,
    'snapshotMetadata' => $snapshotMetadata,
];
