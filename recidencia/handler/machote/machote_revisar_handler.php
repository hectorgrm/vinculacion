<?php
declare(strict_types=1);

use Residencia\Controller\Machote\MachoteRevisarController;

require_once __DIR__ . '/../../common/auth.php';
require_once __DIR__ . '/../../controller/machote/MachoteRevisarController.php';

$machote      = [];
$comentarios  = [];
$empresa      = [];
$convenio     = [];
$progreso     = 0;
$estado       = 'En revisión';
$errorMessage = null;
$totales      = [
    'total'      => 0,
    'resueltos'  => 0,
    'pendientes' => 0,
    'progreso'   => 0,
    'estado'     => 'En revisión',
];

try {
    $machoteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($machoteId === false || $machoteId === null) {
        throw new \RuntimeException('ID de machote inválido o no especificado.');
    }

    $controller = new MachoteRevisarController();
    $data = $controller->handle($machoteId);

    $machote     = $data['machote'] ?? [];
    $comentarios = $data['comentarios'] ?? [];
    $empresa     = $data['empresa'] ?? [];
    $convenio    = $data['convenio'] ?? [];
    $progreso    = (int) ($data['progreso'] ?? 0);
    $estado      = (string) ($data['estado'] ?? 'En revisión');
    $totales     = $data['totales'] ?? $totales;
} catch (\Throwable $exception) {
    $errorMessage = $exception->getMessage();
}

$currentUser = $user ?? null;
