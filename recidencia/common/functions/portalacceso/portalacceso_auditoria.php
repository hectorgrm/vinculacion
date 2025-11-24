<?php

declare(strict_types=1);

require_once __DIR__ . '/../auditoria/auditoriafunctions.php';

if (!function_exists('portalAccessCurrentAuditContext')) {
    /**
     * @return array<string, mixed>
     */
    function portalAccessCurrentAuditContext(): array
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

if (!function_exists('portalAccessRegisterAuditEvent')) {
    /**
     * @param array<string, mixed> $context
     * @param array<int, array<string, mixed>> $detalles
     */
    function portalAccessRegisterAuditEvent(string $accion, int $accessId, array $context = [], array $detalles = []): bool
    {
        $accion = trim($accion);

        if ($accion === '' || $accessId <= 0) {
            return false;
        }

        $payload = [
            'accion' => $accion,
            'entidad' => 'rp_portal_acceso',
            'entidad_id' => $accessId,
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

if (!function_exists('portalAccessFormatExpirationForAudit')) {
    function portalAccessFormatExpirationForAudit(?string $expiracion): ?string
    {
        $expiracion = is_string($expiracion) ? trim($expiracion) : '';

        if ($expiracion === '') {
            return 'Sin expiracion';
        }

        $formats = ['Y-m-d\\TH:i', 'Y-m-d H:i:s', \DateTimeInterface::ATOM];

        foreach ($formats as $format) {
            $dateTime = \DateTime::createFromFormat($format, $expiracion);

            if ($dateTime instanceof \DateTime) {
                return $dateTime->format('Y-m-d H:i');
            }
        }

        return $expiracion;
    }
}

if (!function_exists('portalAccessBuildCreacionDetalles')) {
    /**
     * @param array<string, string> $data
     * @param array<int, array<string, string>> $empresaOptions
     * @return array<int, array<string, mixed>>
     */
    function portalAccessBuildCreacionDetalles(array $data, array $empresaOptions = []): array
    {
        $detalles = [];

        $empresaValue = null;

        if ($empresaOptions !== []) {
            $empresaId = $data['empresa_id'] ?? '';

            foreach ($empresaOptions as $empresa) {
                if (!isset($empresa['id']) || (string) $empresa['id'] !== (string) $empresaId) {
                    continue;
                }

                $nombre = isset($empresa['nombre']) ? trim((string) $empresa['nombre']) : '';
                $numeroControl = isset($empresa['numero_control']) ? trim((string) $empresa['numero_control']) : '';

                $empresaValue = $nombre;

                if ($numeroControl !== '') {
                    $empresaValue = $empresaValue !== ''
                        ? $empresaValue . ' - ' . $numeroControl
                        : $numeroControl;
                }

                break;
            }
        }

        if ($empresaValue === null && isset($data['empresa_id'])) {
            $empresaValue = 'ID ' . (string) $data['empresa_id'];
        }

        $empresaValue = auditoriaNormalizeDetalleValue($empresaValue);

        if ($empresaValue !== null) {
            $detalles[] = [
                'campo' => 'empresa_id',
                'campo_label' => 'Empresa',
                'valor_anterior' => null,
                'valor_nuevo' => $empresaValue,
            ];
        }

        $token = auditoriaNormalizeDetalleValue($data['token'] ?? null);
        if ($token !== null) {
            $detalles[] = [
                'campo' => 'token',
                'campo_label' => 'Token',
                'valor_anterior' => null,
                'valor_nuevo' => $token,
            ];
        }

        $nip = auditoriaNormalizeDetalleValue($data['nip'] ?? null);
        if ($nip !== null) {
            $detalles[] = [
                'campo' => 'nip',
                'campo_label' => 'NIP',
                'valor_anterior' => null,
                'valor_nuevo' => $nip,
            ];
        }

        $estatus = (isset($data['activo']) && $data['activo'] === '0') ? 'Inactivo' : 'Activo';
        $estatus = auditoriaNormalizeDetalleValue($estatus);
        if ($estatus !== null) {
            $detalles[] = [
                'campo' => 'activo',
                'campo_label' => 'Estatus',
                'valor_anterior' => null,
                'valor_nuevo' => $estatus,
            ];
        }

        $expiracion = portalAccessFormatExpirationForAudit($data['expiracion'] ?? null);
        $expiracion = auditoriaNormalizeDetalleValue($expiracion);
        if ($expiracion !== null) {
            $detalles[] = [
                'campo' => 'expiracion',
                'campo_label' => 'Expiracion',
                'valor_anterior' => null,
                'valor_nuevo' => $expiracion,
            ];
        }

        return $detalles;
    }
}
