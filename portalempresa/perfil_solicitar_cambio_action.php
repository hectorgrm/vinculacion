<?php
// residencias/view/portalempresa/perfil_solicitar_cambio_action.php
declare(strict_types=1);

session_start();

// === Ajusta estos require según tu estructura ===
require_once __DIR__ . '/../../../config/db.php';      // Debe definir $pdo = new PDO(...)
require_once __DIR__ . '/../../../config/session.php'; // Si necesitas helpers de sesión

// --- Autenticación básica (ajusta a tu lógica) ---
$user = $_SESSION['user'] ?? null;
// Esperamos un rol de portal de empresa, ajusta el nombre del rol si usas otro:
if (!is_array($user) || !in_array(($user['role'] ?? ''), ['empresa', 'empresa_portal', 'res_empresa'], true)) {
  header('Location: ../../common/login.php?error=unauthorized');
  exit;
}

$empresaId = (int)($user['empresa_id'] ?? ($_POST['empresa_id'] ?? 0));
if ($empresaId <= 0) {
  header('Location: perfil_solicitar_cambio.php?error=no_empresa');
  exit;
}

// --- Solo POST ---
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
  header('Location: perfil_solicitar_cambio.php?error=method');
  exit;
}

// --- Recoger y sanear entradas ---
$razon            = trim((string)($_POST['razon']            ?? ''));
$rfc              = trim((string)($_POST['rfc']              ?? ''));
$domicilio        = trim((string)($_POST['domicilio']        ?? ''));
$sitio            = trim((string)($_POST['sitio']            ?? ''));
$telefono         = trim((string)($_POST['telefono']         ?? ''));

$contacto_nombre  = trim((string)($_POST['contacto_nombre']  ?? ''));
$contacto_email   = trim((string)($_POST['contacto_email']   ?? ''));
$contacto_puesto  = trim((string)($_POST['contacto_puesto']  ?? ''));
$contacto_tel     = trim((string)($_POST['contacto_tel']     ?? ''));

$autorizados      = trim((string)($_POST['autorizados']      ?? ''));
$fecha_preferente = trim((string)($_POST['fecha_preferente'] ?? ''));
$mensaje          = trim((string)($_POST['mensaje']          ?? ''));

$consent          = isset($_POST['consent']);
$tipos            = $_POST['tipo_cambio'] ?? []; // array multiselect
if (!is_array($tipos)) $tipos = [];
$tipos_json = json_encode(array_values(array_unique(array_map('strval', $tipos))), JSON_UNESCAPED_UNICODE);

// Validaciones mínimas
if (!$consent || $mensaje === '') {
  header('Location: perfil_solicitar_cambio.php?error=val');
  exit;
}

// --- Config de subida ---
const MAX_BYTES = 10 * 1024 * 1024; // 10 MB por archivo
$allowedExt = ['pdf','jpg','jpeg','png'];
$allowedMime = [
  'application/pdf',
  'image/jpeg',
  'image/png'
];
$uploadDir = __DIR__ . '/../../../uploads/portal_solicitudes';
if (!is_dir($uploadDir)) {
  @mkdir($uploadDir, 0775, true);
}

// --- Transacción ---
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
  $pdo->beginTransaction();

  // 1) Insertar solicitud
  // AJUSTA los nombres de tabla/columnas a tu esquema real
  $sql = "INSERT INTO rp_empresa_update_request
          (empresa_id, razon, rfc, domicilio, sitio, telefono,
           contacto_nombre, contacto_email, contacto_puesto, contacto_tel,
           autorizados, tipos_json, fecha_preferente, mensaje,
           estatus, creado_en)
          VALUES
          (:empresa_id, :razon, :rfc, :domicilio, :sitio, :telefono,
           :contacto_nombre, :contacto_email, :contacto_puesto, :contacto_tel,
           :autorizados, :tipos_json, :fecha_preferente, :mensaje,
           'pendiente', NOW())";
  $st = $pdo->prepare($sql);
  $st->execute([
    ':empresa_id'       => $empresaId,
    ':razon'            => $razon !== '' ? $razon : null,
    ':rfc'              => $rfc   !== '' ? $rfc   : null,
    ':domicilio'        => $domicilio !== '' ? $domicilio : null,
    ':sitio'            => $sitio !== '' ? $sitio : null,
    ':telefono'         => $telefono !== '' ? $telefono : null,
    ':contacto_nombre'  => $contacto_nombre !== '' ? $contacto_nombre : null,
    ':contacto_email'   => $contacto_email  !== '' ? $contacto_email  : null,
    ':contacto_puesto'  => $contacto_puesto !== '' ? $contacto_puesto : null,
    ':contacto_tel'     => $contacto_tel    !== '' ? $contacto_tel    : null,
    ':autorizados'      => $autorizados     !== '' ? $autorizados     : null,
    ':tipos_json'       => $tipos_json,
    ':fecha_preferente' => $fecha_preferente !== '' ? $fecha_preferente : null,
    ':mensaje'          => $mensaje,
  ]);
  $requestId = (int)$pdo->lastInsertId();

  // 2) Procesar adjuntos (múltiples)
  if (!empty($_FILES['adjuntos']) && is_array($_FILES['adjuntos']['name'])) {
    $names = $_FILES['adjuntos']['name'];
    $tmps  = $_FILES['adjuntos']['tmp_name'];
    $errs  = $_FILES['adjuntos']['error'];
    $sizes = $_FILES['adjuntos']['size'];
    $types = $_FILES['adjuntos']['type'];

    for ($i = 0; $i < count($names); $i++) {
      if (($errs[$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) continue;
      if ($errs[$i] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('upload_error_'.$errs[$i]);
      }

      $origName = (string)$names[$i];
      $tmpPath  = (string)$tmps[$i];
      $size     = (int)$sizes[$i];
      $mime     = (string)$types[$i];

      if ($size <= 0 || $size > MAX_BYTES) {
        throw new RuntimeException('file_size_invalid');
      }

      // Validar extensión por seguridad
      $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
      if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('file_ext_invalid');
      }

      // Validar MIME (best-effort)
      if (!in_array($mime, $allowedMime, true)) {
        // Intento con finfo si el server lo soporta
        if (function_exists('finfo_open')) {
          $fi = finfo_open(FILEINFO_MIME_TYPE);
          $realMime = finfo_file($fi, $tmpPath) ?: '';
          finfo_close($fi);
          if (!in_array($realMime, $allowedMime, true)) {
            throw new RuntimeException('file_mime_invalid');
          }
        } else {
          // Si no hay finfo, seguimos con el tipo reportado por el navegador
          // (ya controlamos extensión y tamaño)
        }
      }

      // Nombre único
      $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($origName, PATHINFO_FILENAME));
      $newName  = sprintf('req_%d_%s_%s.%s',
                    $requestId,
                    date('YmdHis'),
                    bin2hex(random_bytes(4)),
                    $ext
                  );
      $destPath = $uploadDir . '/' . $newName;

      if (!move_uploaded_file($tmpPath, $destPath)) {
        throw new RuntimeException('move_failed');
      }

      // Insert archivo
      $sqlF = "INSERT INTO rp_empresa_update_file
               (request_id, file_name, original_name, mime, size_bytes, uploaded_at)
               VALUES (:request_id, :file_name, :original_name, :mime, :size_bytes, NOW())";
      $stF = $pdo->prepare($sqlF);
      $stF->execute([
        ':request_id'   => $requestId,
        ':file_name'    => $newName,
        ':original_name'=> $origName,
        ':mime'         => $mime,
        ':size_bytes'   => $size,
      ]);
    }
  }

  $pdo->commit();

  // Éxito: regresar con ok=1
  header('Location: perfil_solicitar_cambio.php?ok=1');
  exit;

} catch (Throwable $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  // Log interno (ajusta a tu logger)
  error_log('[perfil_solicitar_cambio_action] '.$e->getMessage());

  // Feedback genérico
  header('Location: perfil_solicitar_cambio.php?error=internal');
  exit;
}
