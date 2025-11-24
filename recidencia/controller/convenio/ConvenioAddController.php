<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_add.php';
require_once __DIR__ . '/../../model/convenio/ConvenioAddModel.php';

use Residencia\Model\Convenio\ConvenioAddModel;
use PDOException;
use RuntimeException;
use Throwable;

class ConvenioAddController
{
    private ConvenioAddModel $model;

    public function __construct(?ConvenioAddModel $model = null)
    {
        $this->model = $model ?? new ConvenioAddModel();
    }

    public function empresaTieneConvenioActivo(int $empresaId): bool
    {
        try {
            return $this->model->empresaHasActiveConvenio($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo validar los convenios activos de la empresa.', 0, $exception);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmpresasForSelect(): array
    {
        try {
            return $this->model->getEmpresasForSelect();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las empresas disponibles.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createConvenio(array $data): int
    {
        try {
            return $this->model->createConvenio($data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo registrar el convenio.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $post
     * @param array<string, mixed> $files
     * @param array<string, mixed> $server
     * @return array{
     *     estatusOptions: array<int, string>,
     *     empresaOptions: array<int, array<string, mixed>>,
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     controllerAvailable: bool,
     *     empresaLockedId: ?string,
     *     createdConvenioId: ?int,
     *     createdEmpresaId: ?int
     * }
     */
    public function handle(array $post, array $files, array $server, array $query = []): array
    {
        $estatusOptions = convenioStatusOptions();
        $formData = convenioFormDefaults();
        $errors = [];
        $successMessage = null;
        $controllerError = null;
        $empresaOptions = [];
        $empresaLockedId = null;

        try {
            $empresaOptions = $this->getEmpresasForSelect();
        } catch (Throwable $throwable) {
            $message = trim($throwable->getMessage());
            $controllerError = $message !== ''
                ? $message
                : 'No se pudieron obtener las empresas disponibles.';
        }

        if ($controllerError === null) {
            $empresaLockedId = $this->resolveEmpresaFromQuery($query, $empresaOptions);

            if ($empresaLockedId !== null) {
                $formData['empresa_id'] = $empresaLockedId;
            }
        }

        $controllerAvailable = $controllerError === null;
        $method = isset($server['REQUEST_METHOD'])
            ? strtoupper((string) $server['REQUEST_METHOD'])
            : 'GET';

        $createdConvenioId = null;
        $createdEmpresaId = null;

        if ($method === 'POST') {
            if (!$controllerAvailable) {
                $errors[] = $controllerError
                    ?? 'No se pudo procesar la solicitud. Intenta nuevamente más tarde.';
            } else {
                if ($empresaLockedId !== null) {
                    $post['empresa_id'] = $empresaLockedId;
                }

                $handleResult = convenioHandleAddRequest(
                    $this,
                    $post,
                    $files,
                    convenioUploadsAbsoluteDir(),
                    convenioUploadsRelativeDir()
                );

                $formData = $handleResult['formData'];
                $errors = array_merge($errors, $handleResult['errors']);
                $successMessage = $handleResult['successMessage'];
                $createdConvenioId = $handleResult['createdConvenioId'];
                $createdEmpresaId = $handleResult['createdEmpresaId'];

                if ($empresaLockedId !== null) {
                    $formData['empresa_id'] = $empresaLockedId;
                }
            }
        }

        if ($errors !== []) {
            $errors = array_values(array_unique($errors));
        }

        return [
            'estatusOptions' => $estatusOptions,
            'empresaOptions' => $empresaOptions,
            'formData' => $formData,
            'errors' => $errors,
            'successMessage' => $successMessage,
            'controllerError' => $controllerError,
            'controllerAvailable' => $controllerAvailable,
            'empresaLockedId' => $empresaLockedId,
            'createdConvenioId' => $createdConvenioId,
            'createdEmpresaId' => $createdEmpresaId,
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $empresaOptions
     */
    private function resolveEmpresaFromQuery(array $query, array $empresaOptions): ?string
    {
        if (!isset($query['empresa'])) {
            return null;
        }

        $raw = $query['empresa'];

        if (!is_scalar($raw)) {
            return null;
        }

        $value = trim((string) $raw);

        if ($value === '') {
            return null;
        }

        $normalized = preg_replace('/[^0-9]/', '', $value);

        if ($normalized === null || $normalized === '') {
            return null;
        }

        foreach ($empresaOptions as $empresa) {
            $empresaId = isset($empresa['id']) ? (string) $empresa['id'] : '';

            if ($empresaId === $normalized) {
                return $normalized;
            }
        }

        return null;
    }
}
