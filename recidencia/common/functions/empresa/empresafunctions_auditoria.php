<?php

declare(strict_types=1);

require_once __DIR__ . '/../empresafunction.php';
require_once __DIR__ . '/../auditoria/auditoriafunctions.php';

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

if (!function_exists('empresaRegisterAuditEvent')) {
    /**
     * @param array<string, mixed> $context
     * @param array<int, array<string, mixed>> $detalles
     */
    function empresaRegisterAuditEvent(string $accion, int $empresaId, array $context = [], array $detalles = []): bool
    {
        $accion = trim($accion);

        if ($accion === '' || $empresaId <= 0) {
            return false;
        }

        $payload = [
            'accion' => $accion,
            'entidad' => 'rp_empresa',
            'entidad_id' => $empresaId,
        ];

        if (isset($context['actor_tipo'])) {
            $payload['actor_tipo'] = $context['actor_tipo'];
        }

        if (isset($context['actor_id'])) {
            $payload['actor_id'] = $context['actor_id'];
        }

        if (isset($context['ip'])) {
            $payload['ip'] = $context['ip'];
        }

        if ($detalles !== []) {
            $payload['detalles'] = $detalles;
        }

        if (!isset($payload['actor_tipo']) && isset($payload['actor_id'])) {
            $payload['actor_tipo'] = 'usuario';
        }

        return auditoriaRegistrarEvento($payload);
    }
}

if (!function_exists('empresaBuildCreacionDetalles')) {
    /**
     * @param array<string, string> $empresaData
     * @return array<int, array<string, mixed>>
     */
    function empresaBuildCreacionDetalles(array $empresaData): array
    {
        $labels = [
            'nombre' => 'Nombre',
            'rfc' => 'RFC',
            'representante' => 'Representante',
            'estatus' => 'Estatus inicial',
        ];

        $detalles = [];

        foreach ($labels as $field => $label) {
            $valor = $empresaData[$field] ?? '';

            if ($field === 'estatus') {
                $valor = empresaNormalizeStatus($valor);
            }

            $valor = auditoriaNormalizeDetalleValue($valor);

            if ($valor === null) {
                continue;
            }

            $detalles[] = [
                'campo' => $field,
                'campo_label' => $label,
                'valor_anterior' => null,
                'valor_nuevo' => $valor,
            ];
        }

        return $detalles;
    }
}
