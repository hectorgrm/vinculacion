<?php

declare(strict_types=1);

namespace Residencia\Controller\Documento;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/documento/DocumentoListModel.php';
require_once __DIR__ . '/../../common/functions/documento/documentofunctions_list.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\Documento\DocumentoListModel;

class DocumentoListController
{
    private DocumentoListModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new DocumentoListModel($pdo);
    }

    /**
     * @param array<string, mixed> $query
     * @return array{
     *     q: string,
     *     empresa: string,
     *     tipo: string,
     *     estatus: string,
     *     documentos: array<int, array<string, mixed>>,
     *     empresas: array<int, array<string, mixed>>,
     *     tipos: array<int, array<string, mixed>>,
     *     statusOptions: array<string, string>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $query): array
    {
        $search = documentoNormalizeSearch($query['q'] ?? '');
        $empresaId = documentoNormalizePositiveInt($query['empresa'] ?? null);
        $tipoId = documentoNormalizePositiveInt($query['tipo'] ?? null);
        $estatus = documentoNormalizeStatus($query['estatus'] ?? null);

        $documentos = [];
        $errorMessage = null;

        try {
            $documentos = $this->model->fetchDocuments(
                $search !== '' ? $search : null,
                $empresaId,
                $tipoId,
                $estatus
            );
        } catch (\Throwable $throwable) {
            $message = trim($throwable->getMessage());
            $errorMessage = $message !== ''
                ? $message
                : 'No se pudo obtener la lista de documentos. Intenta nuevamente mas tarde.';
        }

        try {
            $empresas = $this->model->fetchEmpresas();
        } catch (\Throwable) {
            $empresas = [];
        }

        try {
            $tipos = $this->model->fetchTipos();
        } catch (\Throwable) {
            $tipos = [];
        }

        return [
            'q' => $search,
            'empresa' => $empresaId !== null ? (string) $empresaId : '',
            'tipo' => $tipoId !== null ? (string) $tipoId : '',
            'estatus' => $estatus ?? '',
            'documentos' => $documentos,
            'empresas' => $empresas,
            'tipos' => $tipos,
            'statusOptions' => documentoStatusOptions(),
            'errorMessage' => $errorMessage,
        ];
    }
}
