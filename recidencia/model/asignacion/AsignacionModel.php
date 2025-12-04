<?php

declare(strict_types=1);

namespace Residencia\Model\Asignacion;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use RuntimeException;

/**
 * Reglas de negocio para rp_asignacion.
 *
 * - Portal empresa: no puede crear asignaciones si la empresa esta Inactiva.
 * - Admin: puede cancelar asignaciones aunque la empresa este Inactiva,
 *   excepto cuando la asignacion ya esta concluida.
 * - Historial: siempre disponible para consulta.
 */
class AsignacionModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @throws RuntimeException cuando la empresa no existe o esta Inactiva.
     */
    private function assertEmpresaOperativa(int $empresaId): void
    {
        $statement = $this->pdo->prepare('SELECT estatus FROM rp_empresa WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $empresaId]);

        $estatus = $statement->fetchColumn();

        if ($estatus === false) {
            throw new RuntimeException('Empresa no encontrada');
        }

        if (strcasecmp((string) $estatus, 'Inactiva') === 0) {
            throw new RuntimeException('Empresa no operativa');
        }
    }

    /**
     * Crear asignacion desde el portal de empresa.
     * Bloquea cuando la empresa esta Inactiva.
     *
     * @param array<string, mixed> $payload
     * @return int ID de la nueva asignacion.
     */
    public function crearDesdePortal(array $payload): int
    {
        $empresaId = (int) ($payload['empresa_id'] ?? 0);
        $estudianteId = (int) ($payload['estudiante_id'] ?? 0);
        $periodoId = (int) ($payload['periodo_id'] ?? 0);
        $proyecto = trim((string) ($payload['proyecto'] ?? ''));
        $fechaInicio = $payload['fecha_inicio'] ?? null;
        $fechaFin = $payload['fecha_fin'] ?? null;

        if ($empresaId <= 0 || $estudianteId <= 0 || $periodoId <= 0 || $proyecto === '') {
            throw new RuntimeException('Datos incompletos para registrar la asignacion.');
        }

        $this->assertEmpresaOperativa($empresaId);

        $estatusSolicitado = (string) ($payload['estatus'] ?? 'en_curso');
        $estatus = in_array($estatusSolicitado, ['en_curso', 'concluido', 'cancelado'], true)
            ? $estatusSolicitado
            : 'en_curso';

        $statement = $this->pdo->prepare(
            'INSERT INTO rp_asignacion (
                empresa_id,
                estudiante_id,
                periodo_id,
                proyecto,
                estatus,
                fecha_inicio,
                fecha_fin
            ) VALUES (
                :empresa_id,
                :estudiante_id,
                :periodo_id,
                :proyecto,
                :estatus,
                :fecha_inicio,
                :fecha_fin
            )'
        );

        $statement->execute([
            ':empresa_id' => $empresaId,
            ':estudiante_id' => $estudianteId,
            ':periodo_id' => $periodoId,
            ':proyecto' => $proyecto,
            ':estatus' => $estatus,
            ':fecha_inicio' => $fechaInicio,
            ':fecha_fin' => $fechaFin,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Cancelar una asignacion desde administracion.
     * Permite cancelar aun si la empresa esta Inactiva,
     * pero bloquea si la asignacion ya esta concluida.
     */
    public function cancelarComoAdmin(int $asignacionId): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT estatus
               FROM rp_asignacion
              WHERE id = :id
              LIMIT 1'
        );
        $statement->execute([':id' => $asignacionId]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        if ($record === false) {
            throw new RuntimeException('Asignacion no encontrada.');
        }

        $estatusActual = (string) $record['estatus'];

        if (strcasecmp($estatusActual, 'concluido') === 0) {
            throw new RuntimeException('No se puede cancelar una asignacion concluida.');
        }

        if (strcasecmp($estatusActual, 'cancelado') === 0) {
            return false;
        }

        $update = $this->pdo->prepare(
            'UPDATE rp_asignacion
                SET estatus = :estatus
              WHERE id = :id'
        );
        $update->execute([
            ':estatus' => 'cancelado',
            ':id' => $asignacionId,
        ]);

        return $update->rowCount() > 0;
    }

    /**
     * Historial completo por empresa, disponible aun si la empresa esta Inactiva.
     *
     * @return array<int, array<string, mixed>>
     */
    public function historialPorEmpresa(int $empresaId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id,
                    empresa_id,
                    estudiante_id,
                    periodo_id,
                    proyecto,
                    estatus,
                    fecha_inicio,
                    fecha_fin,
                    fecha_registro
               FROM rp_asignacion
              WHERE empresa_id = :empresa_id
              ORDER BY fecha_registro DESC, id DESC'
        );
        $statement->execute([':empresa_id' => $empresaId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
