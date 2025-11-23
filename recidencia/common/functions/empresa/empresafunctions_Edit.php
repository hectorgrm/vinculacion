<?php

declare(strict_types=1);

require_once __DIR__ . '/../empresafunction.php';
require_once __DIR__ . '/../auditoria/auditoriafunctions.php';

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

if (!function_exists('empresaCurrentAuditContext')) {
    /**
     * @return array<string, mixed>
     */
    function empresaCurrentAuditContext(): array
    {
        $context = [];

        if (isset($GLOBALS['residenciaAuthUser']) && is_array($GLOBALS['residenciaAuthUser'])) {
            $context['actor_tipo'] = 'usuario';
            $actorId = auditoriaNormalizePositiveInt($GLOBALS['residenciaAuthUser']['id'] ?? null);

            if ($actorId !== null) {
                $context['actor_id'] = $actorId;
            }
        } elseif (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            $context['actor_tipo'] = 'usuario';
            $actorId = auditoriaNormalizePositiveInt($_SESSION['user']['id'] ?? null);

            if ($actorId !== null) {
                $context['actor_id'] = $actorId;
            }
        } elseif (isset($_SESSION['empresa']) && is_array($_SESSION['empresa'])) {
            $context['actor_tipo'] = 'empresa';
            $actorId = auditoriaNormalizePositiveInt($_SESSION['empresa']['id'] ?? null);

            if ($actorId !== null) {
                $context['actor_id'] = $actorId;
            }
        }

        if (!isset($context['actor_tipo'])) {
            $context['actor_tipo'] = 'sistema';
        }

        $ip = auditoriaObtenerIP();
        if ($ip !== '') {
            $context['ip'] = $ip;
        }

        return $context;
    }
}

if (!function_exists('empresaEditAuditFieldLabels')) {
    /**
     * @return array<string, string>
     */
    function empresaEditAuditFieldLabels(): array
    {
        return [
            'numero_control' => 'No. de control',
            'nombre' => 'Nombre',
            'rfc' => 'RFC',
            'representante' => 'Representante legal',
            'cargo_representante' => 'Cargo del representante',
            'sector' => 'Sector',
            'sitio_web' => 'Sitio web',
            'contacto_nombre' => 'Nombre de contacto',
            'contacto_email' => 'Correo de contacto',
            'telefono' => 'Teléfono',
            'estado' => 'Estado',
            'municipio' => 'Municipio',
            'cp' => 'Código postal',
            'direccion' => 'Dirección',
            'estatus' => 'Estatus',
            'regimen_fiscal' => 'Régimen fiscal',
            'notas' => 'Notas',
        ];
    }
}

if (!function_exists('empresaRegistrarEventoActualizacion')) {
    /**
     * @param array<int, array{campo: string, campo_label: string, valor_anterior: ?string, valor_nuevo: ?string}> $detalles
     * @param array<string, mixed>|null $context
     */
    function empresaRegistrarEventoActualizacion(int $empresaId, array $detalles, ?array $context = null): void
    {
        if ($empresaId <= 0 || $detalles === []) {
            return;
        }

        $payload = [
            'accion' => 'actualizar',
            'entidad' => 'rp_empresa',
            'entidad_id' => $empresaId,
            'detalles' => $detalles,
        ];

        $context = $context ?? empresaCurrentAuditContext();

        if (isset($context['actor_tipo'])) {
            $payload['actor_tipo'] = $context['actor_tipo'];
        }

        if (isset($context['actor_id'])) {
            $payload['actor_id'] = $context['actor_id'];
        }

        if (isset($context['ip'])) {
            $payload['ip'] = $context['ip'];
        }

        if (!isset($payload['actor_tipo']) && isset($payload['actor_id'])) {
            $payload['actor_tipo'] = 'usuario';
        }

        auditoriaRegistrarEvento($payload);
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
