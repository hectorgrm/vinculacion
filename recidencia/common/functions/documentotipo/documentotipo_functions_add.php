<?php

declare(strict_types=1);

require_once __DIR__ . '/documentotipo_funtions_list.php';

if (!function_exists('documentoTipoAddDefaults')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     tipoEmpresaOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string
     * }
     */
    function documentoTipoAddDefaults(): array
    {
        return [
            'formData' => documentoTipoAddFormDefaults(),
            'tipoEmpresaOptions' => documentoTipoEmpresaOptions(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
        ];
    }
}

if (!function_exists('documentoTipoAddFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function documentoTipoAddFormDefaults(): array
    {
        return [
            'nombre' => '',
            'descripcion' => '',
            'obligatorio' => '1',
            'tipo_empresa' => 'ambas',
        ];
    }
}

if (!function_exists('documentoTipoAddIsPostRequest')) {
    function documentoTipoAddIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('documentoTipoAddSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function documentoTipoAddSanitizeInput(array $input): array
    {
        $defaults = documentoTipoAddFormDefaults();
        $sanitized = $defaults;

        if (isset($input['nombre'])) {
            $sanitized['nombre'] = trim((string) $input['nombre']);
        }

        if (isset($input['descripcion'])) {
            $sanitized['descripcion'] = trim((string) $input['descripcion']);
        }

        $sanitized['obligatorio'] = documentoTipoAddNormalizeObligatorio($input['obligatorio'] ?? $defaults['obligatorio']);
        $sanitized['tipo_empresa'] = documentoTipoAddNormalizeTipoEmpresa($input['tipo_empresa'] ?? $defaults['tipo_empresa']);

        return $sanitized;
    }
}

if (!function_exists('documentoTipoAddNormalizeObligatorio')) {
    function documentoTipoAddNormalizeObligatorio(mixed $value): string
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

        return in_array($normalized, ['1', 'true', 'si', 'yes', 'on'], true) ? '1' : '0';
    }
}

if (!function_exists('documentoTipoAddNormalizeTipoEmpresa')) {
    function documentoTipoAddNormalizeTipoEmpresa(mixed $value): string
    {
        $default = 'ambas';

        if (!is_scalar($value)) {
            return $default;
        }

        $normalized = strtolower(trim((string) $value));

        if ($normalized === '') {
            return $default;
        }

        $options = array_keys(documentoTipoEmpresaOptions());

        return in_array($normalized, $options, true) ? $normalized : $default;
    }
}

if (!function_exists('documentoTipoAddStringLength')) {
    function documentoTipoAddStringLength(string $value): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value, 'UTF-8');
        }

        return strlen($value);
    }
}

if (!function_exists('documentoTipoAddValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function documentoTipoAddValidateData(array $data): array
    {
        $errors = [];

        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $tipoEmpresa = $data['tipo_empresa'] ?? '';
        $obligatorio = $data['obligatorio'] ?? '';

        if ($nombre === '') {
            $errors[] = 'El nombre del documento es obligatorio.';
        } elseif (documentoTipoAddStringLength($nombre) > 100) {
            $errors[] = 'El nombre del documento no puede exceder 100 caracteres.';
        }

        if ($descripcion !== '' && documentoTipoAddStringLength($descripcion) > 65535) {
            $errors[] = 'La descripcion no puede exceder 65535 caracteres.';
        }

        if (!in_array($tipoEmpresa, array_keys(documentoTipoEmpresaOptions()), true)) {
            $errors[] = 'Selecciona un tipo de empresa valido.';
        }

        if (!in_array($obligatorio, ['0', '1'], true)) {
            $errors[] = 'Selecciona si el documento es obligatorio.';
        }

        return $errors;
    }
}

if (!function_exists('documentoTipoAddSuccessMessage')) {
    function documentoTipoAddSuccessMessage(int $id): string
    {
        return 'El tipo de documento se registro correctamente con el folio #' . $id . '.';
    }
}

if (!function_exists('documentoTipoAddControllerErrorMessage')) {
    function documentoTipoAddControllerErrorMessage(\Throwable $exception): string
    {
        return 'No se pudo establecer conexion con la base de datos. Intenta nuevamente mas tarde.';
    }
}

if (!function_exists('documentoTipoAddDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function documentoTipoAddDuplicateErrors(\PDOException $exception): array
    {
        $errorInfo = $exception->errorInfo;

        if (!is_array($errorInfo) || !isset($errorInfo[1]) || (int) $errorInfo[1] !== 1062) {
            return [];
        }

        $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? strtolower($errorInfo[2]) : '';

        if ($detail !== '' && stripos($detail, 'uk_documento_tipo_nombre') !== false) {
            return ['Ya existe un tipo de documento registrado con el mismo nombre.'];
        }

        return ['La informacion proporcionada ya se encuentra registrada.'];
    }
}

if (!function_exists('documentoTipoAddPersistenceErrorMessage')) {
    function documentoTipoAddPersistenceErrorMessage(\Throwable $exception): string
    {
        return 'Ocurrio un error al registrar el tipo de documento. Intenta nuevamente.';
    }
}

if (!function_exists('documentoTipoAddPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array{
     *     nombre: string,
     *     descripcion: ?string,
     *     obligatorio: int,
     *     tipo_empresa: string
     * }
     */
    function documentoTipoAddPrepareForPersistence(array $data): array
    {
        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $tipoEmpresa = documentoTipoAddNormalizeTipoEmpresa($data['tipo_empresa'] ?? '');
        $obligatorio = documentoTipoAddNormalizeObligatorio($data['obligatorio'] ?? '0');

        return [
            'nombre' => $nombre,
            'descripcion' => $descripcion === '' ? null : $descripcion,
            'obligatorio' => $obligatorio === '1' ? 1 : 0,
            'tipo_empresa' => $tipoEmpresa,
        ];
    }
}

if (!function_exists('documentoTipoAddFormValue')) {
    /**
     * @param array<string, string> $data
     */
    function documentoTipoAddFormValue(array $data, string $field): string
    {
        return isset($data[$field]) ? (string) $data[$field] : '';
    }
}
