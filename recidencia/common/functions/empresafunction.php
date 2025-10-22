<?php

declare(strict_types=1);

if (!function_exists('renderBadgeClass')) {
    function renderBadgeClass(?string $estatus): string
    {
        $value = trim((string) $estatus);

        if ($value !== '' && function_exists('mb_strtolower')) {
            $value = mb_strtolower($value, 'UTF-8');
        } else {
            $value = strtolower($value);
        }

        switch ($value) {
            case 'activa':
                return 'badge ok';
            case 'en revisión':
            case 'en revision':
                return 'badge secondary';
            case 'inactiva':
                return 'badge warn';
            case 'suspendida':
                return 'badge err';
            default:
                return 'badge secondary';
        }
    }
}

if (!function_exists('renderBadgeLabel')) {
    function renderBadgeLabel(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        return $estatus !== '' ? $estatus : 'Sin especificar';
    }
}

if (!function_exists('empresaStatusOptions')) {
    /**
     * @return array<int, string>
     */
    function empresaStatusOptions(): array
    {
        return ['Activa', 'En revisión', 'Inactiva', 'Suspendida'];
    }
}

if (!function_exists('empresaNormalizeStatus')) {
    function empresaNormalizeStatus(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        if ($estatus === '') {
            return 'En revisión';
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);

        foreach (empresaStatusOptions() as $option) {
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

if (!function_exists('empresaFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function empresaFormDefaults(): array
    {
        return [
            'nombre' => '',
            'rfc' => '',
            'representante' => '',
            'cargo_representante' => '',
            'sector' => '',
            'sitio_web' => '',
            'contacto_nombre' => '',
            'contacto_email' => '',
            'telefono' => '',
            'estado' => '',
            'municipio' => '',
            'cp' => '',
            'direccion' => '',
            'estatus' => 'En revisión',
            'regimen_fiscal' => '',
            'notas' => '',
        ];
    }
}

if (!function_exists('empresaHydrateForm')) {
    /**
     * @param array<string, string> $defaults
     * @param array<string, mixed> $record
     * @return array<string, string>
     */
    function empresaHydrateForm(array $defaults, array $record): array
    {
        foreach ($defaults as $field => $value) {
            if (!array_key_exists($field, $record)) {
                continue;
            }

            if ($record[$field] === null) {
                $defaults[$field] = '';
                continue;
            }

            $defaults[$field] = (string) $record[$field];
        }

        $defaults['estatus'] = empresaNormalizeStatus($defaults['estatus']);

        return $defaults;
    }
}

if (!function_exists('empresaSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function empresaSanitizeInput(array $input): array
    {
        $data = empresaFormDefaults();

        foreach (array_keys($data) as $field) {
            $value = $input[$field] ?? '';

            if (!is_string($value)) {
                $value = '';
            }

            if ($field === 'notas') {
                $data[$field] = trim($value) === '' ? '' : $value;
                continue;
            }

            $value = trim($value);

            if ($field === 'rfc' && $value !== '') {
                $value = function_exists('mb_strtoupper')
                    ? mb_strtoupper($value, 'UTF-8')
                    : strtoupper($value);
            }

            $data[$field] = $value;
        }

        $data['estatus'] = empresaNormalizeStatus($data['estatus']);

        return $data;
    }
}

if (!function_exists('empresaStringLength')) {
    function empresaStringLength(string $value): int
    {
        return function_exists('mb_strlen')
            ? mb_strlen($value, 'UTF-8')
            : strlen($value);
    }
}

if (!function_exists('empresaValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function empresaValidateData(array $data): array
    {
        $errors = [];

        if ($data['nombre'] === '') {
            $errors[] = 'El nombre de la empresa es obligatorio.';
        } elseif (empresaStringLength($data['nombre']) > 191) {
            $errors[] = 'El nombre de la empresa no puede exceder 191 caracteres.';
        }

        if ($data['rfc'] !== '' && empresaStringLength($data['rfc']) > 20) {
            $errors[] = 'El RFC no puede exceder 20 caracteres.';
        }

        if ($data['contacto_nombre'] !== '' && empresaStringLength($data['contacto_nombre']) > 191) {
            $errors[] = 'El nombre del contacto no puede exceder 191 caracteres.';
        }

        if ($data['representante'] !== '' && empresaStringLength($data['representante']) > 191) {
            $errors[] = 'El representante legal no puede exceder 191 caracteres.';
        }

        if ($data['cargo_representante'] !== '' && empresaStringLength($data['cargo_representante']) > 191) {
            $errors[] = 'El cargo del representante no puede exceder 191 caracteres.';
        }

        if ($data['sector'] !== '' && empresaStringLength($data['sector']) > 191) {
            $errors[] = 'El sector no puede exceder 191 caracteres.';
        }

        if ($data['sitio_web'] !== '') {
            if (!filter_var($data['sitio_web'], FILTER_VALIDATE_URL)) {
                $errors[] = 'Ingresa un sitio web válido.';
            } elseif (empresaStringLength($data['sitio_web']) > 255) {
                $errors[] = 'El sitio web no puede exceder 255 caracteres.';
            }
        }

        if ($data['contacto_email'] !== '') {
            if (!filter_var($data['contacto_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Ingresa un correo electrónico de contacto válido.';
            } elseif (empresaStringLength($data['contacto_email']) > 191) {
                $errors[] = 'El correo electrónico de contacto no puede exceder 191 caracteres.';
            }
        }

        if ($data['telefono'] !== '' && empresaStringLength($data['telefono']) > 30) {
            $errors[] = 'El teléfono no puede exceder 30 caracteres.';
        }

        if ($data['estado'] !== '' && empresaStringLength($data['estado']) > 100) {
            $errors[] = 'El estado no puede exceder 100 caracteres.';
        }

        if ($data['municipio'] !== '' && empresaStringLength($data['municipio']) > 100) {
            $errors[] = 'El municipio no puede exceder 100 caracteres.';
        }

        if ($data['cp'] !== '' && empresaStringLength($data['cp']) > 10) {
            $errors[] = 'El código postal no puede exceder 10 caracteres.';
        }

        if ($data['direccion'] !== '' && empresaStringLength($data['direccion']) > 255) {
            $errors[] = 'La dirección no puede exceder 255 caracteres.';
        }

        if ($data['regimen_fiscal'] !== '' && empresaStringLength($data['regimen_fiscal']) > 191) {
            $errors[] = 'El régimen fiscal no puede exceder 191 caracteres.';
        }

        if ($data['notas'] !== '' && empresaStringLength($data['notas']) > 65535) {
            $errors[] = 'Las notas no pueden exceder 65535 caracteres.';
        }

        if (!in_array($data['estatus'], empresaStatusOptions(), true)) {
            $errors[] = 'Selecciona un estatus válido para la empresa.';
        }

        return $errors;
    }
}

if (!function_exists('empresaPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array<string, mixed>
     */
    function empresaPrepareForPersistence(array $data): array
    {
        $prepared = [];

        foreach ($data as $field => $value) {
            if ($field === 'estatus') {
                $prepared[$field] = empresaNormalizeStatus($value);
                continue;
            }

            if ($field === 'notas') {
                $prepared[$field] = trim($value) === '' ? null : $value;
                continue;
            }

            $trimmed = trim($value);
            $prepared[$field] = $trimmed === '' ? null : $trimmed;
        }

        return $prepared;
    }
}

if (!function_exists('empresaFormValue')) {
    /**
     * @param array<string, string> $data
     */
    function empresaFormValue(array $data, string $field): string
    {
        return isset($data[$field]) ? (string) $data[$field] : '';
    }
}
