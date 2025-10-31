<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresadocumentotipo;

require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_list.php';
require_once __DIR__ . '/../../model/empresadocumentotipo/EmpresaDocumentoTipoListModel.php';

use PDOException;
use Residencia\Model\Empresadocumentotipo\EmpresaDocumentoTipoListModel;
use RuntimeException;

use function empresaDocumentoTipoListBuildStats;
use function empresaDocumentoTipoListDecorateCustomDocuments;
use function empresaDocumentoTipoListDecorateEmpresa;
use function empresaDocumentoTipoListDecorateGlobalDocuments;
use function empresaDocumentoTipoListDefaults;
use function empresaDocumentoTipoListInputErrorMessage;
use function empresaDocumentoTipoListNormalizeId;
use function empresaDocumentoTipoListNotFoundMessage;

class EmpresaDocumentoTipoListController
{
    private EmpresaDocumentoTipoListModel $model;

    public function __construct(?EmpresaDocumentoTipoListModel $model = null)
    {
        $this->model = $model ?? new EmpresaDocumentoTipoListModel();
    }

    /**
     * @param array<string, mixed> $input
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     globalDocuments: array<int, array<string, mixed>>,
     *     customDocuments: array<int, array<string, mixed>>,
     *     stats: array<string, int>,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    public function handle(array $input): array
    {
        $viewData = empresaDocumentoTipoListDefaults();

        $empresaId = empresaDocumentoTipoListNormalizeId($input['id_empresa'] ?? $input['id'] ?? null);

        if ($empresaId === null) {
            $viewData['inputError'] = empresaDocumentoTipoListInputErrorMessage();

            return $viewData;
        }

        $viewData['empresaId'] = $empresaId;

        $empresa = $this->getEmpresa($empresaId);

        if ($empresa === null) {
            $viewData['notFoundMessage'] = empresaDocumentoTipoListNotFoundMessage($empresaId);

            return $viewData;
        }

        $empresaDecorated = empresaDocumentoTipoListDecorateEmpresa($empresa);
        $tipoEmpresa = $empresaDecorated['tipo_empresa_inferido'] ?? null;

        if (!is_string($tipoEmpresa) || trim($tipoEmpresa) === '') {
            $tipoEmpresa = null;
        }

        $globalRows = $this->getGlobalDocumentos($empresaId, $tipoEmpresa);
        $customRows = $this->getCustomDocumentos($empresaId);

        $globalDocuments = empresaDocumentoTipoListDecorateGlobalDocuments($globalRows);
        $customDocuments = empresaDocumentoTipoListDecorateCustomDocuments($customRows);

        $viewData['empresa'] = $empresaDecorated;
        $viewData['globalDocuments'] = $globalDocuments;
        $viewData['customDocuments'] = $customDocuments;
        $viewData['stats'] = empresaDocumentoTipoListBuildStats($globalDocuments, $customDocuments);

        return $viewData;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEmpresa(int $empresaId): ?array
    {
        try {
            return $this->model->findEmpresaById($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la empresa solicitada.', 0, $exception);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getGlobalDocumentos(int $empresaId, ?string $tipoEmpresa): array
    {
        try {
            return $this->model->fetchGlobalDocumentos($empresaId, $tipoEmpresa);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los documentos globales de la empresa.', 0, $exception);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCustomDocumentos(int $empresaId): array
    {
        try {
            return $this->model->fetchCustomDocumentos($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los documentos individuales de la empresa.', 0, $exception);
        }
    }
}
