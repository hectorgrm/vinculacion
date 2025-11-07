<?php

declare(strict_types=1);


if (!function_exists('estudianteAddFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function estudianteAddFormDefaults(): array
    {
        return [
            'nombre' => '',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'matricula' => '',
            'carrera' => '',
            'correo_institucional' => '',
            'telefono' => '',
            'empresa_id' => '',
            'convenio_id' => '',
            'estatus' => 'Activo',
        ];
    }
}

if (!function_exists('estudianteAddStatusOptions')) {
    /**
     * @return array<int, string>
     */
    function estudianteAddStatusOptions(): array
    {
        return ['Activo', 'Finalizado', 'Inactivo'];
    }
}

if (!function_exists('estudianteAddDefaults')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     empresas: array<int, array<string, string>>,
     *     convenios: array<int, array<string, string>>,
     *     errors: array<int, string>,
     *     success: bool,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     estatusOptions: array<int, string>
     * }
     */
    function estudianteAddDefaults(): array
    {
        return [
            'formData' => estudianteAddFormDefaults(),
            'empresas' => [],
            'convenios' => [],
            'errors' => [],
            'success' => false,
            'successMessage' => null,
            'controllerError' => null,
            'estatusOptions' => estudianteAddStatusOptions(),
        ];
    }
}

if (!function_exists('estudianteAddIsPostRequest')) {
    function estudianteAddIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('estudianteAddSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function estudianteAddSanitizeInput(array $input): array
    {
        $data = estudianteAddFormDefaults();

        foreach ($data as $key => $default) {
            if (!array_key_exists($key, $input)) {
                $data[$key] = $default;
                continue;
            }

            $value = $input[$key];

            if (!is_array($value)) {
                $value = trim((string) $value);
            } else {
                $value = $default;
            }

            $data[$key] = $value;
        }

        if ($data['estatus'] === '') {
            $data['estatus'] = 'Activo';
        }

        $data['nombre'] = mb_substr($data['nombre'], 0, 150);
        $data['apellido_paterno'] = mb_substr($data['apellido_paterno'], 0, 80);
        $data['apellido_materno'] = mb_substr($data['apellido_materno'], 0, 80);
        $data['matricula'] = mb_substr($data['matricula'], 0, 20);
        $data['carrera'] = mb_substr($data['carrera'], 0, 100);
        $data['correo_institucional'] = mb_substr($data['correo_institucional'], 0, 120);
        $data['telefono'] = mb_substr($data['telefono'], 0, 20);

        if ($data['empresa_id'] !== '' && !ctype_digit($data['empresa_id'])) {
            $data['empresa_id'] = '';
        }

        if ($data['convenio_id'] !== '' && !ctype_digit($data['convenio_id'])) {
            $data['convenio_id'] = '';
        }

        return $data;
    }
}

if (!function_exists('estudianteAddValidateData')) {
    /**
     * @param array<string, string> $data
     * @param array<int, array<string, string>> $empresas
     * @param array<int, array<string, string>> $convenios
     * @return array<int, string>
     */
    function estudianteAddValidateData(array $data, array $empresas, array $convenios): array
    {
        $errors = [];

        if ($data['nombre'] === '') {
            $errors[] = 'El nombre del estudiante es obligatorio.';
        } elseif (mb_strlen($data['nombre']) > 150) {
            $errors[] = 'El nombre del estudiante no debe exceder 150 caracteres.';
        }

        if ($data['matricula'] === '') {
            $errors[] = 'La matrícula es obligatoria.';
        } elseif (mb_strlen($data['matricula']) > 20) {
            $errors[] = 'La matrícula no debe exceder 20 caracteres.';
        }

        if ($data['apellido_paterno'] !== '' && mb_strlen($data['apellido_paterno']) > 80) {
            $errors[] = 'El apellido paterno no debe exceder 80 caracteres.';
        }

        if ($data['apellido_materno'] !== '' && mb_strlen($data['apellido_materno']) > 80) {
            $errors[] = 'El apellido materno no debe exceder 80 caracteres.';
        }

        if ($data['carrera'] !== '' && mb_strlen($data['carrera']) > 100) {
            $errors[] = 'La carrera no debe exceder 100 caracteres.';
        }

        if ($data['correo_institucional'] !== '') {
            if (mb_strlen($data['correo_institucional']) > 120) {
                $errors[] = 'El correo institucional no debe exceder 120 caracteres.';
            } elseif (filter_var($data['correo_institucional'], FILTER_VALIDATE_EMAIL) === false) {
                $errors[] = 'Proporciona un correo institucional válido.';
            }
        }

        if ($data['telefono'] !== '' && mb_strlen($data['telefono']) > 20) {
            $errors[] = 'El teléfono no debe exceder 20 caracteres.';
        }

        $empresaMap = [];

        foreach ($empresas as $empresa) {
            if (isset($empresa['id'])) {
                $empresaMap[$empresa['id']] = $empresa;
            }
        }

        if ($data['empresa_id'] === '') {
            $errors[] = 'Selecciona la empresa a la que se vinculará el estudiante.';
        } elseif (!array_key_exists($data['empresa_id'], $empresaMap)) {
            $errors[] = 'La empresa seleccionada no es válida.';
        }

        $convenioMap = [];

        foreach ($convenios as $convenio) {
            if (isset($convenio['id'])) {
                $convenioMap[$convenio['id']] = $convenio;
            }
        }

        if ($data['convenio_id'] !== '') {
            if (!array_key_exists($data['convenio_id'], $convenioMap)) {
                $errors[] = 'El convenio seleccionado no es válido.';
            } elseif (
                $data['empresa_id'] !== ''
                && isset($convenioMap[$data['convenio_id']]['empresa_id'])
                && $convenioMap[$data['convenio_id']]['empresa_id'] !== $data['empresa_id']
            ) {
                $errors[] = 'El convenio seleccionado no pertenece a la empresa elegida.';
            }
        }

        if ($data['estatus'] === '' || !in_array($data['estatus'], estudianteAddStatusOptions(), true)) {
            $errors[] = 'Selecciona un estatus válido para el estudiante.';
        }

        return $errors;
    }
}

if (!function_exists('estudianteAddPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array<string, mixed>
     */
    function estudianteAddPrepareForPersistence(array $data): array
    {
        return [
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'] !== '' ? $data['apellido_paterno'] : null,
            'apellido_materno' => $data['apellido_materno'] !== '' ? $data['apellido_materno'] : null,
            'matricula' => $data['matricula'],
            'carrera' => $data['carrera'] !== '' ? $data['carrera'] : null,
            'correo_institucional' => $data['correo_institucional'] !== '' ? $data['correo_institucional'] : null,
            'telefono' => $data['telefono'] !== '' ? $data['telefono'] : null,
            'empresa_id' => (int) $data['empresa_id'],
            'convenio_id' => $data['convenio_id'] !== '' ? (int) $data['convenio_id'] : null,
            'estatus' => $data['estatus'],
        ];
    }
}

if (!function_exists('estudianteAddSuccessMessage')) {
    function estudianteAddSuccessMessage(int $estudianteId): string
    {
        return 'El estudiante se registró correctamente con el folio #' . $estudianteId . '.';
    }
}

if (!function_exists('estudianteAddControllerErrorMessage')) {
    function estudianteAddControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo establecer comunicación con la base de datos. Intenta nuevamente más tarde.';
    }
}

if (!function_exists('estudianteAddPersistenceErrorMessage')) {
    function estudianteAddPersistenceErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'Ocurrió un error al guardar el estudiante. Intenta nuevamente.';
    }
}

if (!function_exists('estudianteAddDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function estudianteAddDuplicateErrors(\PDOException $exception): array
    {
        $errorInfo = $exception->errorInfo;

        if (!is_array($errorInfo) || !isset($errorInfo[1]) || (int) $errorInfo[1] !== 1062) {
            return [];
        }

        $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? $errorInfo[2] : '';

        if ($detail !== '' && stripos($detail, 'matricula') !== false) {
            return ['Ya existe un estudiante registrado con la matrícula proporcionada.'];
        }

        return ['Ya existe un registro duplicado para el estudiante.'];
    }
}
