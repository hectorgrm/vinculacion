<?php
declare(strict_types=1);

// ============================
// 🔒 Controlador y autenticación
// ============================
require_once __DIR__ . '/../../controller/EmpresaController.php';
require_once __DIR__ . '/../../common/auth.php';

use Residencia\Controller\EmpresaController;

// ============================
// 🧍 Validar sesión y permisos
// ============================
$user = $_SESSION['user'] ?? null;

if (!$user || !in_array(strtolower((string)($user['role'] ?? '')), ['res_admin', 'ss_admin'], true)) {
    header('Location: empresa_list.php?error=unauthorized');
    exit;
}

// ============================
// 📩 Validar ID recibido por POST
// ============================
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    header('Location: empresa_list.php?error=invalid_id');
    exit;
}

// ============================
// ⚙️ Ejecutar desactivación
// ============================
try {
    $controller = new EmpresaController();

    // Desactivar en cascada
    $controller->disableEmpresa(
        $id,
        (int)($user['id'] ?? 0),
        'Desactivación manual desde listado de empresas'
    );

    // Redirigir con mensaje de éxito
    header('Location: empresa_list.php?success=disabled');
    exit;

} catch (RuntimeException $e) {
    // Error controlado (problemas de base de datos, etc.)
    $msg = urlencode($e->getMessage());
    header("Location: empresa_list.php?error={$msg}");
    exit;

} catch (Throwable $t) {
    // Error inesperado
    header('Location: empresa_list.php?error=unexpected');
    exit;
}
