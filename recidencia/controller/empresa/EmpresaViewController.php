<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaViewModel.php';
require_once __DIR__ . '/../../common/functions/empresa/empresa_functions_view.php';

use PDOException;
use Residencia\Model\Empresa\EmpresaViewModel;
use RuntimeException;

use function empresaViewDefaults;
use function empresaViewDecorate;
use function empresaViewDecorateConvenios;
use function empresaViewInputErrorMessage;
use function empresaViewNormalizeId;
use function empresaViewNotFoundMessage;

class EmpresaViewController
{
    private EmpresaViewModel $model;

    public function __construct(?EmpresaViewModel $model = null)
    {
        $this->model = $model ?? new EmpresaViewModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEmpresa(int $empresaId): ?array
    {
        try {
            return $this->model->findById($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la informacion de la empresa.', 0, $exception);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getConveniosActivos(int $empresaId): array
    {
        try {
            return $this->model->findActiveConveniosByEmpresaId($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los convenios activos de la empresa.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $input
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     conveniosActivos: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string
     * }
     */
    public function handle(array $input): array
    {
        $viewData = empresaViewDefaults();

        $empresaId = empresaViewNormalizeId($input['id'] ?? null);

        if ($empresaId === null) {
            $viewData['inputError'] = empresaViewInputErrorMessage();

            return $viewData;
        }

        $viewData['empresaId'] = $empresaId;

        $empresa = $this->getEmpresa($empresaId);

        if ($empresa === null) {
            $viewData['notFoundMessage'] = empresaViewNotFoundMessage($empresaId);

            return $viewData;
        }

        $viewData['empresa'] = empresaViewDecorate($empresa);

        $convenios = $this->getConveniosActivos($empresaId);
        $viewData['conveniosActivos'] = empresaViewDecorateConvenios($convenios);

        return $viewData;
    }
}
