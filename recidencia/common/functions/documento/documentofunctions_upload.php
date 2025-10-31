<?php

declare(strict_types=1);

require_once __DIR__ . '/documentofunctions_list.php';

if (!function_exists('documentoUploadDefaults')) {
    /**
     * @param array<string, mixed> $query
     * @return array{
     *     formData: array<string, string>,
     *     empresas: array<int, array<string, mixed>>,
     *     tiposGlobales: array<int, array<string, mixed>>,
     *     tiposPersonalizados: array<int, array<string, mixed>>,
     *     statusOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     savedDocument: ?array<string, mixed>
     * }
     */
    function documentoUploadDefaults(array $query = []): array
    {
        $formData = documentoUploadFormDefaults();

        $empresaQuery = documentoNormalizePositiveInt($query['empresa'] ?? null);
        if ($empresaQuery !== null) {
            $formData['empresa_id'] = (string) $empresaQuery;
        }

        $tipoQuery = documentoNormalizePositiveInt($query['tipo'] ?? null);
        if ($tipoQuery !== null) {
            $formData['tipo_global_id'] = (string) $tipoQuery;
        }

        $personalizadoQuery = documentoNormalizePositiveInt($query['personalizado'] ?? null);
        if ($personalizadoQuery !== null) {
            $formData['tipo_personalizado_id'] = (string) $personalizadoQuery;
            $formData['tipo_origen'] = 'personalizado';
        }

        $origenQuery = documentoUploadNormalizeOrigen($query['origen'] ?? null);
        if ($origenQuery !== null) {
            $formData['tipo_origen'] = $origenQuery;
        }

        $estatusQuery = documentoNormalizeStatus($query['estatus'] ?? null);
        if ($estatusQuery !== null) {
            $formData['estatus'] = $estatusQuery;
        }

        return [
            'formData' => $formData,
            'empresas' => [],
            'tiposGlobales' => [],
            'tiposPersonalizados' => [],
            'statusOptions' => documentoStatusOptions(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
            'savedDocument' => null,
        ];
    }
}

if (!function_exists('documentoUploadFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function documentoUploadFormDefaults(): array
    {
        return [
            'empresa_id' => '',
            'tipo_origen' => 'global',
            'tipo_global_id' => '',
            'tipo_personalizado_id' => '',
            'estatus' => 'pendiente',
            'observacion' => '',
            'fecha_doc' => '',
        ];
    }
}

if (!function_exists('documentoUploadNormalizeOrigen')) {
    function documentoUploadNormalizeOrigen(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strtolower(trim((string) $value));

        return match ($value) {
            'global' => 'global',
            'personalizado', 'personal', 'custom' => 'personalizado',
            default => null,
        };
    }
}

if (!function_exists('documentoUploadIsPostRequest')) {
    function documentoUploadIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('documentoUploadSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function documentoUploadSanitizeInput(array $input): array
    {
        $defaults = documentoUploadFormDefaults();

        $sanitized = $defaults;
        $sanitized['empresa_id'] = isset($input['empresa_id']) ? trim((string) $input['empresa_id']) : '';
        $sanitized['tipo_origen'] = isset($input['tipo_origen']) ? trim((string) $input['tipo_origen']) : $defaults['tipo_origen'];
        $sanitized['tipo_global_id'] = isset($input['tipo_global_id']) ? trim((string) $input['tipo_global_id']) : '';
        $sanitized['tipo_personalizado_id'] = isset($input['tipo_personalizado_id']) ? trim((string) $input['tipo_personalizado_id']) : '';
        $sanitized['estatus'] = isset($input['estatus']) ? trim((string) $input['estatus']) : $defaults['estatus'];
        $sanitized['observacion'] = isset($input['observacion']) ? trim((string) $input['observacion']) : '';
        $sanitized['fecha_doc'] = isset($input['fecha_doc']) ? trim((string) $input['fecha_doc']) : '';

        return $sanitized;
    }
}

if (!function_exists('documentoUploadNormalizeFile')) {
    /**
     * @param mixed $file
     * @return array<string, mixed>|null
     */
    function documentoUploadNormalizeFile(mixed $file): ?array
    {
        if (!is_array($file)) {
            return null;
        }

        $name = isset($file['name']) ? (string) $file['name'] : '';
        $normalized = [
            'name' => $name,
            'type' => isset($file['type']) ? (string) $file['type'] : '',
            'tmp_name' => isset($file['tmp_name']) ? (string) $file['tmp_name'] : '',
            'error' => isset($file['error']) ? (int) $file['error'] : \UPLOAD_ERR_NO_FILE,
            'size' => isset($file['size']) ? (int) $file['size'] : 0,
        ];

        $normalized['extension'] = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));

        return $normalized;
    }
}

if (!function_exists('documentoUploadAllowedExtensions')) {
    /**
     * @return array<int, string>
     */
    function documentoUploadAllowedExtensions(): array
    {
        return ['pdf', 'jpg', 'jpeg', 'png'];
    }
}

if (!function_exists('documentoUploadAllowedMimeTypes')) {
    /**
     * @return array<int, string>
     */
    function documentoUploadAllowedMimeTypes(): array
    {
        return [
            'application/pdf',
            'image/jpeg',
            'image/png',
        ];
    }
}

if (!function_exists('documentoUploadMaxFileSize')) {
    function documentoUploadMaxFileSize(): int
    {
        return 10 * 1024 * 1024; // 10 MB
    }
}

if (!function_exists('documentoUploadValidateData')) {
    /**
     * @param array<string, string> $formData
     * @param callable $tipoGlobalExists function(int $tipoId): bool
     * @param callable $tipoPersonalizadoExists function(int $tipoId, int $empresaId): bool
     * @return array<int, string>
     */
    function documentoUploadValidateData(
        array &$formData,
        ?array $fileInfo,
        array $statusOptions,
        callable $empresaExists,
        callable $tipoGlobalExists,
        callable $tipoPersonalizadoExists
    ): array {
        $errors = [];

        $empresaId = documentoNormalizePositiveInt($formData['empresa_id'] ?? null);
        if ($empresaId === null) {
            $errors[] = 'Selecciona una empresa.';
        } elseif (!$empresaExists($empresaId)) {
            $errors[] = 'La empresa seleccionada no existe.';
        }

        $tipoOrigen = documentoUploadNormalizeOrigen($formData['tipo_origen'] ?? null) ?? 'global';
        $formData['tipo_origen'] = $tipoOrigen;

        if ($tipoOrigen === 'global') {
            $tipoGlobalId = documentoNormalizePositiveInt($formData['tipo_global_id'] ?? null);
            if ($tipoGlobalId === null) {
                $errors[] = 'Selecciona un tipo de documento global.';
            } elseif (!$tipoGlobalExists($tipoGlobalId)) {
                $errors[] = 'El tipo de documento global seleccionado no existe.';
            }
        } elseif ($tipoOrigen === 'personalizado') {
            $tipoPersonalizadoId = documentoNormalizePositiveInt($formData['tipo_personalizado_id'] ?? null);
            if ($tipoPersonalizadoId === null) {
                $errors[] = 'Selecciona un documento personalizado de la empresa.';
            } elseif ($empresaId === null) {
                $errors[] = 'Selecciona primero la empresa antes del documento personalizado.';
            } elseif (!$tipoPersonalizadoExists($tipoPersonalizadoId, $empresaId)) {
                $errors[] = 'El documento personalizado seleccionado no pertenece a la empresa indicada.';
            }
        } else {
            $errors[] = 'Selecciona un origen de documento válido.';
        }

        $estatus = documentoNormalizeStatus($formData['estatus'] ?? null);
        if ($estatus === null) {
            $errors[] = 'Selecciona un estatus válido para el documento.';
        } elseif (!array_key_exists($estatus, $statusOptions)) {
            $errors[] = 'El estatus proporcionado no es válido.';
        }

        $observacion = $formData['observacion'] ?? '';
        if ($observacion !== '') {
            $length = function_exists('mb_strlen') ? mb_strlen($observacion, 'UTF-8') : strlen($observacion);

            if ($length > 65535) {
                $errors[] = 'La observación supera el límite permitido (65,535 caracteres).';
            }
        }

        $fechaDoc = $formData['fecha_doc'] ?? '';
        if ($fechaDoc !== '') {
            try {
                $date = new \DateTimeImmutable($fechaDoc);
                $formData['fecha_doc'] = $date->format('Y-m-d');
            } catch (\Throwable) {
                $errors[] = 'La fecha del documento no tiene un formato válido (aaaa-mm-dd).';
            }
        }

        if ($fileInfo === null) {
            $errors[] = 'Adjunta un archivo para continuar.';
        } else {
            $fileError = (int) ($fileInfo['error'] ?? \UPLOAD_ERR_NO_FILE);

            if ($fileError !== \UPLOAD_ERR_OK) {
                $errors[] = documentoUploadFileErrorMessage($fileError);
            } else {
                $fileSize = (int) ($fileInfo['size'] ?? 0);
                if ($fileSize <= 0) {
                    $errors[] = 'El archivo seleccionado está vacío.';
                } elseif ($fileSize > documentoUploadMaxFileSize()) {
                    $errors[] = 'El archivo excede el tamaño máximo permitido (10 MB).';
                }

                $extension = isset($fileInfo['extension']) ? (string) $fileInfo['extension'] : '';
                if ($extension === '' || !in_array($extension, documentoUploadAllowedExtensions(), true)) {
                    $errors[] = 'Formato de archivo no permitido. Usa PDF, JPG o PNG.';
                }

                $tmpName = isset($fileInfo['tmp_name']) ? (string) $fileInfo['tmp_name'] : '';
                if ($tmpName !== '' && is_file($tmpName)) {
                    $detectedMime = null;
                    $finfo = function_exists('finfo_open') ? finfo_open(\FILEINFO_MIME_TYPE) : false;

                    if ($finfo !== false) {
                        $detectedMime = finfo_file($finfo, $tmpName) ?: null;
                        finfo_close($finfo);
                    }

                    if ($detectedMime !== null && !in_array($detectedMime, documentoUploadAllowedMimeTypes(), true)) {
                        $errors[] = 'El tipo de archivo no es válido. Usa archivos PDF o imagen (JPG/PNG).';
                    }
                }
            }
        }

        return $errors;
    }
}

if (!function_exists('documentoUploadFileErrorMessage')) {
    function documentoUploadFileErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            \UPLOAD_ERR_INI_SIZE, \UPLOAD_ERR_FORM_SIZE => 'El archivo seleccionado excede el tamaño permitido.',
            \UPLOAD_ERR_PARTIAL => 'El archivo se cargó de forma incompleta. Intenta nuevamente.',
            \UPLOAD_ERR_NO_FILE => 'Adjunta un archivo para continuar.',
            \UPLOAD_ERR_NO_TMP_DIR => 'No se encontró un directorio temporal para almacenar el archivo.',
            \UPLOAD_ERR_CANT_WRITE => 'No fue posible guardar el archivo en el servidor.',
            \UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga del archivo.',
            default => 'Ocurrió un error desconocido al cargar el archivo.',
        };
    }
}

if (!function_exists('documentoUploadSuccessMessage')) {
    function documentoUploadSuccessMessage(int $documentoId): string
    {
        return 'El documento se registró correctamente con el folio #' . $documentoId . '.';
    }
}

if (!function_exists('documentoUploadControllerErrorMessage')) {
    function documentoUploadControllerErrorMessage(\Throwable $exception): string
    {
        return 'No se pudo establecer conexión con la base de datos. Por favor, intenta nuevamente más tarde.';
    }
}

if (!function_exists('documentoUploadPersistenceErrorMessage')) {
    function documentoUploadPersistenceErrorMessage(\Throwable $exception): string
    {
        $message = $exception->getMessage();

        $message = is_string($message) ? trim($message) : '';

        return $message !== ''
            ? $message
            : 'No se pudo guardar el documento. Intenta nuevamente.';
    }
}
