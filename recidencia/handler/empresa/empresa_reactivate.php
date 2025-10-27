<?php

declare(strict_types=1);

require_once __DIR__ . '/../../controller/empresa/EmpresaStatusController.php';
require_once __DIR__ . '/../../common/auth.php';

use Residencia\Controller\Empresa\EmpresaStatusController;

$user = $residenciaAuthUser;

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    header('Location: ../../view/empresa/empresa_list.php?error=invalid_id');
    exit;
}

try {
    $controller = new EmpresaStatusController();
    $controller->reactivateEmpresa(
        $id,
        (int) ($user['id'] ?? 0),
        'Reactivación manual desde listado de empresas'
    );

    header('Location: ../../view/empresa/empresa_list.php?success=reactivated');
    exit;
} catch (RuntimeException $exception) {
    $msg = urlencode($exception->getMessage());
    header("Location: ../../view/empresa/empresa_list.php?error={$msg}");
    exit;
} catch (Throwable $throwable) {
    header('Location: ../../view/empresa/empresa_list.php?error=unexpected');
    exit;
}

