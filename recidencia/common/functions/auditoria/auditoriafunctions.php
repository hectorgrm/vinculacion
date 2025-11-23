<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../common/model/db.php';
require_once __DIR__ . '/../../../controller/auditoria/AuditoriaRegistrarController.php';
require_once __DIR__ . '/../../../model/auditoria/AuditoriaModel.php';
require_once __DIR__ . '/../convenio/conveniofunctions_auditoria.php';

use Common\Model\Database;
use Residencia\Controller\Auditoria\AuditoriaRegistrarController;

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

if (!function_exists('auditoriaFormatActorLabel')) {
    function auditoriaFormatActorLabel(mixed $actorTipo, mixed $actorId, mixed $actorNombre): string
    {
        if (function_exists('convenioAuditoriaFormatActor')) {
            return convenioAuditoriaFormatActor($actorTipo, $actorId, is_string($actorNombre) ? $actorNombre : null);
        }

        $actorNombre = is_string($actorNombre) ? trim($actorNombre) : '';
        if ($actorNombre !== '') {
            return $actorNombre;
        }

        $actorTipo = is_string($actorTipo) ? trim($actorTipo) : '';
        $actorTipoLower = function_exists('mb_strtolower')
            ? mb_strtolower($actorTipo, 'UTF-8')
            : strtolower($actorTipo);

        $actorId = auditoriaNormalizePositiveInt($actorId);
        $tipoLabel = match ($actorTipoLower) {
            'usuario' => 'Usuario',
            'empresa' => 'Empresa',
            'sistema' => 'Sistema',
            default => ($actorTipo !== '' ? ucfirst($actorTipo) : 'Actor'),
        };

        if ($tipoLabel === 'Sistema' || $actorId === null) {
            return $tipoLabel;
        }

        return $tipoLabel . ' #' . $actorId;
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
     *     ip?: string|null,
     *     detalles?: array<int, array<string, mixed>>
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
            $detalles = auditoriaNormalizeDetallesPayload($payload['detalles'] ?? null);

            $ip = isset($payload['ip']) ? trim((string) $payload['ip']) : '';
            if ($ip === '') {
                $ip = auditoriaObtenerIP();
            }

            $controller = new AuditoriaRegistrarController();

            return $controller->registrar([
                'accion' => $accion,
                'entidad' => $entidad,
                'entidad_id' => $entidadId,
                'actor_tipo' => $actorTipo,
                'actor_id' => $actorId,
                'ip' => $ip,
                'detalles' => $detalles,
            ]);
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('auditoriaNormalizeDetalleCampo')) {
    function auditoriaNormalizeDetalleCampo(mixed $value): ?string
    {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $campo = trim((string) $value);

        if ($campo === '') {
            return null;
        }

        if (function_exists('mb_substr')) {
            $campo = mb_substr($campo, 0, 100, 'UTF-8');
        } else {
            $campo = substr($campo, 0, 100);
        }

        return $campo;
    }
}

if (!function_exists('auditoriaNormalizeDetalleLabel')) {
    function auditoriaNormalizeDetalleLabel(?string $value, string $campo): string
    {
        $label = trim((string) $value);

        if ($label === '') {
            $label = str_replace('_', ' ', $campo);

            if (function_exists('mb_convert_case')) {
                $label = mb_convert_case($label, MB_CASE_TITLE, 'UTF-8');
            } else {
                $label = ucwords($label);
            }
        }

        if (function_exists('mb_substr')) {
            return mb_substr($label, 0, 150, 'UTF-8');
        }

        return substr($label, 0, 150);
    }
}

if (!function_exists('auditoriaNormalizeDetalleValue')) {
    function auditoriaNormalizeDetalleValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);
        } elseif (is_scalar($value)) {
            $value = trim((string) $value);
        } else {
            return null;
        }

        if ($value === '') {
            return null;
        }

        if (function_exists('mb_strlen')) {
            if (mb_strlen($value, 'UTF-8') > 500) {
                return mb_substr($value, 0, 500, 'UTF-8');
            }

            return $value;
        }

        if (strlen($value) > 500) {
            return substr($value, 0, 500);
        }

        return $value;
    }
}

if (!function_exists('auditoriaNormalizeDetallesPayload')) {
    /**
     * @param mixed $detalles
     * @return array<int, array{campo: string, campo_label: string, valor_anterior: ?string, valor_nuevo: ?string}>
     */
    function auditoriaNormalizeDetallesPayload(mixed $detalles): array
    {
        if (!is_array($detalles) || $detalles === []) {
            return [];
        }

        $normalized = [];

        foreach ($detalles as $detalle) {
            if (!is_array($detalle)) {
                continue;
            }

            $campo = auditoriaNormalizeDetalleCampo($detalle['campo'] ?? null);

            if ($campo === null) {
                continue;
            }

            $valorAnterior = auditoriaNormalizeDetalleValue($detalle['valor_anterior'] ?? $detalle['anterior'] ?? null);
            $valorNuevo = auditoriaNormalizeDetalleValue($detalle['valor_nuevo'] ?? $detalle['nuevo'] ?? null);

            if ($valorAnterior === null && $valorNuevo === null) {
                continue;
            }

            $normalized[] = [
                'campo' => $campo,
                'campo_label' => auditoriaNormalizeDetalleLabel(
                    isset($detalle['campo_label']) && is_string($detalle['campo_label'])
                        ? $detalle['campo_label']
                        : (isset($detalle['etiqueta']) && is_string($detalle['etiqueta'])
                            ? $detalle['etiqueta']
                            : ($detalle['label'] ?? null)
                        ),
                    $campo
                ),
                'valor_anterior' => $valorAnterior,
                'valor_nuevo' => $valorNuevo,
            ];
        }

        return $normalized;
    }
}

if (!function_exists('auditoriaBuildCambios')) {
    /**
     * @param array<string, mixed> $previous
     * @param array<string, mixed> $current
     * @param array<string, string> $labels
     * @return array<int, array<string, mixed>>
     */
    function auditoriaBuildCambios(array $previous, array $current, array $labels = []): array
    {
        require_once __DIR__ . '/../../helpers/auditoria/auditoria_helper.php';

        return auditoriaDetectarCambios($previous, $current, $labels);
    }
}

if (!function_exists('auditoriaEnsureDetalleTable')) {
    function auditoriaEnsureDetalleTable(PDO $pdo): bool
    {
        static $ensured = false;

        if ($ensured) {
            return true;
        }

        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS auditoria_detalle (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                auditoria_id BIGINT NOT NULL,
                campo VARCHAR(100) NOT NULL,
                campo_label VARCHAR(150) NOT NULL,
                valor_anterior TEXT NULL,
                valor_nuevo TEXT NULL,
                creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_auditoria_detalle_auditoria (auditoria_id),
                CONSTRAINT fk_auditoria_detalle_auditoria
                    FOREIGN KEY (auditoria_id)
                    REFERENCES auditoria (id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        SQL;

        try {
            $pdo->exec($sql);
            $ensured = true;

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('auditoriaInsertDetalleRegistros')) {
    /**
     * @param array<int, array{campo: string, campo_label: string, valor_anterior: ?string, valor_nuevo: ?string}> $detalles
     */
    function auditoriaInsertDetalleRegistros(PDO $pdo, int $auditoriaId, array $detalles): void
    {
        if ($detalles === [] || !auditoriaEnsureDetalleTable($pdo)) {
            return;
        }

        $statement = $pdo->prepare(
            'INSERT INTO auditoria_detalle (auditoria_id, campo, campo_label, valor_anterior, valor_nuevo) '
            . 'VALUES (:auditoria_id, :campo, :campo_label, :valor_anterior, :valor_nuevo)'
        );

        foreach ($detalles as $detalle) {
            $statement->bindValue(':auditoria_id', $auditoriaId, PDO::PARAM_INT);
            $statement->bindValue(':campo', $detalle['campo'], PDO::PARAM_STR);
            $statement->bindValue(':campo_label', $detalle['campo_label'], PDO::PARAM_STR);

            if ($detalle['valor_anterior'] === null) {
                $statement->bindValue(':valor_anterior', null, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':valor_anterior', $detalle['valor_anterior'], PDO::PARAM_STR);
            }

            if ($detalle['valor_nuevo'] === null) {
                $statement->bindValue(':valor_nuevo', null, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':valor_nuevo', $detalle['valor_nuevo'], PDO::PARAM_STR);
            }

            $statement->execute();
        }
    }
}

if (!function_exists('auditoriaFetchDetallesByAuditoriaIds')) {
    /**
     * @param array<int, int> $auditoriaIds
     * @return array<int, array<int, array{campo: string, campo_label: string, valor_anterior: ?string, valor_nuevo: ?string}>>
     */
    function auditoriaFetchDetallesByAuditoriaIds(array $auditoriaIds): array
    {
        $ids = [];

        foreach ($auditoriaIds as $id) {
            $normalized = auditoriaNormalizePositiveInt($id);

            if ($normalized !== null) {
                $ids[$normalized] = $normalized;
            }
        }

        if ($ids === []) {
            return [];
        }

        try {
            $pdo = Database::getConnection();

            if (!auditoriaEnsureDetalleTable($pdo)) {
                return [];
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $statement = $pdo->prepare(
                'SELECT auditoria_id, campo, campo_label, valor_anterior, valor_nuevo '
                . 'FROM auditoria_detalle '
                . 'WHERE auditoria_id IN (' . $placeholders . ') '
                . 'ORDER BY auditoria_id ASC, id ASC'
            );

            $index = 1;
            foreach ($ids as $id) {
                $statement->bindValue($index, $id, PDO::PARAM_INT);
                $index++;
            }

            $statement->execute();

            /** @var array<int, array<string, mixed>> $rows */
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            $grouped = [];

            foreach ($rows as $row) {
                $auditoriaId = auditoriaNormalizePositiveInt($row['auditoria_id'] ?? null);

                if ($auditoriaId === null) {
                    continue;
                }

                $valorAnterior = isset($row['valor_anterior']) ? trim((string) $row['valor_anterior']) : '';
                $valorNuevo = isset($row['valor_nuevo']) ? trim((string) $row['valor_nuevo']) : '';

                $grouped[$auditoriaId][] = [
                    'campo' => isset($row['campo']) ? (string) $row['campo'] : '',
                    'campo_label' => isset($row['campo_label']) ? (string) $row['campo_label'] : '',
                    'valor_anterior' => $valorAnterior !== '' ? $valorAnterior : null,
                    'valor_nuevo' => $valorNuevo !== '' ? $valorNuevo : null,
                ];
            }

            return $grouped;
        } catch (\Throwable) {
            return [];
        }
    }
}
