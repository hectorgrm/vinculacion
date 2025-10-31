<?php

declare(strict_types=1);

require_once __DIR__ . '/empresa_documentotipo_functions_add.php';
require_once __DIR__ . '/empresa_documentotipo_functions_list.php';

if (!function_exists('empresaDocumentoTipoEditDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     documentoId: ?int,
     *     empresa: ?array<string, mixed>,
     *     documento: ?array<string, mixed>,
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string,
     *     documentoNotFoundMessage: ?string,
     *     isActivo: bool,
     *     supportsTipoEmpresa: bool,
     *     supportsActivo: bool
     * }
     */
    function empresaDocumentoTipoEditDefaults(): array
    {
        return [
            'empresaId' => null,
            'documentoId' => null,
            'empresa' => null,
            'documento' => null,
            'formData' => empresaDocumentoTipoEditFormDefaults(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
            'inputError' => null,
            'notFoundMessage' => null,
            'documentoNotFoundMessage' => null,
            'isActivo' => true,
            'supportsTipoEmpresa' => false,
            'supportsActivo' => false,
        ];
    }
}

if (!function_exists('empresaDocumentoTipoEditFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function empresaDocumentoTipoEditFormDefaults(?int $empresaId = null, ?int $documentoId = null): array
    {
        return [
            'empresa_id' => $empresaId !== null ? (string) $empresaId : '',
            'documento_id' => $documentoId !== null ? (string) $documentoId : '',
            'nombre' => '',
            'descripcion' => '',
            'obligatorio' => '1',
            'tipo_empresa' => 'ambas',
        ];
    }
}

if (!function_exists('empresaDocumentoTipoEditNormalizeId')) {
    function empresaDocumentoTipoEditNormalizeId(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_string($value) && preg_match('/^\d+$/', $value) === 1) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        if (is_numeric($value)) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        return null;
    }
}

if (!function_exists('empresaDocumentoTipoEditInputErrorMessage')) {
    function empresaDocumentoTipoEditInputErrorMessage(): string
    {
        return 'No se proporcionaron identificadores validos para editar el documento individual.';
    }
}

if (!function_exists('empresaDocumentoTipoEditEmpresaNotFoundMessage')) {
    function empresaDocumentoTipoEditEmpresaNotFoundMessage(int $empresaId): string
    {
        return 'No se encontro la empresa solicitada (#' . $empresaId . ').';
    }
}

if (!function_exists('empresaDocumentoTipoEditDocumentoNotFoundMessage')) {
    function empresaDocumentoTipoEditDocumentoNotFoundMessage(int $documentoId): string
    {
        return 'No se encontro el documento individual solicitado (#' . $documentoId . ').';
    }
}

if (!function_exists('empresaDocumentoTipoEditControllerErrorMessage')) {
    function empresaDocumentoTipoEditControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo establecer conexion con la base de datos. Intenta nuevamente mas tarde.';
    }
}

if (!function_exists('empresaDocumentoTipoEditIsPostRequest')) {
    function empresaDocumentoTipoEditIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD'])
            && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('empresaDocumentoTipoEditSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function empresaDocumentoTipoEditSanitizeInput(array $input, ?int $empresaId = null, ?int $documentoId = null): array
    {
        $defaults = empresaDocumentoTipoEditFormDefaults($empresaId, $documentoId);
        $sanitized = $defaults;

        if ($empresaId === null && isset($input['empresa_id'])) {
            $empresaIdValue = empresaDocumentoTipoAddNormalizeEmpresaId($input['empresa_id']);
            if ($empresaIdValue !== null) {
                $sanitized['empresa_id'] = (string) $empresaIdValue;
            }
        }

        if ($documentoId === null && isset($input['documento_id'])) {
            $documentoIdValue = empresaDocumentoTipoEditNormalizeId($input['documento_id']);
            if ($documentoIdValue !== null) {
                $sanitized['documento_id'] = (string) $documentoIdValue;
            }
        }

        if (isset($input['nombre'])) {
            $sanitized['nombre'] = trim((string) $input['nombre']);
        }

        if (isset($input['descripcion'])) {
            $sanitized['descripcion'] = trim((string) $input['descripcion']);
        }

        if (isset($input['obligatorio'])) {
            $sanitized['obligatorio'] = empresaDocumentoTipoAddNormalizeObligatorio((string) $input['obligatorio']);
        }

        if (isset($input['tipo_empresa'])) {
            $sanitized['tipo_empresa'] = empresaDocumentoTipoEditNormalizeTipoEmpresa((string) $input['tipo_empresa']);
        }

        return $sanitized;
    }
}

if (!function_exists('empresaDocumentoTipoEditNormalizeTipoEmpresa')) {
    function empresaDocumentoTipoEditNormalizeTipoEmpresa(string $value): string
    {
        $normalized = strtolower(trim($value));

        $allowed = ['ambas', 'fisica', 'moral'];

        return in_array($normalized, $allowed, true) ? $normalized : 'ambas';
    }
}

if (!function_exists('empresaDocumentoTipoEditValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function empresaDocumentoTipoEditValidateData(
        array $data,
        ?int $empresaId = null,
        ?int $documentoId = null,
        bool $supportsTipoEmpresa = true
    ): array {
        $errors = [];

        $empresaIdValue = empresaDocumentoTipoAddNormalizeEmpresaId($data['empresa_id'] ?? null);
        $documentoIdValue = empresaDocumentoTipoEditNormalizeId($data['documento_id'] ?? null);

        if ($empresaIdValue === null) {
            $errors[] = 'No se recibio la empresa asociada al documento.';
        } elseif ($empresaId !== null && $empresaIdValue !== $empresaId) {
            $errors[] = 'Los datos enviados no corresponden con la empresa seleccionada.';
        }

        if ($documentoIdValue === null) {
            $errors[] = 'No se recibio el identificador del documento individual.';
        } elseif ($documentoId !== null && $documentoIdValue !== $documentoId) {
            $errors[] = 'Los datos enviados no corresponden con el documento seleccionado.';
        }

        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $obligatorio = $data['obligatorio'] ?? '';
        $tipoEmpresa = $data['tipo_empresa'] ?? '';

        if ($nombre === '') {
            $errors[] = 'El nombre del documento es obligatorio.';
        } elseif (empresaDocumentoTipoAddStringLength($nombre) > 100) {
            $errors[] = 'El nombre del documento no puede exceder 100 caracteres.';
        }

        if ($descripcion !== '' && empresaDocumentoTipoAddStringLength($descripcion) > 65535) {
            $errors[] = 'La descripcion no puede exceder 65535 caracteres.';
        }

        if (!in_array($obligatorio, ['0', '1'], true)) {
            $errors[] = 'Selecciona si el documento sera obligatorio.';
        }

        if ($supportsTipoEmpresa && !in_array($tipoEmpresa, ['ambas', 'fisica', 'moral'], true)) {
            $errors[] = 'Selecciona un tipo de empresa valido.';
        }

        return $errors;
    }
}

if (!function_exists('empresaDocumentoTipoEditHydrateForm')) {
    /**
     * @param array<string, string> $defaults
     * @param array<string, mixed>|null $record
     * @return array<string, string>
     */
    function empresaDocumentoTipoEditHydrateForm(array $defaults, ?array $record): array
    {
        if ($record === null) {
            return $defaults;
        }

        $formData = $defaults;

        if (isset($record['empresa_id'])) {
            $empresaId = empresaDocumentoTipoAddNormalizeEmpresaId($record['empresa_id']);
            if ($empresaId !== null) {
                $formData['empresa_id'] = (string) $empresaId;
            }
        }

        if (isset($record['id'])) {
            $documentoId = empresaDocumentoTipoEditNormalizeId($record['id']);
            if ($documentoId !== null) {
                $formData['documento_id'] = (string) $documentoId;
            }
        }

        if (isset($record['nombre'])) {
            $formData['nombre'] = trim((string) $record['nombre']);
        }

        if (array_key_exists('descripcion', $record)) {
            $formData['descripcion'] = trim((string) ($record['descripcion'] ?? ''));
        }

        if (isset($record['obligatorio'])) {
            $formData['obligatorio'] = (int) $record['obligatorio'] === 1 ? '1' : '0';
        }

        if (isset($record['tipo_empresa'])) {
            $formData['tipo_empresa'] = empresaDocumentoTipoEditNormalizeTipoEmpresa((string) $record['tipo_empresa']);
        }

        return $formData;
    }
}

if (!function_exists('empresaDocumentoTipoEditRecordIsActive')) {
    /**
     * @param array<string, mixed>|null $record
     */
    function empresaDocumentoTipoEditRecordIsActive(?array $record): bool
    {
        if ($record === null) {
            return true;
        }

        if (!array_key_exists('activo', $record)) {
            return true;
        }

        return (int) $record['activo'] === 1;
    }
}

if (!function_exists('empresaDocumentoTipoEditSuccessMessage')) {
    function empresaDocumentoTipoEditSuccessMessage(string $documentoNombre): string
    {
        $nombre = trim($documentoNombre);
        $label = $nombre !== '' ? $nombre : 'El documento individual';

        return $label . ' se actualizo correctamente.';
    }
}

if (!function_exists('empresaDocumentoTipoEditPersistenceErrorMessage')) {
    function empresaDocumentoTipoEditPersistenceErrorMessage(\Throwable $exception): string
    {
        return 'Ocurrio un error al actualizar el documento individual. Intenta nuevamente.';
    }
}

if (!function_exists('empresaDocumentoTipoEditInactiveUpdateErrorMessage')) {
    function empresaDocumentoTipoEditInactiveUpdateErrorMessage(): string
    {
        return 'Este documento individual se encuentra inactivo. Reactivalo para habilitar la edicion.';
    }
}

if (!function_exists('empresaDocumentoTipoEditRecordSupportsTipoEmpresa')) {
    /**
     * @param array<string, mixed>|null $record
     */
    function empresaDocumentoTipoEditRecordSupportsTipoEmpresa(?array $record): bool
    {
        return is_array($record) && array_key_exists('tipo_empresa', $record);
    }
}

if (!function_exists('empresaDocumentoTipoEditRecordSupportsActivo')) {
    /**
     * @param array<string, mixed>|null $record
     */
    function empresaDocumentoTipoEditRecordSupportsActivo(?array $record): bool
    {
        return is_array($record) && array_key_exists('activo', $record);
    }
}

if (!function_exists('empresaDocumentoTipoEditDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function empresaDocumentoTipoEditDuplicateErrors(\PDOException $exception): array
    {
        $errorInfo = $exception->errorInfo ?? null;

        if (!is_array($errorInfo) || !isset($errorInfo[1]) || (int) $errorInfo[1] !== 1062) {
            return [];
        }

        $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? strtolower($errorInfo[2]) : '';

        if ($detail !== '' && strpos($detail, 'uk_documento_tipo_empresa_nombre') !== false) {
            return ['Ya existe un documento individual con el mismo nombre para esta empresa.'];
        }

        return ['La informacion proporcionada ya se encuentra registrada.'];
    }
}

if (!function_exists('empresaDocumentoTipoEditPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array{
     *     empresa_id: int,
     *     documento_id: int,
     *     nombre: string,
     *     descripcion: ?string,
     *     obligatorio: int,
     *     tipo_empresa: string
     * }
     */
    function empresaDocumentoTipoEditPrepareForPersistence(array $data): array
    {
        $empresaId = empresaDocumentoTipoAddNormalizeEmpresaId($data['empresa_id'] ?? null) ?? 0;
        $documentoId = empresaDocumentoTipoEditNormalizeId($data['documento_id'] ?? null) ?? 0;
        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $obligatorio = empresaDocumentoTipoAddNormalizeObligatorio($data['obligatorio'] ?? '0');
        $tipoEmpresa = empresaDocumentoTipoEditNormalizeTipoEmpresa($data['tipo_empresa'] ?? '');

        return [
            'empresa_id' => $empresaId,
            'documento_id' => $documentoId,
            'nombre' => $nombre,
            'descripcion' => $descripcion === '' ? null : $descripcion,
            'obligatorio' => $obligatorio === '1' ? 1 : 0,
            'tipo_empresa' => $tipoEmpresa,
        ];
    }
}

if (!function_exists('empresaDocumentoTipoEditTipoEmpresaOptions')) {
    /**
     * @return array<string, string>
     */
    function empresaDocumentoTipoEditTipoEmpresaOptions(): array
    {
        return [
            'ambas' => 'Ambas',
            'fisica' => 'Fisica',
            'moral' => 'Moral',
        ];
    }
}

if (!function_exists('empresaDocumentoTipoEditFormValue')) {
    /**
     * @param array<string, string> $data
     */
    function empresaDocumentoTipoEditFormValue(array $data, string $field): string
    {
        return isset($data[$field]) ? (string) $data[$field] : '';
    }
}
