<?php
declare(strict_types=1);

namespace Residencia\Common\Helpers\Convenio;

use function is_array;

/**
 * @param mixed $value
 */
function sanitizePositiveInt($value): int
{
    $filtered = preg_replace('/[^0-9]/', '', (string) ($value ?? ''));
    if ($filtered === null || $filtered === '') {
        return 0;
    }

    return (int) $filtered;
}

/**
 * @param array<string, mixed> $post
 * @return array{convenio_id:int, motivo:?string, errors:array<int,string>, confirm:bool}
 */
function sanitizeArchivarPost(array $post): array
{
    $errors = [];
    $convenioId = sanitizePositiveInt($post['id'] ?? null);
    $empresaId = sanitizePositiveInt($post['empresa_id'] ?? null);
    $motivo = isset($post['motivo']) ? trim((string) $post['motivo']) : null;
    $confirm = isset($post['confirm']) && ((string) $post['confirm']) === '1';

    if ($convenioId <= 0) {
        $errors[] = 'Identificador de convenio no válido.';
    }

    if ($empresaId <= 0) {
        $errors[] = 'Empresa no válida.';
    }

    if ($confirm === false) {
        $errors[] = 'Debes confirmar que deseas archivar el convenio.';
    }

    return [
        'convenio_id' => $convenioId,
        'motivo' => $motivo !== '' ? $motivo : null,
        'errors' => $errors,
        'confirm' => $confirm,
        'empresa_id' => $empresaId,
    ];
}

/**
 * @param array<string, mixed>|null $convenio
 * @param array<string, mixed>|null $sanitizedPost
 * @param array<int, string> $errors
 * @return array<string, mixed>
 */
function buildArchivarViewData(?array $convenio, int $requestedId, ?int $requestedEmpresaId, ?array $sanitizedPost, array $errors, ?string $successMessage): array
{
    $convenioIdDisplay = '';
    if ($convenio !== null && isset($convenio['id'])) {
        $convenioIdDisplay = (string) $convenio['id'];
    } elseif ($requestedId > 0) {
        $convenioIdDisplay = (string) $requestedId;
    }

    $empresaIdDisplay = '';
    if ($convenio !== null && isset($convenio['empresa_id'])) {
        $empresaIdDisplay = (string) $convenio['empresa_id'];
    } elseif ($sanitizedPost !== null && isset($sanitizedPost['empresa_id']) && $sanitizedPost['empresa_id'] > 0) {
        $empresaIdDisplay = (string) $sanitizedPost['empresa_id'];
    } elseif ($requestedEmpresaId !== null) {
        $empresaIdDisplay = (string) $requestedEmpresaId;
    }

    $empresaNombreDisplay = $convenio['empresa_nombre'] ?? '';
    $machoteIdDisplay = '';
    if ($convenio !== null && isset($convenio['machote_id'])) {
        $machoteIdDisplay = (string) $convenio['machote_id'];
    }

    $motivoValue = '';
    if ($sanitizedPost !== null && $successMessage === null && isset($sanitizedPost['motivo']) && $sanitizedPost['motivo'] !== null) {
        $motivoValue = (string) $sanitizedPost['motivo'];
    }

    $confirmChecked = $sanitizedPost !== null && ($sanitizedPost['confirm'] ?? false) === true;

    $isAlreadyInactive = $convenio !== null
        && isset($convenio['estatus'])
        && ((string) $convenio['estatus'] === 'Inactiva');

    $empresaEstatus = isset($convenio['empresa_estatus']) ? (string) $convenio['empresa_estatus'] : '';
    $empresaIsCompletada = strcasecmp($empresaEstatus, 'Completada') === 0;
    $empresaIsInactiva = strcasecmp($empresaEstatus, 'Inactiva') === 0;

    $formDisabled = $successMessage !== null
        || $isAlreadyInactive
        || $empresaIsCompletada
        || $empresaIsInactiva;

    return [
        'controllerError' => $errors !== [] ? implode(' ', $errors) : null,
        'errors' => $errors,
        'successMessage' => $successMessage,
        'convenioIdDisplay' => $convenioIdDisplay,
        'empresaIdDisplay' => $empresaIdDisplay,
        'machoteIdDisplay' => $machoteIdDisplay,
        'empresaNombreDisplay' => $empresaNombreDisplay,
        'motivoValue' => $motivoValue,
        'confirmChecked' => $confirmChecked,
        'isAlreadyInactive' => $isAlreadyInactive,
        'formDisabled' => $formDisabled,
        'empresaIsCompletada' => $empresaIsCompletada,
        'empresaIsInactiva' => $empresaIsInactiva,
    ];
}

function formatArchivoLabel(?string $fechaArchivo): string
{
    if ($fechaArchivo === null || $fechaArchivo === '') {
        return 'Archivo sin fecha';
    }

    return 'Archivado el ' . date('d/m/Y H:i', strtotime($fechaArchivo));
}
