<?php

declare(strict_types=1);

require_once __DIR__ . '/../empresafunction.php';

if (!function_exists('empresaEditDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     formData: array<string, string>,
     *     estatusOptions: array<int, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string
     * }
     */
    function empresaEditDefaults(): array
    {
        return [
            'empresaId' => null,
            'formData' => empresaFormDefaults(),
            'estatusOptions' => empresaStatusOptions(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
            'loadError' => null,
        ];
    }
}

if (!function_exists('empresaEditResolveId')) {
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     * @return array{empresaId: ?int, error: ?string}
     */
    function empresaEditResolveId(array $query, array $post): array
    {
        $empresaIdParam = $query['id'] ?? ($post['empresa_id'] ?? null);

        if ($empresaIdParam === null) {
            return [
                'empresaId' => null,
                'error' => 'No se especificó la empresa que deseas editar.',
            ];
        }

        if (!is_string($empresaIdParam) || preg_match('/^\d+$/', $empresaIdParam) !== 1) {
            return [
                'empresaId' => null,
                'error' => 'El identificador proporcionado no es válido.',
            ];
        }

        $empresaId = (int) $empresaIdParam;

        if ($empresaId <= 0) {
            return [
                'empresaId' => null,
                'error' => 'El identificador proporcionado no es válido.',
            ];
        }

        return [
            'empresaId' => $empresaId,
            'error' => null,
        ];
    }
}

if (!function_exists('empresaEditIsPostRequest')) {
    function empresaEditIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('empresaEditSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function empresaEditSanitizeInput(array $input): array
    {
        return empresaSanitizeInput($input);
    }
}

if (!function_exists('empresaEditValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function empresaEditValidateData(array $data): array
    {
        return empresaValidateData($data);
    }
}

if (!function_exists('empresaEditSuccessMessage')) {
    function empresaEditSuccessMessage(): string
    {
        return 'La información de la empresa se actualizó correctamente.';
    }
}

if (!function_exists('empresaEditControllerErrorMessage')) {
    function empresaEditControllerErrorMessage(\Throwable $exception): string
    {
        return 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';
    }
}

if (!function_exists('empresaEditDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function empresaEditDuplicateErrors(\PDOException $exception): array
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

if (!function_exists('empresaEditPersistenceErrorMessage')) {
    function empresaEditPersistenceErrorMessage(\Throwable $exception): string
    {
        return 'Ocurrió un error al actualizar la empresa. Intenta nuevamente.';
    }
}
