<?php

declare(strict_types=1);

namespace Residencia\Model\Convenio;

require_once __DIR__ . '/../ConvenioModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\ConvenioModel;
use Throwable;

class ConvenioModle extends ConvenioModel
{
    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        parent::__construct($pdo);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findForRenewal(int $convenioId): ?array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   c.empresa_id,
                   c.tipo_convenio,
                   c.estatus,
                   c.observaciones,
                   c.fecha_inicio,
                   c.fecha_fin,
                   c.responsable_academico,
                   c.folio,
                   c.renovado_de,
                   e.nombre AS empresa_nombre,
                   e.numero_control AS empresa_numero_control
              FROM rp_convenio AS c
              JOIN rp_empresa AS e ON e.id = c.empresa_id
             WHERE c.id = :id
             LIMIT 1
        SQL;

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':id' => $convenioId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createRenewal(array $data): int
    {
        $pdo = $this->getConnection();
        $shouldManageTransaction = !$pdo->inTransaction();

        if ($shouldManageTransaction) {
            $pdo->beginTransaction();
        }

        try {
            $sql = <<<'SQL'
                INSERT INTO rp_convenio (
                    empresa_id,
                    renovado_de,
                    tipo_convenio,
                    estatus,
                    observaciones,
                    fecha_inicio,
                    fecha_fin,
                    responsable_academico,
                    folio,
                    borrador_path,
                    firmado_path
                ) VALUES (
                    :empresa_id,
                    :renovado_de,
                    :tipo_convenio,
                    :estatus,
                    :observaciones,
                    :fecha_inicio,
                    :fecha_fin,
                    :responsable_academico,
                    :folio,
                    :borrador_path,
                    :firmado_path
                )
            SQL;

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':empresa_id' => isset($data['empresa_id']) ? (int) $data['empresa_id'] : 0,
                ':renovado_de' => isset($data['renovado_de']) ? (int) $data['renovado_de'] : null,
                ':tipo_convenio' => $data['tipo_convenio'] ?? null,
                ':estatus' => $data['estatus'] ?? 'En revisión',
                ':observaciones' => $data['observaciones'] ?? null,
                ':fecha_inicio' => $data['fecha_inicio'] ?? null,
                ':fecha_fin' => $data['fecha_fin'] ?? null,
                ':responsable_academico' => $data['responsable_academico'] ?? null,
                ':folio' => $data['folio'] ?? null,
                ':borrador_path' => $data['borrador_path'] ?? null,
                ':firmado_path' => $data['firmado_path'] ?? null,
            ]);

            $newConvenioId = (int) $pdo->lastInsertId();

            if (isset($data['renovado_de']) && $data['renovado_de'] !== null) {
                $updateSql = <<<'SQL'
                    UPDATE rp_convenio
                       SET estatus = 'Inactiva'
                     WHERE id = :id
                SQL;

                $updateStatement = $pdo->prepare($updateSql);
                $updateStatement->execute([
                    ':id' => (int) $data['renovado_de'],
                ]);
            }

            if ($shouldManageTransaction) {
                $pdo->commit();
            }

            return $newConvenioId;
        } catch (Throwable $throwable) {
            if ($shouldManageTransaction && $pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $throwable;
        }
    }
}
