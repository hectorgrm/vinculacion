<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../common/model/db.php';

use Common\Model\Database;

if (!function_exists('auditoriaActorTipoOptions')) {
    /**
     * @return array<string, string>
     */
    function auditoriaActorTipoOptions(): array
    {
        return [
            'empresa' => 'Empresa',
            'usuario' => 'Usuario',
            'sistema' => 'Sistema',
        ];
    }
}

if (!function_exists('auditoriaActorTipoLabel')) {
    function auditoriaActorTipoLabel(?string $actorTipo): string
    {
        $actorTipo = trim((string) $actorTipo);

        if ($actorTipo === '') {
            return 'Sin actor';
        }

        $actorTipo = function_exists('mb_strtolower')
            ? mb_strtolower($actorTipo, 'UTF-8')
            : strtolower($actorTipo);

        return auditoriaActorTipoOptions()[$actorTipo] ?? ucfirst($actorTipo);
    }
}

if (!function_exists('auditoriaListDefaults')) {
    /**
     * @return array{
     *     q: string,
     *     actor_tipo: string,
     *     actor_id: string,
     *     accion: string,
     *     entidad: string,
     *     fecha_desde: string,
     *     fecha_hasta: string,
     *     auditorias: array<int, array<string, mixed>>,
     *     actorTipoOptions: array<string, string>,
     *     errorMessage: ?string
     * }
     */
    function auditoriaListDefaults(): array
    {
        return [
            'q' => '',
            'actor_tipo' => '',
            'actor_id' => '',
            'accion' => '',
            'entidad' => '',
            'fecha_desde' => '',
            'fecha_hasta' => '',
            'auditorias' => [],
            'actorTipoOptions' => auditoriaActorTipoOptions(),
            'errorMessage' => null,
        ];
    }
}

if (!function_exists('auditoriaNormalizeSearch')) {
    function auditoriaNormalizeSearch(?string $value): string
    {
        return trim((string) $value);
    }
}

if (!function_exists('auditoriaNormalizeActorTipo')) {
    function auditoriaNormalizeActorTipo(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($value, 'UTF-8')
            : strtolower($value);

        return array_key_exists($normalized, auditoriaActorTipoOptions())
            ? $normalized
            : null;
    }
}

if (!function_exists('auditoriaNormalizePositiveInt')) {
    function auditoriaNormalizePositiveInt(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_numeric($value)) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        return null;
    }
}

if (!function_exists('auditoriaNormalizePlain')) {
    function auditoriaNormalizePlain(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }
}

if (!function_exists('auditoriaNormalizeDate')) {
    function auditoriaNormalizeDate(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);

        if ($date === false) {
            return null;
        }

        return $date->format('Y-m-d');
    }
}

if (!function_exists('auditoriaFormatDateTime')) {
    function auditoriaFormatDateTime(?string $value, string $fallback = 'N/A'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00 00:00:00') {
            return $fallback;
        }

        try {
            $date = new \DateTimeImmutable($value);
        } catch (\Throwable) {
            return $fallback;
        }

        return $date->format('d/m/Y H:i:s');
    }
}

if (!function_exists('auditoriaValueOrDefault')) {
    function auditoriaValueOrDefault(mixed $value, string $fallback = 'N/A'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $value = trim($value);

            return $value !== '' ? $value : $fallback;
        }

        if (is_scalar($value)) {
            $value = (string) $value;

            return $value !== '' ? $value : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('auditoriaObtenerIP')) {
    function auditoriaObtenerIP(): string
    {
        $candidates = [
            $_SERVER['HTTP_CLIENT_IP'] ?? null,
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ];

        foreach ($candidates as $value) {
            if (!is_string($value) || trim($value) === '') {
                continue;
            }

            if (str_contains($value, ',')) {
                $value = explode(',', $value)[0];
            }

            $value = trim($value);

            if ($value === '' || strcasecmp($value, 'unknown') === 0) {
                continue;
            }

            if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                return $value;
            }

            if (filter_var($value, FILTER_VALIDATE_IP)) {
                return $value;
            }
        }

        return '127.0.0.1';
    }
}

if (!function_exists('auditoriaRegistrarEvento')) {
    /**
     * @param array{
     *     accion: string,
     *     entidad: string,
     *     entidad_id: int|string|null,
     *     actor_tipo?: string|null,
     *     actor_id?: int|string|null,
     *     ip?: string|null
     * } $payload
     */
    function auditoriaRegistrarEvento(array $payload): bool
    {
        try {
            $accion = auditoriaNormalizePlain($payload['accion'] ?? null);
            $entidad = auditoriaNormalizePlain($payload['entidad'] ?? null);
            $entidadId = auditoriaNormalizePositiveInt($payload['entidad_id'] ?? null);

            if ($accion === null || $entidad === null || $entidadId === null) {
                return false;
            }

            $actorTipo = auditoriaNormalizeActorTipo($payload['actor_tipo'] ?? null) ?? 'sistema';
            $actorId = auditoriaNormalizePositiveInt($payload['actor_id'] ?? null);

            $ip = isset($payload['ip']) ? trim((string) $payload['ip']) : '';
            if ($ip === '') {
                $ip = auditoriaObtenerIP();
            }

            $pdo = Database::getConnection();
            $statement = $pdo->prepare(
                'INSERT INTO auditoria (actor_tipo, actor_id, accion, entidad, entidad_id, ip) '
                . 'VALUES (:actor_tipo, :actor_id, :accion, :entidad, :entidad_id, :ip)'
            );

            $statement->bindValue(':actor_tipo', $actorTipo, PDO::PARAM_STR);

            if ($actorId === null) {
                $statement->bindValue(':actor_id', null, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':actor_id', $actorId, PDO::PARAM_INT);
            }

            $statement->bindValue(':accion', $accion, PDO::PARAM_STR);
            $statement->bindValue(':entidad', $entidad, PDO::PARAM_STR);
            $statement->bindValue(':entidad_id', $entidadId, PDO::PARAM_INT);

            if ($ip === '') {
                $statement->bindValue(':ip', null, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':ip', $ip, PDO::PARAM_STR);
            }

            return $statement->execute();
        } catch (\Throwable) {
            return false;
        }
    }
}
