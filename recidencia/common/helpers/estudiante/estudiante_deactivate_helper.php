<?php

declare(strict_types=1);

if (!function_exists('estudianteDeactivateDefaults')) {
    /**
     * @return array{
     *     estudianteId: ?int,
     *     estudiante: ?array<string, mixed>,
     *     empresa: ?array<string, mixed>,
     *     convenio: ?array<string, mixed>,
     *     errors: array<int, string>,
     *     success: bool,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string
     * }
     */
    function estudianteDeactivateDefaults(): array
    {
        return [
            'estudianteId' => null,
            'estudiante' => null,
            'empresa' => null,
            'convenio' => null,
            'errors' => [],
            'success' => false,
            'successMessage' => null,
            'controllerError' => null,
            'loadError' => null,
        ];
    }
}

if (!function_exists('estudianteDeactivateIsPostRequest')) {
    function estudianteDeactivateIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD'])
            && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('estudianteDeactivateResolveId')) {
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     * @return array{estudianteId: ?int, error: ?string}
     */
    function estudianteDeactivateResolveId(array $query, array $post): array
    {
        $sources = estudianteDeactivateIsPostRequest() ? [$post, $query] : [$query, $post];

        foreach ($sources as $source) {
            if (!is_array($source)) {
                continue;
            }

            if (!array_key_exists('id', $source)) {
                continue;
            }

            $value = $source['id'];

            if (is_array($value)) {
                continue;
            }

            $value = trim((string) $value);

            if ($value === '') {
                continue;
            }

            $id = filter_var($value, FILTER_VALIDATE_INT);

            if ($id !== false && $id > 0) {
                return ['estudianteId' => $id, 'error' => null];
            }
        }

        return [
            'estudianteId' => null,
            'error' => 'No se identificó al estudiante que deseas desactivar.',
        ];
    }
}

if (!function_exists('estudianteDeactivateControllerErrorMessage')) {
    function estudianteDeactivateControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo establecer comunicación con la base de datos. Intenta nuevamente más tarde.';
    }
}

if (!function_exists('estudianteDeactivateLoadErrorMessage')) {
    function estudianteDeactivateLoadErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'Ocurrió un problema al obtener la información del estudiante.';
    }
}

if (!function_exists('estudianteDeactivatePersistenceErrorMessage')) {
    function estudianteDeactivatePersistenceErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo desactivar al estudiante. Intenta nuevamente.';
    }
}

if (!function_exists('estudianteDeactivateRecordNotFoundMessage')) {
    function estudianteDeactivateRecordNotFoundMessage(int $estudianteId): string
    {
        return 'No se encontró el estudiante con folio #' . $estudianteId . '.';
    }
}

if (!function_exists('estudianteDeactivateAlreadyInactiveMessage')) {
    function estudianteDeactivateAlreadyInactiveMessage(): string
    {
        return 'El estudiante ya se encuentra inactivo.';
    }
}

if (!function_exists('estudianteDeactivateConfirmationErrorMessage')) {
    function estudianteDeactivateConfirmationErrorMessage(): string
    {
        return 'Debes confirmar la desactivación del estudiante.';
    }
}

if (!function_exists('estudianteDeactivateSuccessMessage')) {
    function estudianteDeactivateSuccessMessage(): string
    {
        return 'El estudiante fue desactivado correctamente. Podrás reactivarlo editando el estatus.';
    }
}

if (!function_exists('estudianteDeactivateIsConfirmed')) {
    /**
     * @param array<string, mixed> $input
     */
    function estudianteDeactivateIsConfirmed(array $input): bool
    {
        if (!array_key_exists('confirm', $input)) {
            return false;
        }

        $value = $input['confirm'];

        if (is_array($value)) {
            return false;
        }

        $value = trim((string) $value);

        return $value === '1' || strcasecmp($value, 'true') === 0 || $value === 'on';
    }
}

if (!function_exists('estudianteDeactivateFormatNombreCompleto')) {
    /**
     * @param array<string, mixed>|null $estudiante
     */
    function estudianteDeactivateFormatNombreCompleto(?array $estudiante): string
    {
        if ($estudiante === null) {
            return '';
        }

        $parts = [];

        foreach (['nombre', 'apellido_paterno', 'apellido_materno'] as $key) {
            if (!isset($estudiante[$key])) {
                continue;
            }

            $value = trim((string) $estudiante[$key]);

            if ($value !== '') {
                $parts[] = $value;
            }
        }

        return $parts !== [] ? implode(' ', $parts) : '';
    }
}
