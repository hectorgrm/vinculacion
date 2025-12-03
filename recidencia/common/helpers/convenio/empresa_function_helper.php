<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../common/functions/auditoria/auditoriafunctions.php';

if (!function_exists('empresaAuditoriaTruncateAccion')) {
    function empresaAuditoriaTruncateAccion(string $accion): string
    {
        if (function_exists('mb_substr')) {
            return mb_substr($accion, 0, 100, 'UTF-8');
        }

        return substr($accion, 0, 100);
    }
}

if (!function_exists('empresaRegistrarEventoDesactivacion')) {
    function empresaRegistrarEventoDesactivacion(
        int $empresaId,
        ?int $actorId,
        int $conveniosAfectados,
        int $documentosAfectados,
        int $accesosAfectados
    ): void {
        $resumen = [];

        if ($conveniosAfectados > 0) {
            $resumen[] = 'convenios:' . $conveniosAfectados;
        }

        if ($documentosAfectados > 0) {
            $resumen[] = 'docs:' . $documentosAfectados;
        }

        if ($accesosAfectados > 0) {
            $resumen[] = 'accesos:' . $accesosAfectados;
        }

        $accion = 'desactivar_cascada';

        if ($resumen !== []) {
            $accion .= ' [' . implode(', ', $resumen) . ']';
        }

        $payload = [
            'accion' => empresaAuditoriaTruncateAccion($accion),
            'entidad' => 'rp_empresa',
            'entidad_id' => $empresaId,
        ];

        if ($actorId !== null && $actorId > 0) {
            $payload['actor_tipo'] = 'usuario';
            $payload['actor_id'] = $actorId;
        }

        auditoriaRegistrarEvento($payload);
    }
}

if (!function_exists('empresaRegistrarEventoReactivacion')) {
    function empresaRegistrarEventoReactivacion(
        int $empresaId,
        ?int $actorId,
        int $accesosAfectados
    ): void {
        $resumen = [];

        if ($accesosAfectados > 0) {
            $resumen[] = 'accesos:' . $accesosAfectados;
        }

        $accion = 'reactivar_en_revision';

        if ($resumen !== []) {
            $accion .= ' [' . implode(', ', $resumen) . ']';
        }

        $payload = [
            'accion' => empresaAuditoriaTruncateAccion($accion),
            'entidad' => 'rp_empresa',
            'entidad_id' => $empresaId,
        ];

        if ($actorId !== null && $actorId > 0) {
            $payload['actor_tipo'] = 'usuario';
            $payload['actor_id'] = $actorId;
        }

        auditoriaRegistrarEvento($payload);
    }
}
