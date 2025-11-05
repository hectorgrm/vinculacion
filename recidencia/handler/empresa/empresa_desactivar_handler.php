<?php
declare(strict_types=1);

// ============================
// 🔒 Controlador y autenticación
// ============================
require_once __DIR__ . '/../../controller/empresa/EmpresaStatusController.php';
require_once __DIR__ . '/../../common/auth.php';

use Residencia\Controller\Empresa\EmpresaStatusController;

// Usuario autenticado disponible desde auth.php
$user = $residenciaAuthUser;

// ============================
// 📩 Validar ID recibido por POST
// ============================
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    header('Location: ../../view/empresa/empresa_list.php?error=invalid_id');
    exit;
}

// ============================
// ⚙️ Ejecutar desactivación
// ============================
try {
    $controller = new EmpresaStatusController();

    // Desactivar en cascada
    $controller->disableEmpresa(
        $id,
        (int)($user['id'] ?? 0),
        'Desactivación manual desde listado de empresas'
    );

    // Redirigir con mensaje de éxito
    header('Location: ../../view/empresa/empresa_list.php?success=disabled');
    exit;

} catch (RuntimeException $e) {
    // Error controlado (problemas de base de datos, etc.)
    $msg = urlencode($e->getMessage());
    header("Location: ../../view/empresa/empresa_list.php?error={$msg}");
    exit;

} catch (Throwable $t) {
    // Error inesperado
    header('Location: ../../view/empresa/empresa_list.php?error=unexpected');
    exit;
}
