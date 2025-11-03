<?php

declare(strict_types=1);

if (!function_exists('portalAccessFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function portalAccessFormDefaults(): array
    {
        return [
            'empresa_id' => '',
            'token' => '',
            'nip' => '',
            'activo' => '1',
            'expiracion' => '',
        ];
    }
}

if (!function_exists('portalAccessDefaults')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     empresaOptions: array<int, array<string, string>>, 
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string
     * }
     */
    function portalAccessDefaults(): array
    {
        return [
            'formData' => portalAccessFormDefaults(),
            'empresaOptions' => [],
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
        ];
    }
}

if (!function_exists('portalAccessIsPostRequest')) {
    function portalAccessIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('portalAccessNormalizeActivo')) {
    function portalAccessNormalizeActivo(?string $value): string
    {
        return $value === '0' ? '0' : '1';
    }
}

if (!function_exists('portalAccessSanitizeExpiration')) {
    function portalAccessSanitizeExpiration(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i', $value);

        if ($dateTime instanceof \DateTime) {
            return $dateTime->format('Y-m-d\TH:i');
        }

        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $value);

        if ($dateTime instanceof \DateTime) {
            return $dateTime->format('Y-m-d\TH:i');
        }

        return '';
    }
}

if (!function_exists('portalAccessSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function portalAccessSanitizeInput(array $input): array
    {
        $data = portalAccessFormDefaults();

        foreach (array_keys($data) as $field) {
            $value = $input[$field] ?? '';

            if (!is_string($value)) {
                $value = '';
            }

            $value = trim($value);

            switch ($field) {
                case 'empresa_id':
                    $data[$field] = $value !== '' && ctype_digit($value) ? $value : '';
                    break;
                case 'token':
                    $data[$field] = $value;
                    break;
                case 'nip':
                    $data[$field] = $value;
                    break;
                case 'activo':
                    $data[$field] = portalAccessNormalizeActivo($value);
                    break;
                case 'expiracion':
                    $data[$field] = portalAccessSanitizeExpiration($value);
                    break;
            }
        }

        return $data;
    }
}

if (!function_exists('portalAccessIsValidUuid')) {
    function portalAccessIsValidUuid(string $token): bool
    {
        return $token !== '' && preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $token) === 1;
    }
}

if (!function_exists('portalAccessValidateData')) {
    /**
     * @param array<string, string> $data
     * @param array<int, array<string, string>> $empresaOptions
     * @return array<int, string>
     */
    function portalAccessValidateData(array $data, array $empresaOptions): array
    {
        $errors = [];

        $empresaIds = [];

        foreach ($empresaOptions as $empresa) {
            if (!isset($empresa['id'])) {
                continue;
            }

            $empresaIds[] = (string) $empresa['id'];
        }

        if ($empresaIds === []) {
            $errors[] = 'No hay empresas registradas. Registra una empresa antes de crear accesos.';
        }

        if ($data['empresa_id'] === '') {
            $errors[] = 'Selecciona la empresa para la que deseas crear el acceso.';
        } elseif ($empresaIds !== [] && !in_array($data['empresa_id'], $empresaIds, true)) {
            $errors[] = 'La empresa seleccionada no es válida.';
        }

        if ($data['token'] === '') {
            $errors[] = 'El token es obligatorio.';
        } elseif (!portalAccessIsValidUuid($data['token'])) {
            $errors[] = 'El token debe tener un formato UUID válido.';
        }

        if ($data['nip'] === '') {
            $errors[] = 'El NIP es obligatorio.';
        } elseif (preg_match('/^[0-9]{4,6}$/', $data['nip']) !== 1) {
            $errors[] = 'El NIP debe contener entre 4 y 6 dígitos numéricos.';
        }

        if ($data['activo'] !== '1' && $data['activo'] !== '0') {
            $errors[] = 'Selecciona un estatus válido.';
        }

        if ($data['expiracion'] !== '') {
            $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i', $data['expiracion']);

            if (!$dateTime instanceof \DateTime) {
                $errors[] = 'La fecha de expiración no tiene un formato válido.';
            }
        }

        return $errors;
    }
}

if (!function_exists('portalAccessSuccessMessage')) {
    function portalAccessSuccessMessage(int $accessId): string
    {
        return 'El acceso se creó correctamente con el folio #' . $accessId . '.';
    }
}

if (!function_exists('portalAccessControllerErrorMessage')) {
    function portalAccessControllerErrorMessage(\Throwable $exception): string
    {
        return 'No fue posible conectar con la base de datos. Intenta nuevamente más tarde.';
    }
}

if (!function_exists('portalAccessDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function portalAccessDuplicateErrors(\PDOException $exception): array
    {
        $errorInfo = $exception->errorInfo;

        if (!is_array($errorInfo) || !isset($errorInfo[1]) || (int) $errorInfo[1] !== 1062) {
            return [];
        }

        $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? $errorInfo[2] : '';

        if ($detail !== '' && stripos($detail, 'uk_portal_token') !== false) {
            return ['El token generado ya está en uso. Genera uno diferente e inténtalo nuevamente.'];
        }

        return ['Ya existe un acceso con la información proporcionada.'];
    }
}

if (!function_exists('portalAccessPersistenceErrorMessage')) {
    function portalAccessPersistenceErrorMessage(\Throwable $exception): string
    {
        return 'Ocurrió un error al guardar el acceso. Intenta nuevamente.';
    }
}

if (!function_exists('portalAccessPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array{
     *     empresa_id: int,
     *     token: string,
     *     nip: string,
     *     activo: int,
     *     expiracion: ?string
     * }
     */
    function portalAccessPrepareForPersistence(array $data): array
    {
        $expiracion = null;

        if ($data['expiracion'] !== '') {
            $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i', $data['expiracion']);

            if ($dateTime instanceof \DateTime) {
                $expiracion = $dateTime->format('Y-m-d H:i:s');
            }
        }

        return [
            'empresa_id' => (int) $data['empresa_id'],
            'token' => $data['token'],
            'nip' => $data['nip'],
            'activo' => $data['activo'] === '1' ? 1 : 0,
            'expiracion' => $expiracion,
        ];
    }
}
