<?php

declare(strict_types=1);

namespace Residencia\Controller\DocumentoTipo;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/documentotipo/DocumentoTipoListModel.php';
require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_funtions_list.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\DocumentoTipo\DocumentoTipoListModel;

class DocumentoTipoListController
{
    private DocumentoTipoListModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new DocumentoTipoListModel($pdo);
    }

    /**
     * @param array<string, mixed> $query
     * @return array{
     *     q: string,
     *     tipo_empresa: string,
     *     tipos: array<int, array<string, mixed>>,
     *     tipoEmpresaOptions: array<string, string>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $query): array
    {
        $search = documentoTipoNormalizeSearch($query['q'] ?? '');
        $tipoEmpresa = documentoTipoNormalizeTipoEmpresa($query['tipo_empresa'] ?? null);

        $tipos = [];
        $errorMessage = null;

        try {
            $tipos = $this->model->fetchTipos(
                $search !== '' ? $search : null,
                $tipoEmpresa
            );
        } catch (\Throwable $throwable) {
            $message = trim($throwable->getMessage());
            $errorMessage = $message !== ''
                ? $message
                : 'No se pudo obtener la lista de tipos de documentos. Intenta nuevamente mas tarde.';
        }

        return [
            'q' => $search,
            'tipo_empresa' => $tipoEmpresa ?? '',
            'tipos' => $tipos,
            'tipoEmpresaOptions' => documentoTipoEmpresaOptions(),
            'errorMessage' => $errorMessage,
        ];
    }
}
