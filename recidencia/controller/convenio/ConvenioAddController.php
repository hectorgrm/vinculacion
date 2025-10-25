<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_add.php';
require_once __DIR__ . '/../../model/convenio/ConvenioAddModel.php';

use Residencia\Model\Convenio\ConvenioAddModel;
use Throwable;

class ConvenioAddController
{
    private ConvenioAddModel $model;

    public function __construct(?ConvenioAddModel $model = null)
    {
        $this->model = $model ?? new ConvenioAddModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmpresasForSelect(): array
    {
        return $this->model->getEmpresasForSelect();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createConvenio(array $data): int
    {
        return $this->model->createConvenio($data);
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
     *     controllerAvailable: bool
     * }
     */
    public function handle(array $post, array $files, array $server): array
    {
        $estatusOptions = convenioStatusOptions();
        $formData = convenioFormDefaults();
        $errors = [];
        $successMessage = null;
        $controllerError = null;
        $empresaOptions = [];

        try {
            $empresaOptions = $this->getEmpresasForSelect();
        } catch (Throwable $throwable) {
            $message = trim($throwable->getMessage());
            $controllerError = $message !== ''
                ? $message
                : 'No se pudieron obtener las empresas disponibles.';
        }

        $controllerAvailable = $controllerError === null;
        $method = isset($server['REQUEST_METHOD'])
            ? strtoupper((string) $server['REQUEST_METHOD'])
            : 'GET';

        if ($method === 'POST') {
            if (!$controllerAvailable) {
                $errors[] = $controllerError
                    ?? 'No se pudo procesar la solicitud. Intenta nuevamente más tarde.';
            } else {
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
        ];
    }
}
