<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaViewModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   numero_control,
                   nombre,
                   logo_path,
                   rfc,
                   representante,
                   cargo_representante,
                   sector,
                   sitio_web,
                   contacto_nombre,
                   contacto_email,
                   telefono,
                   estado,
                   municipio,
                   cp,
                   direccion,
                   estatus,
                   regimen_fiscal,
                   notas,
                   creado_en,
                   actualizado_en
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * Obtiene los convenios de la empresa con estatus "Activa" o "En revisión".
     *
     * @return array<int, array<string, mixed>>
     */
    public function findActiveConveniosByEmpresaId(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   empresa_id,
                   tipo_convenio,
                   estatus,
                   observaciones,
                   fecha_inicio,
                   fecha_fin,
                   responsable_academico,
                   folio,
                   borrador_path,
                   firmado_path,
                   creado_en,
                   actualizado_en
              FROM rp_convenio
             WHERE empresa_id = :empresa_id
               AND estatus IN ('Activa', 'En revisión')
             ORDER BY fecha_fin DESC,
                      fecha_inicio DESC,
                      id DESC
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        /** @var array<int, array<string, mixed>> $records */
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findGlobalDocumentos(int $empresaId, ?string $tipoEmpresa = null): array
    {
        $sql = <<<'SQL'
            SELECT t.id AS tipo_id,
                   t.nombre AS tipo_nombre,
                   t.descripcion AS tipo_descripcion,
                   t.obligatorio AS tipo_obligatorio,
                   t.tipo_empresa,
                   doc.id AS documento_id,
                   doc.ruta AS documento_ruta,
                   doc.estatus AS documento_estatus,
                   doc.observacion AS documento_observacion,
                   doc.creado_en AS documento_creado_en,
                   doc.actualizado_en AS documento_actualizado_en
              FROM rp_documento_tipo AS t
              LEFT JOIN (
                    SELECT ranked.id,
                           ranked.empresa_id,
                           ranked.tipo_global_id,
                           ranked.ruta,
                           ranked.estatus,
                           ranked.observacion,
                           ranked.creado_en,
                           ranked.actualizado_en
                      FROM (
                            SELECT d.*,
                                   ROW_NUMBER() OVER (
                                       PARTITION BY d.tipo_global_id
                                       ORDER BY d.actualizado_en DESC, d.id DESC
                                   ) AS rn
                              FROM rp_empresa_doc AS d
                             WHERE d.empresa_id = :empresa_id
                               AND d.tipo_global_id IS NOT NULL
                         ) AS ranked
                     WHERE ranked.rn = 1
              ) AS doc ON doc.tipo_global_id = t.id
             WHERE t.activo = 1
        SQL;

        $params = [
            ':empresa_id' => $empresaId,
        ];

        if ($tipoEmpresa !== null) {
            $sql .= ' AND (t.tipo_empresa = :tipo_empresa OR t.tipo_empresa = \'ambas\')';
            $params[':tipo_empresa'] = $tipoEmpresa;
        }

        $sql .= ' ORDER BY t.obligatorio DESC, t.nombre ASC, t.id ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        /** @var array<int, array<string, mixed>> $records */
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findCustomDocumentos(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT tipo.id,
                   tipo.empresa_id,
                   tipo.nombre,
                   tipo.descripcion,
                   tipo.obligatorio,
                   tipo.creado_en,
                   doc.id AS documento_id,
                   doc.ruta AS documento_ruta,
                   doc.estatus AS documento_estatus,
                   doc.observacion AS documento_observacion,
                   doc.creado_en AS documento_creado_en,
                   doc.actualizado_en AS documento_actualizado_en
              FROM rp_documento_tipo_empresa AS tipo
              LEFT JOIN (
                    SELECT ranked.id,
                           ranked.empresa_id,
                           ranked.tipo_personalizado_id,
                           ranked.ruta,
                           ranked.estatus,
                           ranked.observacion,
                           ranked.creado_en,
                           ranked.actualizado_en
                      FROM (
                            SELECT d.*,
                                   ROW_NUMBER() OVER (
                                       PARTITION BY d.tipo_personalizado_id
                                       ORDER BY d.actualizado_en DESC, d.id DESC
                                   ) AS rn
                              FROM rp_empresa_doc AS d
                             WHERE d.empresa_id = :empresa_id
                               AND d.tipo_personalizado_id IS NOT NULL
                         ) AS ranked
                     WHERE ranked.rn = 1
              ) AS doc ON doc.tipo_personalizado_id = tipo.id
             WHERE tipo.empresa_id = :empresa_id
             ORDER BY tipo.obligatorio DESC, tipo.nombre ASC, tipo.id ASC
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        /** @var array<int, array<string, mixed>> $records */
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }

    /**
     * @return array{global: array<int, array<string, mixed>>, custom: array<int, array<string, mixed>>}
     */
    public function findDocumentosResumen(int $empresaId, ?string $tipoEmpresa = null): array
    {
        return [
            'global' => $this->findGlobalDocumentos($empresaId, $tipoEmpresa),
            'custom' => $this->findCustomDocumentos($empresaId),
        ];
    }

    /**
     * Obtiene el machote más reciente asociado a la empresa junto a sus métricas básicas.
     *
     * @return array<string, mixed>|null
     */
    public function findLatestMachoteResumen(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT cm.id,
                   cm.version_local,
                   cm.estatus,
                   cm.confirmacion_empresa,
                   cm.actualizado_en,
                   c.id AS convenio_id,
                   c.tipo_convenio
              FROM rp_convenio_machote AS cm
              INNER JOIN rp_convenio AS c ON c.id = cm.convenio_id
             WHERE c.empresa_id = :empresa_id
             ORDER BY cm.actualizado_en DESC, cm.id DESC
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        $machote = $statement->fetch(PDO::FETCH_ASSOC);

        if ($machote === false || !isset($machote['id'])) {
            return null;
        }

        $machoteId = (int) $machote['id'];
        $stats = $this->getMachoteComentariosStats($machoteId);

        return array_merge($machote, $stats);
    }

    /**
     * @return array{comentarios_total:int, comentarios_resueltos:int}
     */
    private function getMachoteComentariosStats(int $machoteId): array
    {
        $sql = <<<'SQL'
            SELECT COUNT(*) AS total,
                   SUM(CASE WHEN estatus = 'resuelto' THEN 1 ELSE 0 END) AS resueltos
              FROM rp_machote_comentario
             WHERE machote_id = :machote_id
               AND (respuesta_a IS NULL OR respuesta_a = 0)
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':machote_id' => $machoteId]);

        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'comentarios_total' => isset($row['total']) ? (int) $row['total'] : 0,
            'comentarios_resueltos' => isset($row['resueltos']) ? (int) $row['resueltos'] : 0,
        ];
    }
}
