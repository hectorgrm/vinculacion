<?php

declare(strict_types=1);

use PortalEmpresa\Controller\EmpresaPerfilController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../controller/EmpresaPerfilController.php';
require_once __DIR__ . '/../helpers/empresaperfil_helper_function.php';
require_once __DIR__ . '/../common/functions/empresaperfil_function.php';

if (!isset($_SESSION['portal_empresa']) || !is_array($_SESSION['portal_empresa'])) {
    header('Location: ../view/login.php');
    exit;
}

$sessionEmpresa = $_SESSION['portal_empresa'];
$empresaId = isset($sessionEmpresa['empresa_id']) ? (int) $sessionEmpresa['empresa_id'] : 0;

if ($empresaId <= 0) {
    header('Location: ../view/login.php');
    exit;
}

$controller = new EmpresaPerfilController();
$perfilErrorMessage = null;

try {
    $perfil = $controller->obtenerPerfil($empresaId);
} catch (\Throwable $exception) {
    $perfil = empresaPerfilEmptyRecord();
    $perfilErrorMessage = 'No se pudo cargar la información de la empresa. Intenta nuevamente más tarde.';
}

$empresaNombre = $perfil['nombre'] !== ''
    ? $perfil['nombre']
    : (string) ($sessionEmpresa['empresa_nombre'] ?? '');
$sector = $perfil['sector'] ?? '';
$rfc = $perfil['rfc'] ?? '';
$representante = $perfil['representante'] ?? '';
$cargoRepresentante = $perfil['cargo_representante'] ?? '';
$telefono = $perfil['telefono'] ?? '';
$contactoEmail = $perfil['contacto_email'] ?? '';
$sitioWeb = $perfil['sitio_web'] ?? '';
$sitioWebUrl = $perfil['sitio_web_url'] ?? '';
$direccion = $perfil['direccion'] ?? '';
$municipio = $perfil['municipio'] ?? '';
$estado = $perfil['estado'] ?? '';
$cp = $perfil['cp'] ?? '';
$estatus = $perfil['estatus'] ?? (string) ($sessionEmpresa['empresa_estatus'] ?? '');
$estatusBadgeClass = empresaPerfilBadgeClass($estatus);
$estatusBadgeLabel = empresaPerfilBadgeLabel($estatus);

if ($empresaNombre === '') {
    $empresaNombre = 'Empresa';
}

require __DIR__ . '/../view/perfil_empresa.php';
