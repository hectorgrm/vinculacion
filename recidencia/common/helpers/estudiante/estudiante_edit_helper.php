<?php

declare(strict_types=1);

require_once __DIR__ . '/estudiante_add_helper.php';

if (!function_exists('estudianteEditFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function estudianteEditFormDefaults(): array
    {
        return estudianteAddFormDefaults();
    }
}

if (!function_exists('estudianteEditStatusOptions')) {
    /**
     * @return array<int, string>
     */
    function estudianteEditStatusOptions(): array
    {
        return estudianteAddStatusOptions();
    }
}

if (!function_exists('estudianteEditDefaults')) {
    /**
     * @return array{
     *     estudianteId: ?int,
     *     formData: array<string, string>,
     *     empresas: array<int, array<string, string>>,
     *     convenios: array<int, array<string, string>>,
     *     errors: array<int, string>,
     *     success: bool,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string,
     *     estatusOptions: array<int, string>
     * }
     */
    function estudianteEditDefaults(): array
    {
        return [
            'estudianteId' => null,
            'formData' => estudianteEditFormDefaults(),
            'empresas' => [],
            'convenios' => [],
            'errors' => [],
            'success' => false,
            'successMessage' => null,
            'controllerError' => null,
            'loadError' => null,
            'estatusOptions' => estudianteEditStatusOptions(),
        ];
    }
}

if (!function_exists('estudianteEditResolveId')) {
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     * @return array{estudianteId: ?int, error: ?string}
     */
    function estudianteEditResolveId(array $query, array $post): array
    {
        $idParam = $query['id'] ?? ($post['estudiante_id'] ?? null);

        if ($idParam === null) {
            return [
                'estudianteId' => null,
                'error' => 'No se especificó el estudiante que deseas editar.',
            ];
        }

        if (!is_string($idParam) || preg_match('/^\d+$/', $idParam) !== 1) {
            return [
                'estudianteId' => null,
                'error' => 'El identificador del estudiante no es válido.',
            ];
        }

        $estudianteId = (int) $idParam;

        if ($estudianteId <= 0) {
            return [
                'estudianteId' => null,
                'error' => 'El identificador del estudiante no es válido.',
            ];
        }

        return [
            'estudianteId' => $estudianteId,
            'error' => null,
        ];
    }
}

if (!function_exists('estudianteEditIsPostRequest')) {
    function estudianteEditIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('estudianteEditSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function estudianteEditSanitizeInput(array $input): array
    {
        $data = estudianteAddSanitizeInput($input);

        return $data;
    }
}

if (!function_exists('estudianteEditValidateData')) {
    /**
     * @param array<string, string> $data
     * @param array<int, array<string, string>> $empresas
     * @param array<int, array<string, string>> $convenios
     * @return array<int, string>
     */
    function estudianteEditValidateData(array $data, array $empresas, array $convenios): array
    {
        return estudianteAddValidateData($data, $empresas, $convenios);
    }
}

if (!function_exists('estudianteEditHydrateForm')) {
    /**
     * @param array<string, string> $defaults
     * @param array<string, mixed> $record
     * @return array<string, string>
     */
    function estudianteEditHydrateForm(array $defaults, array $record): array
    {
        $form = $defaults;

        $keys = [
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'matricula',
            'carrera',
            'correo_institucional',
            'telefono',
        ];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $record)) {
                continue;
            }

            $value = $record[$key];
            $form[$key] = ($value === null) ? '' : trim((string) $value);
        }

        if (array_key_exists('empresa_id', $record) && $record['empresa_id'] !== null) {
            $form['empresa_id'] = (string) $record['empresa_id'];
        }

        if (array_key_exists('convenio_id', $record) && $record['convenio_id'] !== null) {
            $form['convenio_id'] = (string) $record['convenio_id'];
        }

        if (array_key_exists('estatus', $record) && is_string($record['estatus'])) {
            $estatus = trim($record['estatus']);
            $form['estatus'] = in_array($estatus, estudianteEditStatusOptions(), true)
                ? $estatus
                : 'Activo';
        }

        return $form;
    }
}

if (!function_exists('estudianteEditPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array<string, mixed>
     */
    function estudianteEditPrepareForPersistence(array $data): array
    {
        return estudianteAddPrepareForPersistence($data);
    }
}

if (!function_exists('estudianteEditSuccessMessage')) {
    function estudianteEditSuccessMessage(): string
    {
        return 'La información del estudiante se actualizó correctamente.';
    }
}

if (!function_exists('estudianteEditControllerErrorMessage')) {
    function estudianteEditControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo establecer comunicación con la base de datos. Intenta nuevamente más tarde.';
    }
}

if (!function_exists('estudianteEditPersistenceErrorMessage')) {
    function estudianteEditPersistenceErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'Ocurrió un error al actualizar la información del estudiante. Intenta nuevamente.';
    }
}

if (!function_exists('estudianteEditRecordNotFoundMessage')) {
    function estudianteEditRecordNotFoundMessage(int $estudianteId): string
    {
        return 'El estudiante solicitado con el folio #' . $estudianteId . ' no existe.';
    }
}

if (!function_exists('estudianteEditDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function estudianteEditDuplicateErrors(\PDOException $exception): array
    {
        $errorInfo = $exception->errorInfo;

        if (!is_array($errorInfo) || !isset($errorInfo[1]) || (int) $errorInfo[1] !== 1062) {
            return [];
        }

        $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? $errorInfo[2] : '';

        if ($detail !== '' && stripos($detail, 'matricula') !== false) {
            return ['Ya existe otro estudiante registrado con la matrícula proporcionada.'];
        }

        return ['Ya existe un registro duplicado para el estudiante.'];
    }
}

