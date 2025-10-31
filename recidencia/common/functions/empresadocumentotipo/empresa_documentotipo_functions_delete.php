<?php

declare(strict_types=1);

require_once __DIR__ . '/empresa_documentotipo_functions_add.php';
require_once __DIR__ . '/empresa_documentotipo_functions_edit.php';
require_once __DIR__ . '/empresa_documentotipo_functions_list.php';

if (!function_exists('empresaDocumentoTipoDeleteDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     documentoId: ?int,
     *     empresa: ?array<string, mixed>,
     *     documento: ?array<string, mixed>,
     *     usageCount: int,
     *     supportsActivo: bool,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string,
     *     errorMessage: ?string,
     *     statusMessage: ?string
     * }
     */
    function empresaDocumentoTipoDeleteDefaults(): array
    {
        return [
            'empresaId' => null,
            'documentoId' => null,
            'empresa' => null,
            'documento' => null,
            'usageCount' => 0,
            'supportsActivo' => false,
            'controllerError' => null,
            'inputError' => null,
            'notFoundMessage' => null,
            'errorMessage' => null,
            'statusMessage' => null,
        ];
    }
}

if (!function_exists('empresaDocumentoTipoDeleteFormDefaults')) {
    /**
     * @param ?int $empresaId
     * @param ?int $documentoId
     * @return array{id: string, empresa_id: string, confirm: bool}
     */
    function empresaDocumentoTipoDeleteFormDefaults(?int $empresaId = null, ?int $documentoId = null): array
    {
        return [
            'id' => $documentoId !== null ? (string) $documentoId : '',
            'empresa_id' => $empresaId !== null ? (string) $empresaId : '',
            'confirm' => false,
        ];
    }
}

if (!function_exists('empresaDocumentoTipoDeleteNormalizeId')) {
    function empresaDocumentoTipoDeleteNormalizeId(mixed $value): ?int
    {
        return empresaDocumentoTipoEditNormalizeId($value);
    }
}

if (!function_exists('empresaDocumentoTipoDeleteNormalizeEmpresaId')) {
    function empresaDocumentoTipoDeleteNormalizeEmpresaId(mixed $value): ?int
    {
        return empresaDocumentoTipoAddNormalizeEmpresaId($value);
    }
}

if (!function_exists('empresaDocumentoTipoDeleteNormalizeBool')) {
    function empresaDocumentoTipoDeleteNormalizeBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            $normalized = strtolower(trim((string) $value));

            return !in_array($normalized, ['0', 'false', 'no', 'off', ''], true);
        }

        return false;
    }
}

if (!function_exists('empresaDocumentoTipoDeleteSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array{id: ?int, empresa_id: ?int, confirm: bool}
     */
    function empresaDocumentoTipoDeleteSanitizeInput(array $input): array
    {
        return [
            'id' => empresaDocumentoTipoDeleteNormalizeId($input['id'] ?? ($input['documento_id'] ?? null)),
            'empresa_id' => empresaDocumentoTipoDeleteNormalizeEmpresaId($input['empresa_id'] ?? ($input['id_empresa'] ?? null)),
            'confirm' => empresaDocumentoTipoDeleteNormalizeBool($input['confirm'] ?? null),
        ];
    }
}

if (!function_exists('empresaDocumentoTipoDeleteValidateRequest')) {
    /**
     * @param array{id: ?int, empresa_id: ?int, confirm: bool} $sanitized
     * @return array<int, string>
     */
    function empresaDocumentoTipoDeleteValidateRequest(array $sanitized): array
    {
        $errors = [];

        if ($sanitized['id'] === null) {
            $errors[] = 'invalid_id';
        }

        if ($sanitized['empresa_id'] === null) {
            $errors[] = 'invalid_empresa';
        }

        if ($sanitized['confirm'] !== true) {
            $errors[] = 'confirm_required';
        }

        return $errors;
    }
}

if (!function_exists('empresaDocumentoTipoDeleteErrorMessages')) {
    /**
     * @return array<string, string>
     */
    function empresaDocumentoTipoDeleteErrorMessages(): array
    {
        return [
            'invalid_id' => 'La solicitud de eliminacion no es valida.',
            'invalid_empresa' => 'No se proporciono una empresa valida.',
            'confirm_required' => 'Debes confirmar que deseas eliminar este documento individual.',
            'controller' => 'No se pudo preparar la eliminacion del documento individual.',
            'not_found' => 'El documento individual solicitado no existe o ya fue removido.',
            'delete_failed' => 'No se pudo completar la eliminacion. Intenta nuevamente.',
            'inactive_not_supported' => 'El documento esta en uso y la tabla no admite desactivarlo automaticamente.',
            'method_not_allowed' => 'El metodo de envio no es valido para esta operacion.',
        ];
    }
}

if (!function_exists('empresaDocumentoTipoDeleteErrorMessageFromCode')) {
    function empresaDocumentoTipoDeleteErrorMessageFromCode(?string $code): ?string
    {
        if ($code === null || $code === '') {
            return null;
        }

        $messages = empresaDocumentoTipoDeleteErrorMessages();

        return $messages[$code] ?? null;
    }
}

if (!function_exists('empresaDocumentoTipoDeleteControllerErrorMessage')) {
    function empresaDocumentoTipoDeleteControllerErrorMessage(\Throwable $throwable): string
    {
        $message = trim((string) $throwable->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo establecer conexion con la base de datos. Intenta mas tarde.';
    }
}

if (!function_exists('empresaDocumentoTipoDeleteNotFoundMessage')) {
    function empresaDocumentoTipoDeleteNotFoundMessage(int $documentoId): string
    {
        return 'No se encontro el documento individual solicitado (#' . $documentoId . ').';
    }
}

if (!function_exists('empresaDocumentoTipoDeleteStatusMessage')) {
    /**
     * @param array<string, mixed>|null $documento
     */
    function empresaDocumentoTipoDeleteStatusMessage(?string $statusCode, ?array $documento = null, ?int $usageCount = null): ?string
    {
        if ($statusCode === null || $statusCode === '') {
            return null;
        }

        $nombre = empresaDocumentoTipoDeleteExtractNombre($documento);

        return match ($statusCode) {
            'deleted' => $nombre !== ''
                ? $nombre . ' se elimino permanentemente.'
                : 'El documento individual se elimino permanentemente.',
            'deactivated' => $nombre !== ''
                ? $nombre . ' se desactivo porque tiene archivos relacionados.'
                    . ($usageCount !== null && $usageCount > 0 ? ' (' . $usageCount . ' archivo' . ($usageCount === 1 ? '' : 's') . ').' : '')
                : 'El documento individual se desactivo porque tiene archivos relacionados.'
                    . ($usageCount !== null && $usageCount > 0 ? ' (' . $usageCount . ' archivo' . ($usageCount === 1 ? '' : 's') . ').' : ''),
            default => null,
        };
    }
}

if (!function_exists('empresaDocumentoTipoDeleteExtractNombre')) {
    /**
     * @param array<string, mixed>|null $documento
     */
    function empresaDocumentoTipoDeleteExtractNombre(?array $documento): string
    {
        if (!is_array($documento)) {
            return '';
        }

        $nombre = $documento['nombre'] ?? ($documento['nombre_label'] ?? null);

        if ($nombre === null) {
            return '';
        }

        $trimmed = trim((string) $nombre);

        return $trimmed !== '' ? $trimmed : '';
    }
}

if (!function_exists('empresaDocumentoTipoDeleteDecorateDocumento')) {
    /**
     * @param array<string, mixed> $documento
     * @return array<string, mixed>
     */
    function empresaDocumentoTipoDeleteDecorateDocumento(array $documento): array
    {
        $decorated = $documento;

        $nombre = empresaDocumentoTipoListValueOrDefault($documento['nombre'] ?? null, 'Documento individual');
        $descripcion = empresaDocumentoTipoListValueOrDefault($documento['descripcion'] ?? null, 'Sin descripcion');
        $obligatorio = empresaDocumentoTipoListCastBool($documento['obligatorio'] ?? null);

        $decorated['nombre_label'] = $nombre;
        $decorated['descripcion_label'] = $descripcion;
        $decorated['obligatorio'] = $obligatorio;
        $decorated['obligatorio_label'] = empresaDocumentoTipoListObligatorioLabel($obligatorio);
        $decorated['obligatorio_badge_class'] = empresaDocumentoTipoListObligatorioClass($obligatorio);

        $decorated['supports_activo'] = array_key_exists('activo', $documento);
        $decorated['activo'] = empresaDocumentoTipoDeleteRecordIsActive($documento);
        $decorated['estado_label'] = $decorated['activo'] ? 'Activo' : 'Inactivo';
        $decorated['estado_badge_class'] = $decorated['activo'] ? 'badge ok' : 'badge warn';

        if (array_key_exists('creado_en', $documento)) {
            $decorated['creado_en_label'] = empresaDocumentoTipoListFormatDate($documento['creado_en'] ?? null);
        }

        if (array_key_exists('tipo_empresa', $documento)) {
            $decorated['tipo_empresa'] = $documento['tipo_empresa'];
            $decorated['tipo_empresa_label'] = empresaDocumentoTipoDeleteTipoEmpresaLabel($documento['tipo_empresa']);
        } else {
            $decorated['tipo_empresa_label'] = 'No especificado';
        }

        return $decorated;
    }
}

if (!function_exists('empresaDocumentoTipoDeleteDecorateEmpresa')) {
    /**
     * @param array<string, mixed>|null $empresa
     * @return array<string, mixed>|null
     */
    function empresaDocumentoTipoDeleteDecorateEmpresa(?array $empresa): ?array
    {
        if (!is_array($empresa)) {
            return null;
        }

        return empresaDocumentoTipoListDecorateEmpresa($empresa);
    }
}

if (!function_exists('empresaDocumentoTipoDeleteRecordIsActive')) {
    /**
     * @param array<string, mixed> $documento
     */
    function empresaDocumentoTipoDeleteRecordIsActive(array $documento): bool
    {
        if (!array_key_exists('activo', $documento)) {
            return true;
        }

        $value = $documento['activo'];

        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));

            return !in_array($normalized, ['0', 'false', 'no', 'inactivo'], true);
        }

        return true;
    }
}

if (!function_exists('empresaDocumentoTipoDeleteTipoEmpresaLabel')) {
    function empresaDocumentoTipoDeleteTipoEmpresaLabel(mixed $value): string
    {
        if (!is_string($value)) {
            return 'No especificado';
        }

        $normalized = strtolower(trim($value));
        $options = empresaDocumentoTipoEditTipoEmpresaOptions();

        return $options[$normalized] ?? 'No especificado';
    }
}

if (!function_exists('empresaDocumentoTipoDeleteBuildRedirectUrl')) {
    /**
     * @param array<string, int|float|string|bool|null> $params
     */
    function empresaDocumentoTipoDeleteBuildRedirectUrl(string $basePath, array $params = []): string
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
            } else {
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

        return (strpos($basePath, '?') === false ? $basePath . '?' : $basePath . '&') . $query;
    }
}

if (!function_exists('empresaDocumentoTipoDeleteUsageMessage')) {
    function empresaDocumentoTipoDeleteUsageMessage(int $usageCount): string
    {
        if ($usageCount <= 0) {
            return 'No hay archivos vinculados.';
        }

        $suffix = $usageCount === 1 ? '' : 's';

        return $usageCount . ' archivo' . $suffix . ' vinculado' . $suffix . '.';
    }
}
