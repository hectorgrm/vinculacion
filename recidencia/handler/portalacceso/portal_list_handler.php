<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_list_functions.php';
require_once __DIR__ . '/../../controller/portalacceso/PortalListController.php';

use Residencia\Controller\PortalAcceso\PortalListController;

if (!function_exists('portalAccessListHandler')) {
    /**
     * @return array{
     *     search: string,
     *     status: string,
     *     statusOptions: array<string, string>,
     *     portales: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    function portalAccessListHandler(): array
    {
        $defaults = portalAccessListDefaults();

        try {
            $controller = new PortalListController();
            $viewData = $controller->handle($_GET);

            if (!is_array($viewData)) {
                return $defaults;
            }

            return array_merge($defaults, $viewData);
        } catch (Throwable $exception) {
            $defaults['errorMessage'] = portalAccessListErrorMessage($exception->getMessage());

            return $defaults;
        }
    }
}

$handlerResult = portalAccessListHandler();

$search = $handlerResult['search'];
$status = $handlerResult['status'];
$statusOptions = $handlerResult['statusOptions'];
$portales = $handlerResult['portales'];
$errorMessage = $handlerResult['errorMessage'];

return $handlerResult;
