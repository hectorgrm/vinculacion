<?php

declare(strict_types=1);

namespace Residencia\Model\Auditoria;

use Common\Model\Database;
use PDO;

class AuditoriaModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<int, array{campo: string, campo_label: string, valor_anterior: ?string, valor_nuevo: ?string}> $detalles
     */
    public function registrar(array $payload, array $detalles = []): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO auditoria (actor_tipo, actor_id, accion, entidad, entidad_id, ip) '
            . 'VALUES (:actor_tipo, :actor_id, :accion, :entidad, :entidad_id, :ip)'
        );

        $statement->bindValue(':actor_tipo', $payload['actor_tipo'], PDO::PARAM_STR);

        if ($payload['actor_id'] === null) {
            $statement->bindValue(':actor_id', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':actor_id', $payload['actor_id'], PDO::PARAM_INT);
        }

        $statement->bindValue(':accion', $payload['accion'], PDO::PARAM_STR);
        $statement->bindValue(':entidad', $payload['entidad'], PDO::PARAM_STR);
        $statement->bindValue(':entidad_id', $payload['entidad_id'], PDO::PARAM_INT);

        if ($payload['ip'] === null) {
            $statement->bindValue(':ip', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':ip', $payload['ip'], PDO::PARAM_STR);
        }

        $executed = $statement->execute();

        if (!$executed) {
            return false;
        }

        if ($detalles === []) {
            return true;
        }

        $auditoriaId = (int) $this->pdo->lastInsertId();

        if ($auditoriaId <= 0) {
            return false;
        }

        $this->insertDetalles($auditoriaId, $detalles);

        return true;
    }

    /**
     * @param array<int, array{campo: string, campo_label: string, valor_anterior: ?string, valor_nuevo: ?string}> $detalles
     */
    private function insertDetalles(int $auditoriaId, array $detalles): void
    {
        $this->ensureDetalleTable();

        $statement = $this->pdo->prepare(
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

    private function ensureDetalleTable(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS auditoria_detalle (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                auditoria_id BIGINT UNSIGNED NOT NULL,
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

        $this->pdo->exec($sql);
    }
}
