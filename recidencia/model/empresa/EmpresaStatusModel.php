<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/helpers/convenio/empresa_function_helper.php';

use Common\Model\Database;
use PDO;
use PDOException;

class EmpresaStatusModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function disableWithCascade(int $empresaId, ?int $byUserId = null, ?string $reason = null): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sqlEmpresa = <<<'SQL'
                UPDATE rp_empresa
                   SET estatus = 'Inactiva',
                       actualizado_en = NOW()
                 WHERE id = :id
            SQL;
            $statementEmpresa = $this->pdo->prepare($sqlEmpresa);
            $statementEmpresa->execute([':id' => $empresaId]);

            $sqlConvenios = <<<'SQL'
                UPDATE rp_convenio
                   SET estatus = 'Inactiva'
                 WHERE empresa_id = :id
            SQL;
            $statementConvenios = $this->pdo->prepare($sqlConvenios);
            $statementConvenios->execute([':id' => $empresaId]);

            $sqlDocumentos = <<<'SQL'
                UPDATE rp_empresa_doc
                   SET estatus = CASE
                       WHEN estatus IN ('aprobado', 'pendiente', 'revision') THEN 'revision'
                       ELSE estatus
                   END
                 WHERE empresa_id = :id
            SQL;
            $statementDocumentos = $this->pdo->prepare($sqlDocumentos);
            $statementDocumentos->execute([':id' => $empresaId]);

            $sqlPortal = <<<'SQL'
                UPDATE rp_portal_acceso
                   SET activo = 0
                 WHERE empresa_id = :id
            SQL;
            $statementPortal = $this->pdo->prepare($sqlPortal);
            $statementPortal->execute([':id' => $empresaId]);

            $sqlMachoteRevision = <<<'SQL'
                UPDATE rp_machote_revision
                   SET estado = 'cancelado'
                 WHERE empresa_id = :id
            SQL;
            $this->pdo->prepare($sqlMachoteRevision)->execute([':id' => $empresaId]);

            $sqlAsignaciones = <<<'SQL'
                UPDATE rp_asignacion
                   SET estatus = 'cancelado'
                 WHERE empresa_id = :id
                   AND estatus != 'concluido'
            SQL;
            $this->pdo->prepare($sqlAsignaciones)->execute([':id' => $empresaId]);

            $this->pdo->commit();

            $conveniosActualizados = $statementConvenios->rowCount();
            $documentosActualizados = $statementDocumentos->rowCount();
            $accesosActualizados = $statementPortal->rowCount();

            empresaRegistrarEventoDesactivacion(
                $empresaId,
                $byUserId,
                $conveniosActualizados,
                $documentosActualizados,
                $accesosActualizados
            );

            return true;
        } catch (PDOException $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }

    public function reactivateWithCascade(int $empresaId, ?int $byUserId = null, ?string $reason = null): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sqlEmpresa = <<<'SQL'
                UPDATE rp_empresa
                   SET estatus = 'En revisión',
                       actualizado_en = NOW()
                 WHERE id = :id
            SQL;
            $statementEmpresa = $this->pdo->prepare($sqlEmpresa);
            $statementEmpresa->execute([':id' => $empresaId]);

            $sqlConvenios = <<<'SQL'
                UPDATE rp_convenio
                   SET estatus = CASE
                       WHEN estatus = 'Inactiva' THEN 'En revisión'
                       ELSE estatus
                   END
                 WHERE empresa_id = :id
            SQL;
            $statementConvenios = $this->pdo->prepare($sqlConvenios);
            $statementConvenios->execute([':id' => $empresaId]);

            $sqlPortal = <<<'SQL'
                UPDATE rp_portal_acceso
                   SET activo = 1
                 WHERE empresa_id = :id
            SQL;
            $statementPortal = $this->pdo->prepare($sqlPortal);
            $statementPortal->execute([':id' => $empresaId]);

            $sqlMachoteRevision = <<<'SQL'
                UPDATE rp_machote_revision
                   SET estado = 'en_revision'
                 WHERE empresa_id = :id
                   AND estado = 'cancelado'
            SQL;
            $this->pdo->prepare($sqlMachoteRevision)->execute([':id' => $empresaId]);

            $sqlAsignaciones = <<<'SQL'
                UPDATE rp_asignacion
                   SET estatus = 'en_curso'
                 WHERE empresa_id = :id
                   AND estatus = 'cancelado'
            SQL;
            $this->pdo->prepare($sqlAsignaciones)->execute([':id' => $empresaId]);

            $this->pdo->commit();

            $conveniosActualizados = $statementConvenios->rowCount();
            $accesosActualizados = $statementPortal->rowCount();

            empresaRegistrarEventoReactivacion(
                $empresaId,
                $byUserId,
                $conveniosActualizados,
                $accesosActualizados
            );

            return true;
        } catch (PDOException $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }
}
