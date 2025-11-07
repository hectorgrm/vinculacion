<?php
declare(strict_types=1);

namespace Residencia\Model\Estudiante;

use PDO;

class EstudianteListModel
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function obtenerEmpresas(): array
    {
        $sql = 'SELECT id, nombre FROM rp_empresa ORDER BY nombre ASC';
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function obtenerConvenios(): array
    {
        $sql = 'SELECT id, folio, empresa_id FROM rp_convenio ORDER BY id DESC';
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function obtenerEstudiantes(?int $empresaId, ?int $convenioId): array
    {
        $sql = [
            'SELECT',
            "    e.id,",
            "    e.nombre,",
            "    e.apellido_paterno,",
            "    e.apellido_materno,",
            "    e.matricula,",
            "    e.carrera,",
            "    e.estatus,",
            "    emp.nombre AS empresa_nombre,",
            "    c.folio AS convenio_folio",
            'FROM rp_estudiante e',
            'INNER JOIN rp_empresa emp ON emp.id = e.empresa_id',
            'LEFT JOIN rp_convenio c ON c.id = e.convenio_id',
            'WHERE 1 = 1',
        ];

        $params = [];

        if ($empresaId !== null) {
            $sql[] = 'AND e.empresa_id = :empresa_id';
            $params[':empresa_id'] = $empresaId;
        }

        if ($convenioId !== null) {
            $sql[] = 'AND e.convenio_id = :convenio_id';
            $params[':convenio_id'] = $convenioId;
        }

        $sql[] = 'ORDER BY e.creado_en DESC';

        $stmt = $this->pdo->prepare(implode(PHP_EOL, $sql));
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
