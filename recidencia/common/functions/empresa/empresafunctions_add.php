<?php

declare(strict_types=1);

require_once __DIR__ . '/../empresafunction.php';
if (!function_exists('empresaAddDefaults')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     estatusOptions: array<int, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string
     * }
     */
    function empresaAddDefaults(): array
    {
        return [
            'formData' => empresaFormDefaults(),
            'estatusOptions' => empresaStatusOptions(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
        ];
    }
}

if (!function_exists('empresaAddIsPostRequest')) {
    function empresaAddIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('empresaAddSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function empresaAddSanitizeInput(array $input): array
    {
        return empresaSanitizeInput($input);
    }
}

if (!function_exists('empresaAddValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function empresaAddValidateData(array $data): array
    {
        return empresaValidateData($data);
    }
}

if (!function_exists('empresaAddSuccessMessage')) {
    function empresaAddSuccessMessage(int $empresaId): string
    {
        return 'La empresa se registró correctamente con el folio #' . $empresaId . '.';
    }
}

if (!function_exists('empresaAddControllerErrorMessage')) {
    function empresaAddControllerErrorMessage(\Throwable $exception): string
    {
        return 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';
    }
}

if (!function_exists('empresaAddDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function empresaAddDuplicateErrors(\PDOException $exception): array
    {
        $errorInfo = $exception->errorInfo;

        if (!is_array($errorInfo) || !isset($errorInfo[1]) || (int) $errorInfo[1] !== 1062) {
            return [];
        }

        $duplicateDetail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? $errorInfo[2] : '';

        if ($duplicateDetail !== '' && stripos($duplicateDetail, 'numero_control') !== false) {
            return ['Ya existe una empresa registrada con el número de control proporcionado.'];
        }

        if ($duplicateDetail !== '' && stripos($duplicateDetail, 'rfc') !== false) {
            return ['Ya existe una empresa registrada con el RFC proporcionado.'];
        }

        return ['Ya existe una empresa registrada con la información proporcionada.'];
    }
}

if (!function_exists('empresaAddPersistenceErrorMessage')) {
    function empresaAddPersistenceErrorMessage(\Throwable $exception): string
    {
        return 'Ocurrió un error al registrar la empresa. Intenta nuevamente.';
    }
}
