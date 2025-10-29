<?php

declare(strict_types=1);

require_once __DIR__ . '/documentotipo_funtions_list.php';
require_once __DIR__ . '/documentotipo_functions_add.php';

if (!function_exists('documentoTipoEditDefaults')) {
    /**
     * @return array{
     *     documentoTipoId: ?int,
     *     formData: array<string, string>,
     *     tipoEmpresaOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string
     * }
     */
    function documentoTipoEditDefaults(): array
    {
        return [
            'documentoTipoId' => null,
            'formData' => documentoTipoEditFormDefaults(),
            'tipoEmpresaOptions' => documentoTipoEmpresaOptions(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
            'loadError' => null,
        ];
    }
}

if (!function_exists('documentoTipoEditFormDefaults')) {
    /**
     * @return array<string, string>
     */
    function documentoTipoEditFormDefaults(): array
    {
        return documentoTipoAddFormDefaults();
    }
}

if (!function_exists('documentoTipoEditResolveId')) {
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     * @return array{documentoTipoId: ?int, error: ?string}
     */
    function documentoTipoEditResolveId(array $query, array $post): array
    {
        $idParam = $query['id'] ?? ($post['documento_tipo_id'] ?? null);

        if ($idParam === null) {
            return [
                'documentoTipoId' => null,
                'error' => 'No se especifico el tipo de documento que deseas editar.',
            ];
        }

        if (!is_scalar($idParam)) {
            return [
                'documentoTipoId' => null,
                'error' => 'El identificador proporcionado no es valido.',
            ];
        }

        $idValue = trim((string) $idParam);

        if ($idValue === '' || preg_match('/^\d+$/', $idValue) !== 1) {
            return [
                'documentoTipoId' => null,
                'error' => 'El identificador proporcionado no es valido.',
            ];
        }

        $id = (int) $idValue;

        if ($id <= 0) {
            return [
                'documentoTipoId' => null,
                'error' => 'El identificador proporcionado no es valido.',
            ];
        }

        return [
            'documentoTipoId' => $id,
            'error' => null,
        ];
    }
}

if (!function_exists('documentoTipoEditIsPostRequest')) {
    function documentoTipoEditIsPostRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('documentoTipoEditHydrateForm')) {
    /**
     * @param array<string, string> $defaults
     * @param array<string, mixed> $record
     * @return array<string, string>
     */
    function documentoTipoEditHydrateForm(array $defaults, array $record): array
    {
        foreach ($defaults as $field => $_value) {
            if (!array_key_exists($field, $record)) {
                continue;
            }

            if ($record[$field] === null) {
                $defaults[$field] = '';
                continue;
            }

            $defaults[$field] = (string) $record[$field];
        }

        if (isset($record['obligatorio'])) {
            $defaults['obligatorio'] = documentoTipoCastBool($record['obligatorio']) ? '1' : '0';
        }

        if (isset($record['tipo_empresa'])) {
            $defaults['tipo_empresa'] = documentoTipoAddNormalizeTipoEmpresa($record['tipo_empresa']);
        }

        return $defaults;
    }
}

if (!function_exists('documentoTipoEditSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function documentoTipoEditSanitizeInput(array $input): array
    {
        return documentoTipoAddSanitizeInput($input);
    }
}

if (!function_exists('documentoTipoEditValidateData')) {
    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    function documentoTipoEditValidateData(array $data): array
    {
        return documentoTipoAddValidateData($data);
    }
}

if (!function_exists('documentoTipoEditPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array{
     *     nombre: string,
     *     descripcion: ?string,
     *     obligatorio: int,
     *     tipo_empresa: string
     * }
     */
    function documentoTipoEditPrepareForPersistence(array $data): array
    {
        return documentoTipoAddPrepareForPersistence($data);
    }
}

if (!function_exists('documentoTipoEditSuccessMessage')) {
    function documentoTipoEditSuccessMessage(): string
    {
        return 'El tipo de documento se actualizo correctamente.';
    }
}

if (!function_exists('documentoTipoEditControllerErrorMessage')) {
    function documentoTipoEditControllerErrorMessage(\Throwable $exception): string
    {
        return 'No se pudo establecer conexion con la base de datos. Intenta nuevamente mas tarde.';
    }
}

if (!function_exists('documentoTipoEditNotFoundMessage')) {
    function documentoTipoEditNotFoundMessage(int $documentoTipoId): string
    {
        return 'No se encontro el tipo de documento #' . $documentoTipoId . '.';
    }
}

if (!function_exists('documentoTipoEditDuplicateErrors')) {
    /**
     * @return array<int, string>
     */
    function documentoTipoEditDuplicateErrors(\PDOException $exception): array
    {
        return documentoTipoAddDuplicateErrors($exception);
    }
}

if (!function_exists('documentoTipoEditPersistenceErrorMessage')) {
    function documentoTipoEditPersistenceErrorMessage(\Throwable $exception): string
    {
        return 'Ocurrio un error al actualizar el tipo de documento. Intenta nuevamente.';
    }
}

if (!function_exists('documentoTipoEditFormValue')) {
    /**
     * @param array<string, string> $data
     */
    function documentoTipoEditFormValue(array $data, string $field): string
    {
        return documentoTipoAddFormValue($data, $field);
    }
}

