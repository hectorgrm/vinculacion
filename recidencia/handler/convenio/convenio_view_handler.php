<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioViewController.php';
require_once __DIR__ . '/convenio_auditoria_handler.php';

use Residencia\Controller\Convenio\ConvenioViewController;

if (!function_exists('convenioViewDefaults')) {
    /**
     * @return array{
     *     convenioId: ?int,
     *     convenio: ?array<string, mixed>,
     *     machoteObservaciones: array<int, array<string, mixed>>,
     *     historial: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string
     * }
     */
    function convenioViewDefaults(): array
    {
        return [
            'convenioId' => null,
            'convenio' => null,
            'machoteObservaciones' => [],
            'historial' => [],
            'controllerError' => null,
            'notFoundMessage' => null,
            'inputError' => null,
        ];
    }
}

if (!function_exists('convenioViewControllerErrorMessage')) {
    function convenioViewControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== '' ? $message : 'Ocurrio un error al cargar el convenio. Intenta nuevamente.';
    }
}

if (!function_exists('convenioViewHandler')) {
    /**
     * @return array{
     *     convenioId: ?int,
     *     convenio: ?array<string, mixed>,
     *     machoteObservaciones: array<int, array<string, mixed>>,
     *     historial: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string
     * }
     */
    function convenioViewHandler(): array
    {
        $viewData = convenioViewDefaults();

        try {
            $controller = new ConvenioViewController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = convenioViewControllerErrorMessage($exception);

            return $viewData;
        }

        try {
            $result = $controller->handle($_GET);
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = convenioViewControllerErrorMessage($exception);

            return $viewData;
        }

        if (!is_array($result)) {
            return $viewData;
        }

        $viewData = array_merge($viewData, $result);

        $convenioId = isset($viewData['convenioId']) && is_int($viewData['convenioId'])
            ? $viewData['convenioId']
            : null;
        $convenio = $viewData['convenio'] ?? null;

        if ($convenioId !== null && is_array($convenio)) {
            $empresaId = isset($convenio['empresa_id']) ? (int) $convenio['empresa_id'] : null;
            $auditoriaResult = convenioAuditoriaFetchHistorial($convenioId, $empresaId);
            $viewData['historial'] = $auditoriaResult['historial'];
        }

        return $viewData;
    }
}

return convenioViewHandler();
