<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/machote/MachoteListController.php';

use Residencia\Controller\Machote\MachoteListController;

$controller = new MachoteListController();
$data = $controller->handle($_GET);

// Variables accesibles desde la vista
$machotes = $data['machotes'];
$search = $data['search'];
$error = $data['error'];
