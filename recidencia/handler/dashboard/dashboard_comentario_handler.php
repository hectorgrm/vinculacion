<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_comentario.php';
require_once __DIR__ . '/../../controller/dashboard/DashboardComentarioController.php';

use Residencia\Controller\Dashboard\DashboardComentarioController;

if (!function_exists('dashboardComentarioHandler')) {
    /**
     * @return array{
     *     comentariosStats: array{total: int, abiertos: int, resueltos: int},
     *     comentariosRevision: array<int, array<string, mixed>>,
     *     comentariosError: ?string
     * }
     */
    function dashboardComentarioHandler(): array
    {
        $defaults = dashboardComentarioDefaults();

        try {
            $controller = new DashboardComentarioController();
            $data = $controller->handle();

            if (!is_array($data)) {
                return $defaults;
            }

            return array_merge($defaults, $data);
        } catch (Throwable $exception) {
            $defaults['comentariosError'] = dashboardComentarioErrorMessage($exception->getMessage());

            return $defaults;
        }
    }
}

$dashboardComentarios = dashboardComentarioHandler();
$comentariosStats = $dashboardComentarios['comentariosStats'];
$comentariosRevision = $dashboardComentarios['comentariosRevision'];
$comentariosError = $dashboardComentarios['comentariosError'];

return $dashboardComentarios;
