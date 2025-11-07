<?php
declare(strict_types=1);

use Residencia\Controller\Estudiante\EstudianteListController;
use Residencia\Model\Estudiante\EstudianteListModel;

/** @var \PDO $pdo */
$pdo = require_once dirname(__DIR__, 3) . '/common/model/db.php';
require_once dirname(__DIR__, 2) . '/common/helpers/estudiante/estudianteL_List_Helper.php';
require_once dirname(__DIR__, 2) . '/controller/estudiante/EstudianteListController.php';
require_once dirname(__DIR__, 2) . '/model/estudiante/EstudianteListModel.php';

$viewErrors = [];
$estudiantes = [];
$empresas = [];
$convenios = [];

$empresaSeleccionada = filter_input(
    INPUT_GET,
    'empresa_id',
    FILTER_VALIDATE_INT,
    ['options' => ['default' => null], 'flags' => FILTER_NULL_ON_FAILURE]
);
$convenioSeleccionado = filter_input(
    INPUT_GET,
    'convenio_id',
    FILTER_VALIDATE_INT,
    ['options' => ['default' => null], 'flags' => FILTER_NULL_ON_FAILURE]
);

try {
    $model = new EstudianteListModel($pdo);
    $controller = new EstudianteListController($model);
    $datos = $controller->obtenerDatos($empresaSeleccionada, $convenioSeleccionado);

    $estudiantes = array_map(
        static function (array $estudiante): array {
            $estatus = (string) ($estudiante['estatus'] ?? '');

            $estudiante['nombre_completo'] = estudiante_list_format_nombre($estudiante);
            $estudiante['estatus_badge_class'] = estudiante_list_badge_class($estatus);
            $estudiante['estatus_badge_label'] = estudiante_list_badge_label($estudiante['estatus'] ?? null);

            return $estudiante;
        },
        $datos['estudiantes']
    );

    $empresas = $datos['empresas'];
    $convenios = $datos['convenios'];
} catch (\Throwable $e) {
    $viewErrors[] = 'database_error';
    error_log('Error en estudiante_list_handler.php: ' . $e->getMessage());
}
