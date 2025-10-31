<?php

declare(strict_types=1);

if (!function_exists('empresaDocumentoTipoAddDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function empresaDocumentoTipoAddDefaults(): array
    {
        return [
            'empresaId' => null,
            'empresa' => null,
            'formData' => empresaDocumentoTipoAddFormDefaults(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
            'inputError' => null,
            'notFoundMessage' => null,
        ];
    }
}

if (!function_exists('empresaDocumentoTipoAddFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function empresaDocumentoTipoAddFormDefaults(?int $empresaId = null): array
    {
        return [
            'empresa_id' => $empresaId !== null ? (string) $empresaId : '',
            'nombre' => '',
            'descripcion' => '',
            'obligatorio' => '1',
        ];
    }
}

if (!function_exists('empresaDocumentoTipoAddNormalizeEmpresaId')) {
    function empresaDocumentoTipoAddNormalizeEmpresaId(mixed $value): ?int
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

if (!function_exists('empresaDocumentoTipoAddInputErrorMessage')) {
    function empresaDocumentoTipoAddInputErrorMessage(): string
    {
        return 'No se proporciono un identificador de empresa valido.';
    }
}

if (!function_exists('empresaDocumentoTipoAddNotFoundMessage')) {
    function empresaDocumentoTipoAddNotFoundMessage(int $empresaId): string
    {
        return 'No se encontro la empresa solicitada (#' . $empresaId . ').';
    }
}

if (!function_exists('empresaDocumentoTipoAddControllerErrorMessage')) {
    function empresaDocumentoTipoAddControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo establecer conexion con la base de datos. Intenta nuevamente mas tarde.';
    }
}

if (!function_exists('empresaDocumentoTipoAddIsPostRequest')) {
    function empresaDocumentoTipoAddIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD'])
            && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('empresaDocumentoTipoAddSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function empresaDocumentoTipoAddSanitizeInput(array $input, ?int $empresaId = null): array
    {
        $defaults = empresaDocumentoTipoAddFormDefaults($empresaId);
        $sanitized = $defaults;

        if ($empresaId === null && isset($input['empresa_id'])) {
            $empresaIdValue = empresaDocumentoTipoAddNormalizeEmpresaId($input['empresa_id']);
            if ($empresaIdValue !== null) {
                $sanitized['empresa_id'] = (string) $empresaIdValue;
            }
        }

        if (isset($input['nombre'])) {
            $sanitized['nombre'] = trim((string) $input['nombre']);
        }

        if (isset($input['descripcion'])) {
            $sanitized['descripcion'] = trim((string) $input['descripcion']);
        }

        $sanitized['obligatorio'] = empresaDocumentoTipoAddNormalizeObligatorio(
            $input['obligatorio'] ?? $defaults['obligatorio']
        );

        return $sanitized;
    }
}

if (!function_exists('empresaDocumentoTipoAddNormalizeObligatorio')) {
    function empresaDocumentoTipoAddNormalizeObligatorio(mixed $value): string
    {
        if ($value === null) {
            return '0';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value)) {
            return $value === 1 ? '1' : '0';
        }

        if (!is_scalar($value)) {
            return '0';
        }

        $normalized = strtolower(trim((string) $value));

        if ($normalized === '') {
            return '0';
        }

        return in_array($normalized, ['1', 'true', 'si', 'yes', 'on'], true) ? '1' : '0';
    }
}

if (!function_exists('empresaDocumentoTipoAddStringLength')) {
    function empresaDocumentoTipoAddStringLength(string $value): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value, 'UTF-8');
        }

        return strlen($value);
    }
}

if (!function_exists('empresaDocumentoTipoAddValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function empresaDocumentoTipoAddValidateData(array $data, ?int $empresaId = null): array
    {
        $errors = [];

        $empresaIdValue = empresaDocumentoTipoAddNormalizeEmpresaId($data['empresa_id'] ?? null);

        if ($empresaIdValue === null) {
            $errors[] = 'No se recibio la empresa a la que se asociara el documento.';
        } elseif ($empresaId !== null && $empresaIdValue !== $empresaId) {
            $errors[] = 'Los datos enviados no corresponden con la empresa seleccionada.';
        }

        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $obligatorio = $data['obligatorio'] ?? '';

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

        return $errors;
    }
}

if (!function_exists('empresaDocumentoTipoAddSuccessMessage')) {
    function empresaDocumentoTipoAddSuccessMessage(string $empresaNombre, int $documentoId): string
    {
        $nombre = trim($empresaNombre);
        $empresaLabel = $nombre !== '' ? $nombre : 'la empresa seleccionada';

        return 'El documento individual se registro correctamente para ' . $empresaLabel . ' (folio #' . $documentoId . ').';
    }
}

if (!function_exists('empresaDocumentoTipoAddPersistenceErrorMessage')) {
    function empresaDocumentoTipoAddPersistenceErrorMessage(\Throwable $exception): string
    {
        return 'Ocurrio un error al registrar el documento individual. Intenta nuevamente.';
    }
}

if (!function_exists('empresaDocumentoTipoAddDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function empresaDocumentoTipoAddDuplicateErrors(\PDOException $exception): array
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

if (!function_exists('empresaDocumentoTipoAddPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array{empresa_id: int, nombre: string, descripcion: ?string, obligatorio: int}
     */
    function empresaDocumentoTipoAddPrepareForPersistence(array $data): array
    {
        $empresaId = empresaDocumentoTipoAddNormalizeEmpresaId($data['empresa_id'] ?? null) ?? 0;
        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $obligatorio = empresaDocumentoTipoAddNormalizeObligatorio($data['obligatorio'] ?? '0');

        return [
            'empresa_id' => $empresaId,
            'nombre' => $nombre,
            'descripcion' => $descripcion === '' ? null : $descripcion,
            'obligatorio' => $obligatorio === '1' ? 1 : 0,
        ];
    }
}

if (!function_exists('empresaDocumentoTipoAddFormValue')) {
    /**
     * @param array<string, string> $data
     */
    function empresaDocumentoTipoAddFormValue(array $data, string $field): string
    {
        return isset($data[$field]) ? (string) $data[$field] : '';
    }
}
