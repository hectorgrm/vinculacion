<?php

declare(strict_types=1);

if (!function_exists('convenioStatusOptions')) {
    /**
     * @return array<int, string>
     */
    function convenioStatusOptions(): array
    {
        return ['Activa', 'En revisión', 'Inactiva', 'Suspendida'];
    }
}

if (!function_exists('convenioUploadsAbsoluteDir')) {
    function convenioUploadsAbsoluteDir(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'convenios';
    }
}

if (!function_exists('convenioUploadsRelativeDir')) {
    function convenioUploadsRelativeDir(): string
    {
        return 'uploads/convenios';
    }
}

if (!function_exists('convenioResolveControllerData')) {
    /**
     * @return array{
     *     controller: ?\Residencia\Controller\ConvenioController,
     *     empresaOptions: array<int, array<string, mixed>>,
     *     error: ?string
     * }
     */
    function convenioResolveControllerData(): array
    {
        if (!class_exists(\Residencia\Controller\ConvenioController::class)) {
            $controllerPath = __DIR__ . '/../../controller/ConvenioController.php';

            if (is_file($controllerPath)) {
                require_once $controllerPath;
            }
        }

        try {
            $controller = new \Residencia\Controller\ConvenioController();
            $empresaOptions = $controller->getEmpresasForSelect();

            return [
                'controller' => $controller,
                'empresaOptions' => $empresaOptions,
                'error' => null,
            ];
        } catch (\Throwable) {
            return [
                'controller' => null,
                'empresaOptions' => [],
                'error' => 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.',
            ];
        }
    }
}

if (!function_exists('convenioHandleAddRequest')) {
    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $files
     * @return array{
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string
     * }
     */
    function convenioHandleAddRequest(
        \Residencia\Controller\ConvenioController $controller,
        array $request,
        array $files,
        string $uploadDirAbsolute,
        string $uploadDirRelative = 'uploads/convenios'
    ): array {
        $formData = convenioSanitizeInput($request);
        $errors = convenioValidateData($formData);
        $successMessage = null;

        $uploadRelativePath = null;
        $uploadAbsolutePath = null;

        if ($errors === [] && array_key_exists('borrador_path', $files)) {
            $fileData = $files['borrador_path'];

            if (is_array($fileData)) {
                $uploadResult = convenioProcessFileUpload($fileData, $uploadDirAbsolute, $uploadDirRelative);

                if ($uploadResult['error'] !== null) {
                    $errors[] = $uploadResult['error'];
                } else {
                    $uploadRelativePath = $uploadResult['path'];

                    if ($uploadRelativePath !== null) {
                        $uploadAbsolutePath = rtrim($uploadDirAbsolute, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($uploadRelativePath);
                    }
                }
            } else {
                $errors[] = 'El archivo del convenio no se recibió correctamente.';
            }
        }

        if ($errors === []) {
            $payload = convenioPrepareForPersistence($formData, $uploadRelativePath);

            try {
                $convenioId = $controller->createConvenio($payload);
                $successMessage = 'El convenio se registró correctamente con el número #' . $convenioId . '.';
                $formData = convenioFormDefaults();
            } catch (\PDOException $pdoException) {
                if ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
                    @unlink($uploadAbsolutePath);
                }

                $errorInfo = $pdoException->errorInfo;

                if (is_array($errorInfo) && isset($errorInfo[1]) && (int) $errorInfo[1] === 1062) {
                    $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? $errorInfo[2] : '';

                    if ($detail !== '' && stripos($detail, 'folio') !== false) {
                        $errors[] = 'Ya existe un convenio registrado con el folio proporcionado.';
                    } else {
                        $errors[] = 'Los datos proporcionados ya están registrados en otro convenio.';
                    }
                } else {
                    $errors[] = 'Ocurrió un error al registrar el convenio. Intenta nuevamente.';
                }
            } catch (\Throwable) {
                if ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
                    @unlink($uploadAbsolutePath);
                }

                $errors[] = 'Ocurrió un error al registrar el convenio. Intenta nuevamente.';
            }
        } elseif ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
            @unlink($uploadAbsolutePath);
        }

        return [
            'formData' => $formData,
            'errors' => $errors,
            'successMessage' => $successMessage,
        ];
    }
}


if (!function_exists('convenioNormalizeStatus')) {
    function convenioNormalizeStatus(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        if ($estatus === '') {
            return 'En revisión';
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);

        foreach (convenioStatusOptions() as $option) {
            $optionNormalized = function_exists('mb_strtolower')
                ? mb_strtolower($option, 'UTF-8')
                : strtolower($option);

            if ($normalized === $optionNormalized) {
                return $option;
            }
        }

        return 'En revisión';
    }
}

if (!function_exists('convenioRenderBadgeClass')) {
    function convenioRenderBadgeClass(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        if ($estatus !== '' && function_exists('mb_strtolower')) {
            $estatus = mb_strtolower($estatus, 'UTF-8');
        } else {
            $estatus = strtolower($estatus);
        }

        return match ($estatus) {
            'activa' => 'badge ok',
            'en revisión', 'en revision' => 'badge secondary',
            'inactiva' => 'badge warn',
            'suspendida' => 'badge err',
            default => 'badge secondary',
        };
    }
}

if (!function_exists('convenioRenderBadgeLabel')) {
    function convenioRenderBadgeLabel(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        return $estatus !== '' ? $estatus : 'Sin especificar';
    }
}

if (!function_exists('convenioValueOrDefault')) {
    function convenioValueOrDefault(mixed $value, string $fallback = 'N/A'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $value = trim($value);

            return $value !== '' ? $value : $fallback;
        }

        if (is_scalar($value)) {
            $stringValue = (string) $value;

            return $stringValue !== '' ? $stringValue : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('convenioFormatDate')) {
    function convenioFormatDate(?string $value, string $fallback = 'N/A'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00') {
            return $fallback;
        }

        try {
            $date = new \DateTimeImmutable($value);
        } catch (\Throwable) {
            return $fallback;
        }

        return $date->format('d/m/Y');
    }
}

if (!function_exists('convenioFormatDateTime')) {
    function convenioFormatDateTime(?string $value, string $fallback = 'N/A'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00 00:00:00') {
            return $fallback;
        }

        try {
            $date = new \DateTimeImmutable($value);
        } catch (\Throwable) {
            return $fallback;
        }

        return $date->format('d/m/Y H:i');
    }
}

if (!function_exists('convenioFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function convenioFormDefaults(): array
    {
        return [
            'empresa_id' => '',
            'folio' => '',
            'estatus' => 'En revisión',
            'machote_version' => '',
            'version_actual' => '',
            'fecha_inicio' => '',
            'fecha_fin' => '',
            'observaciones' => '',
        ];
    }
}

if (!function_exists('convenioFormValue')) {
    /**
     * @param array<string, string> $formData
     */
    function convenioFormValue(array $formData, string $field): string
    {
        return isset($formData[$field]) ? (string) $formData[$field] : '';
    }
}

if (!function_exists('convenioSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function convenioSanitizeInput(array $input): array
    {
        $data = convenioFormDefaults();

        foreach ($data as $field => $_) {
            if ($field === 'estatus') {
                $data[$field] = convenioNormalizeStatus(
                    isset($input[$field]) ? (string) $input[$field] : null
                );

                continue;
            }

            if (!array_key_exists($field, $input)) {
                $data[$field] = '';
                continue;
            }

            $value = $input[$field];

            if (is_string($value)) {
                $data[$field] = trim($value);
            } elseif (is_scalar($value)) {
                $data[$field] = trim((string) $value);
            } else {
                $data[$field] = '';
            }
        }

        if ($data['empresa_id'] !== '') {
            $empresa = preg_replace('/[^0-9]/', '', $data['empresa_id']);
            $data['empresa_id'] = $empresa !== null ? $empresa : '';
        }

        return $data;
    }
}

if (!function_exists('convenioValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function convenioValidateData(array $data): array
    {
        $errors = [];

        if ($data['empresa_id'] === '') {
            $errors[] = 'Selecciona la empresa con la que se firma el convenio.';
        } elseif (!ctype_digit($data['empresa_id']) || (int) $data['empresa_id'] <= 0) {
            $errors[] = 'La empresa seleccionada no es válida.';
        }

        if (!in_array($data['estatus'], convenioStatusOptions(), true)) {
            $errors[] = 'Selecciona un estatus válido para el convenio.';
        }

        if ($data['folio'] !== '' && mb_strlen($data['folio']) > 32) {
            $errors[] = 'El folio del convenio no puede exceder 32 caracteres.';
        }

        if ($data['machote_version'] !== '' && mb_strlen($data['machote_version']) > 50) {
            $errors[] = 'La versión del machote no puede exceder 50 caracteres.';
        }

        if ($data['version_actual'] !== '' && mb_strlen($data['version_actual']) > 100) {
            $errors[] = 'La versión actual no puede exceder 100 caracteres.';
        }

        $fechaInicio = null;
        if ($data['fecha_inicio'] !== '') {
            $fechaInicio = \DateTimeImmutable::createFromFormat('Y-m-d', $data['fecha_inicio']);

            if (!$fechaInicio || $fechaInicio->format('Y-m-d') !== $data['fecha_inicio']) {
                $errors[] = 'La fecha de inicio no tiene un formato válido (YYYY-MM-DD).';
                $fechaInicio = null;
            }
        }

        $fechaFin = null;
        if ($data['fecha_fin'] !== '') {
            $fechaFin = \DateTimeImmutable::createFromFormat('Y-m-d', $data['fecha_fin']);

            if (!$fechaFin || $fechaFin->format('Y-m-d') !== $data['fecha_fin']) {
                $errors[] = 'La fecha de término no tiene un formato válido (YYYY-MM-DD).';
                $fechaFin = null;
            }
        }

        if ($fechaInicio !== null && $fechaFin !== null && $fechaFin < $fechaInicio) {
            $errors[] = 'La fecha de término debe ser igual o posterior a la fecha de inicio.';
        }

        return $errors;
    }
}

if (!function_exists('convenioPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array<string, mixed>
     */
    function convenioPrepareForPersistence(array $data, ?string $borradorPath = null): array
    {
        return [
            'empresa_id' => (int) $data['empresa_id'],
            'folio' => $data['folio'] !== '' ? $data['folio'] : null,
            'estatus' => convenioNormalizeStatus($data['estatus']),
            'machote_version' => $data['machote_version'] !== '' ? $data['machote_version'] : null,
            'version_actual' => $data['version_actual'] !== '' ? $data['version_actual'] : null,
            'fecha_inicio' => $data['fecha_inicio'] !== '' ? $data['fecha_inicio'] : null,
            'fecha_fin' => $data['fecha_fin'] !== '' ? $data['fecha_fin'] : null,
            'observaciones' => $data['observaciones'] !== '' ? $data['observaciones'] : null,
            'borrador_path' => $borradorPath,
            'firmado_path' => null,
        ];
    }
}

if (!function_exists('convenioProcessFileUpload')) {
    /**
     * @param array<string, mixed> $file
     * @return array{path: ?string, error: ?string}
     */
    function convenioProcessFileUpload(array $file, string $destinationDir, string $relativeDir = 'convenios', string $prefix = 'convenio'): array
    {
        if (!isset($file['error']) || !is_int($file['error'])) {
            return ['path' => null, 'error' => 'El archivo del convenio no se recibió correctamente.'];
        }

        if ($file['error'] === \UPLOAD_ERR_NO_FILE) {
            return ['path' => null, 'error' => null];
        }

        if ($file['error'] !== \UPLOAD_ERR_OK) {
            $message = match ($file['error']) {
                \UPLOAD_ERR_INI_SIZE, \UPLOAD_ERR_FORM_SIZE => 'El archivo del convenio excede el tamaño permitido (10 MB).',
                \UPLOAD_ERR_PARTIAL => 'La carga del archivo del convenio fue incompleta.',
                \UPLOAD_ERR_NO_TMP_DIR => 'No se encontró el directorio temporal para subir el archivo.',
                \UPLOAD_ERR_CANT_WRITE => 'No se pudo guardar el archivo del convenio en el servidor.',
                \UPLOAD_ERR_EXTENSION => 'La carga del archivo fue detenida por una extensión del servidor.',
                default => 'No se pudo procesar el archivo del convenio.',
            };

            return ['path' => null, 'error' => $message];
        }

        if (!isset($file['tmp_name']) || !is_string($file['tmp_name']) || $file['tmp_name'] === '') {
            return ['path' => null, 'error' => 'No se recibió el archivo temporal del convenio.'];
        }

        $size = isset($file['size']) ? (int) $file['size'] : 0;
        if ($size > 10 * 1024 * 1024) {
            return ['path' => null, 'error' => 'El archivo del convenio debe ser menor a 10 MB.'];
        }

        $originalName = isset($file['name']) && is_string($file['name']) ? $file['name'] : 'convenio.pdf';
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));

        if ($extension !== 'pdf') {
            return ['path' => null, 'error' => 'El archivo del convenio debe estar en formato PDF.'];
        }

        $mimeType = null;
        if (function_exists('finfo_open') && is_file($file['tmp_name'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mimeType = finfo_file($finfo, $file['tmp_name']) ?: null;
                finfo_close($finfo);
            }
        }

        if ($mimeType !== null && $mimeType !== 'application/pdf') {
            return ['path' => null, 'error' => 'El archivo del convenio debe ser un PDF válido.'];
        }

        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0775, true) && !is_dir($destinationDir)) {
                return ['path' => null, 'error' => 'No se pudo preparar la carpeta para guardar el convenio.'];
            }
        }

        try {
            $random = bin2hex(random_bytes(8));
        } catch (\Throwable) {
            $random = (string) mt_rand(10000000, 99999999);
        }

        $filename = sprintf('%s_%s_%s.pdf', $prefix, date('Ymd_His'), $random);
        $destinationPath = rtrim($destinationDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
            return ['path' => null, 'error' => 'No se pudo guardar el archivo del convenio en el servidor.'];
        }

        $relativeDir = trim($relativeDir, '/\\');
        $storedPath = $relativeDir !== '' ? $relativeDir . '/' . $filename : $filename;

        return ['path' => $storedPath, 'error' => null];
    }
}
