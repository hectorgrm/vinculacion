<?php

declare(strict_types=1);

require_once __DIR__ . '/documentotipo_funtions_list.php';

if (!function_exists('documentoTipoDeleteDefaults')) {
    /**
     * @return array{
     *     documentoTipoId: ?int,
     *     documentoTipo: ?array<string, mixed>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     errorMessage: ?string,
     *     statusMessage: ?string
     * }
     */
    function documentoTipoDeleteDefaults(): array
    {
        return [
            'documentoTipoId' => null,
            'documentoTipo' => null,
            'controllerError' => null,
            'notFoundMessage' => null,
            'errorMessage' => null,
            'statusMessage' => null,
        ];
    }
}

if (!function_exists('documentoTipoDeleteNormalizeId')) {
    function documentoTipoDeleteNormalizeId(mixed $value): ?int
    {
        if (!is_scalar($value)) {
            return null;
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '' || preg_match('/^\d+$/', $stringValue) !== 1) {
            return null;
        }

        $id = (int) $stringValue;

        return $id > 0 ? $id : null;
    }
}

if (!function_exists('documentoTipoDeleteNormalizeConfirm')) {
    function documentoTipoDeleteNormalizeConfirm(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (!is_scalar($value)) {
            return false;
        }

        $normalized = strtolower(trim((string) $value));
        if ($normalized === '') {
            return false;
        }

        return in_array($normalized, ['1', 'true', 'si', 'yes', 'on', 'acepto'], true);
    }
}

if (!function_exists('documentoTipoDeleteSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array{id: ?int, confirm: bool}
     */
    function documentoTipoDeleteSanitizeInput(array $input): array
    {
        return [
            'id' => documentoTipoDeleteNormalizeId($input['id'] ?? null),
            'confirm' => documentoTipoDeleteNormalizeConfirm($input['confirm'] ?? null),
        ];
    }
}

if (!function_exists('documentoTipoDeleteValidateRequest')) {
    /**
     * @param array{id: ?int, confirm: bool} $data
     * @return array<int, string>
     */
    function documentoTipoDeleteValidateRequest(array $data): array
    {
        $errors = [];

        if ($data['id'] === null) {
            $errors[] = 'invalid_id';
        }

        if ($data['confirm'] !== true) {
            $errors[] = 'confirm_required';
        }

        return $errors;
    }
}

if (!function_exists('documentoTipoDeleteErrorMessages')) {
    /**
     * @return array<string, string>
     */
    function documentoTipoDeleteErrorMessages(): array
    {
        return [
            'invalid_id' => 'La solicitud de eliminacion no es valida.',
            'confirm_required' => 'Debes confirmar que deseas continuar.',
            'controller' => 'No se pudo procesar la solicitud en este momento. Intenta nuevamente.',
            'not_found' => 'El tipo de documento solicitado no existe.',
            'delete_failed' => 'No se pudo eliminar o desactivar el tipo de documento. Intenta mas tarde.',
            'method_not_allowed' => 'El metodo enviado no es valido para esta operacion.',
        ];
    }
}

if (!function_exists('documentoTipoDeleteErrorMessageFromCode')) {
    function documentoTipoDeleteErrorMessageFromCode(?string $code): ?string
    {
        if ($code === null || $code === '') {
            return null;
        }

        $messages = documentoTipoDeleteErrorMessages();

        return $messages[$code] ?? null;
    }
}

if (!function_exists('documentoTipoDeleteStatusMessages')) {
    /**
     * @return array<string, string>
     */
    function documentoTipoDeleteStatusMessages(): array
    {
        return [
            'deleted' => 'El tipo de documento se elimino correctamente.',
            'deactivated' => 'El tipo de documento esta en uso, por lo que se desactivo y no aparecera en nuevos registros.',
        ];
    }
}

if (!function_exists('documentoTipoDeleteStatusMessageFromCode')) {
    function documentoTipoDeleteStatusMessageFromCode(?string $code): ?string
    {
        if ($code === null || $code === '') {
            return null;
        }

        $messages = documentoTipoDeleteStatusMessages();

        return $messages[$code] ?? null;
    }
}

if (!function_exists('documentoTipoDeleteControllerErrorMessage')) {
    function documentoTipoDeleteControllerErrorMessage(\Throwable $exception): string
    {
        return 'No se pudo conectar con la base de datos. Intenta nuevamente.';
    }
}

if (!function_exists('documentoTipoDeleteNotFoundMessage')) {
    function documentoTipoDeleteNotFoundMessage(int $documentoTipoId): string
    {
        return 'No se encontro el tipo de documento #' . $documentoTipoId . '.';
    }
}

if (!function_exists('documentoTipoDeleteDecorate')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    function documentoTipoDeleteDecorate(array $record): array
    {
        $decorated = $record;

        $decorated['nombre_label'] = documentoTipoValueOrDefault($record['nombre'] ?? null, 'Sin nombre');
        $decorated['descripcion_label'] = documentoTipoValueOrDefault($record['descripcion'] ?? null, 'Sin descripcion');
        $decorated['tipo_empresa_label'] = documentoTipoRenderEmpresaLabel($record['tipo_empresa'] ?? null);
        $decorated['obligatorio_label'] = documentoTipoRenderObligatorioLabel($record['obligatorio'] ?? null);
        $decorated['obligatorio_class'] = documentoTipoRenderObligatorioClass($record['obligatorio'] ?? null);

        $activo = isset($record['activo']) ? documentoTipoCastBool($record['activo']) : true;
        $decorated['activo'] = $activo;
        $decorated['activo_label'] = $activo ? 'Activo' : 'Inactivo';

        return $decorated;
    }
}

if (!function_exists('documentoTipoDeleteBuildRedirectUrl')) {
    /**
     * @param array<string, scalar|null> $params
     */
    function documentoTipoDeleteBuildRedirectUrl(string $basePath, array $params = []): string
    {
        if ($params === []) {
            return $basePath;
        }

        $filtered = [];
        foreach ($params as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                $filtered[$key] = $value ? '1' : '0';
                continue;
            }

            if (is_scalar($value)) {
                $filtered[$key] = (string) $value;
            }
        }

        if ($filtered === []) {
            return $basePath;
        }

        $query = http_build_query($filtered);
        if ($query === '') {
            return $basePath;
        }

        $separator = strpos($basePath, '?') === false ? '?' : '&';

        return $basePath . $separator . $query;
    }
}

if (!function_exists('documentoTipoDeleteStatusMessage')) {
    /**
     * @param array<string, mixed>|null $record
     */
    function documentoTipoDeleteStatusMessage(?string $statusCode, ?array $record): ?string
    {
        $baseMessage = documentoTipoDeleteStatusMessageFromCode($statusCode);
        if ($baseMessage === null) {
            return null;
        }

        if (!is_array($record) || !isset($record['nombre'])) {
            return $baseMessage;
        }

        $label = trim((string) $record['nombre']);
        if ($label === '') {
            return $baseMessage;
        }

        return str_replace('El tipo de documento', 'El tipo de documento "' . $label . '"', $baseMessage);
    }
}

if (!function_exists('documentoTipoDeleteIsPostRequest')) {
    function documentoTipoDeleteIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}
