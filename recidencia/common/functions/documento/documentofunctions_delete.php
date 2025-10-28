<?php

declare(strict_types=1);

require_once __DIR__ . '/documentofunction_view.php';

if (!function_exists('documentoDeleteDefaults')) {
    /**
     * @return array{
     *     documentId: ?int,
     *     document: ?array<string, mixed>,
     *     empresaId: ?int,
     *     convenioId: ?int,
     *     fileMeta: array{
     *         exists: bool,
     *         absolutePath: ?string,
     *         publicUrl: ?string,
     *         filename: ?string,
     *         sizeBytes: ?int,
     *         sizeLabel: ?string,
     *         extension: ?string,
     *         canPreview: bool
     *     },
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     errorMessage: ?string
     * }
     */
    function documentoDeleteDefaults(): array
    {
        return [
            'documentId' => null,
            'document' => null,
            'empresaId' => null,
            'convenioId' => null,
            'fileMeta' => [
                'exists' => false,
                'absolutePath' => null,
                'publicUrl' => null,
                'filename' => null,
                'sizeBytes' => null,
                'sizeLabel' => null,
                'extension' => null,
                'canPreview' => false,
            ],
            'controllerError' => null,
            'notFoundMessage' => null,
            'errorMessage' => null,
        ];
    }
}

if (!function_exists('documentoDeleteFormDefaults')) {
    /**
     * @return array{id: string, empresa_id: string, convenio_id: string, confirm: bool, motivo: string}
     */
    function documentoDeleteFormDefaults(): array
    {
        return [
            'id' => '',
            'empresa_id' => '',
            'convenio_id' => '',
            'confirm' => false,
            'motivo' => '',
        ];
    }
}

if (!function_exists('documentoDeleteNormalizeId')) {
    function documentoDeleteNormalizeId(mixed $value): ?int
    {
        return documentoNormalizePositiveInt($value);
    }
}

if (!function_exists('documentoDeleteNormalizeMotivo')) {
    function documentoDeleteNormalizeMotivo(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $text = $value;
        } elseif (is_scalar($value)) {
            $text = (string) $value;
        } else {
            return null;
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = trim($text);

        return $text !== '' ? $text : null;
    }
}

if (!function_exists('documentoDeleteNormalizeBool')) {
    function documentoDeleteNormalizeBool(mixed $value): bool
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

if (!function_exists('documentoDeleteSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array{
     *     id: ?int,
     *     empresa_id: ?int,
     *     convenio_id: ?int,
     *     confirm: bool,
     *     motivo: ?string
     * }
     */
    function documentoDeleteSanitizeInput(array $input): array
    {
        return [
            'id' => documentoDeleteNormalizeId($input['id'] ?? null),
            'empresa_id' => documentoDeleteNormalizeId($input['empresa_id'] ?? null),
            'convenio_id' => documentoDeleteNormalizeId($input['convenio_id'] ?? null),
            'confirm' => documentoDeleteNormalizeBool($input['confirm'] ?? null),
            'motivo' => documentoDeleteNormalizeMotivo($input['motivo'] ?? null),
        ];
    }
}

if (!function_exists('documentoDeleteValidateRequest')) {
    /**
     * @param array{
     *     id: ?int,
     *     empresa_id: ?int,
     *     convenio_id: ?int,
     *     confirm: bool,
     *     motivo: ?string
     * } $sanitized
     * @return array<int, string>
     */
    function documentoDeleteValidateRequest(array $sanitized): array
    {
        $errors = [];

        if (!isset($sanitized['id']) || $sanitized['id'] === null || $sanitized['id'] <= 0) {
            $errors[] = 'invalid_id';
        }

        if (($sanitized['confirm'] ?? false) !== true) {
            $errors[] = 'confirm_required';
        }

        if (isset($sanitized['motivo']) && $sanitized['motivo'] !== null) {
            $length = documentoDeleteStringLength($sanitized['motivo']);
            $maxLength = 500;

            if ($length > $maxLength) {
                $errors[] = 'motivo_too_long';
            }
        }

        return $errors;
    }
}

if (!function_exists('documentoDeleteControllerErrorMessage')) {
    function documentoDeleteControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== '' ? $message : 'No se pudo preparar la eliminacion del documento.';
    }
}

if (!function_exists('documentoDeleteNotFoundMessage')) {
    function documentoDeleteNotFoundMessage(int $documentId): string
    {
        return 'No se encontro el documento solicitado (#' . $documentId . ').';
    }
}

if (!function_exists('documentoDeleteDecorateDocument')) {
    /**
     * @param array<string, mixed> $document
     * @return array<string, mixed>
     */
    function documentoDeleteDecorateDocument(array $document): array
    {
        return documentoViewDecorateDocument($document);
    }
}

if (!function_exists('documentoDeleteBuildFileMeta')) {
    /**
     * @return array{
     *     exists: bool,
     *     absolutePath: ?string,
     *     publicUrl: ?string,
     *     filename: ?string,
     *     sizeBytes: ?int,
     *     sizeLabel: ?string,
     *     extension: ?string,
     *     canPreview: bool
     * }
     */
    function documentoDeleteBuildFileMeta(?string $ruta, string $projectRoot): array
    {
        return documentoViewBuildFileMeta($ruta, $projectRoot);
    }
}

if (!function_exists('documentoDeleteErrorMessages')) {
    /**
     * @return array<string, string>
     */
    function documentoDeleteErrorMessages(): array
    {
        return [
            'invalid_id' => 'La solicitud de eliminacion no es valida.',
            'confirm_required' => 'Debes confirmar que deseas eliminar el documento.',
            'motivo_too_long' => 'El motivo no puede superar los 500 caracteres.',
            'controller' => 'No se pudo preparar la eliminacion del documento.',
            'not_found' => 'El documento solicitado no existe o ya fue eliminado.',
            'delete_failed' => 'No se pudo eliminar el documento. Intenta otra vez.',
            'method_not_allowed' => 'El metodo de envio no es valido para esta operacion.',
        ];
    }
}

if (!function_exists('documentoDeleteErrorMessageFromCode')) {
    function documentoDeleteErrorMessageFromCode(?string $code): ?string
    {
        if ($code === null || $code === '') {
            return null;
        }

        $messages = documentoDeleteErrorMessages();

        return $messages[$code] ?? null;
    }
}

if (!function_exists('documentoDeleteBuildRedirectUrl')) {
    /**
     * @param array<string, int|float|string|bool|null> $params
     */
    function documentoDeleteBuildRedirectUrl(string $basePath, array $params = []): string
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

if (!function_exists('documentoDeleteStringLength')) {
    function documentoDeleteStringLength(string $value): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value, 'UTF-8');
        }

        return strlen($value);
    }
}

if (!function_exists('documentoDeleteLogMotivo')) {
    /**
     * @param array<string, mixed> $document
     */
    function documentoDeleteLogMotivo(array $document, ?string $motivo): void
    {
        if ($motivo === null || $motivo === '') {
            return;
        }

        $parts = [];
        $parts[] = '[documento_delete]';
        $parts[] = 'Documento #' . (int) ($document['id'] ?? 0);

        $empresaId = $document['empresa_id'] ?? null;
        if ($empresaId !== null) {
            $parts[] = 'empresa #' . (int) $empresaId;
        }

        $tipoId = $document['tipo_id'] ?? null;
        if ($tipoId !== null) {
            $parts[] = 'tipo #' . (int) $tipoId;
        }

        $parts[] = 'motivo: ' . $motivo;

        error_log(implode(' ', $parts));
    }
}
