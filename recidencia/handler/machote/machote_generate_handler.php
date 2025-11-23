<?php
/**
 * ===============================================================
 * GENERAR MACHOTE HIJO DESDE PLANTILLA GLOBAL
 * ---------------------------------------------------------------
 * Crea un machote hijo (por convenio) copiando el contenido
 * institucional del machote global (padre), limpiando los logos
 * antiguos, y vinculándolo con el convenio correspondiente.
 * ===============================================================
 */

declare(strict_types=1);

// ===============================================================
// 1️⃣ DEPENDENCIAS Y MODELOS
// ===============================================================
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/auth.php';
require_once __DIR__ . '/../../model/machoteglobal/MachoteGlobalModel.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_auditoria.php';

use Common\Model\Database;
use Residencia\Model\Convenio\ConvenioMachoteModel;
use Residencia\Model\MachoteGlobal\MachoteGlobalModel;

// ===============================================================
// 2️⃣ VALIDAR PARÁMETROS DE ENTRADA
// ===============================================================
$empresaId  = filter_input(INPUT_GET, 'empresa_id', FILTER_VALIDATE_INT);
$convenioId = filter_input(INPUT_GET, 'convenio_id', FILTER_VALIDATE_INT);

if ($convenioId === false || $convenioId === null) {
    redirectToConvenio(null, 'missing_params');
}

// ===============================================================
// 3️⃣ CONEXIÓN A LA BASE DE DATOS Y MODELADO
// ===============================================================
$connection = Database::getConnection();
$convenioMachoteModel = new ConvenioMachoteModel($connection);

// Nombre de la empresa asociada al convenio (para el mensaje de auditoría)
$empresaNombre = '';
try {
    $empresaQuery = $connection->prepare(
        'SELECT e.nombre
         FROM rp_convenio c
         INNER JOIN rp_empresa e ON c.empresa_id = e.id
         WHERE c.id = :convenio_id
         LIMIT 1'
    );

    $empresaQuery->execute([':convenio_id' => $convenioId]);
    $empresaNombre = trim((string) ($empresaQuery->fetchColumn() ?: ''));
} catch (\PDOException $e) {
    // No interrumpir el flujo: solo dejamos el nombre vacío si falla.
}

// Si ya existe un machote hijo asociado al convenio → redirigir a editar
$existingMachote = $convenioMachoteModel->getByConvenio($convenioId);
if ($existingMachote !== null) {
    header('Location: ../../view/machote/machote_edit.php?id=' . urlencode((string) $existingMachote['id']));
    exit;
}

// ===============================================================
// 4️⃣ OBTENER EL MACHOTE GLOBAL (PADRE)
// ===============================================================
$machoteGlobalModel = new MachoteGlobalModel($connection);
$global = $machoteGlobalModel->getLatest();

if ($global === null) {
    redirectToConvenio($convenioId, 'no_global');
}

$machotePadreId = (int) ($global['id'] ?? 0);
$machotePadreVersion = trim((string) ($global['version'] ?? ''));
if ($machotePadreId <= 0) {
    redirectToConvenio($convenioId, 'no_global');
}

// ===============================================================
// 5️⃣ LIMPIAR CONTENIDO DEL MACHOTE PADRE 🧹
// ---------------------------------------------------------------
// Solo se limpia el contenido del machote padre, para que el hijo
// no herede logos fijos ni textos estáticos. El encabezado oficial
// (ITSJ y empresa) se maneja dinámicamente al generar el PDF.
// ===============================================================
$contenidoOriginal = (string) $global['contenido_html'];

// 🔸 Eliminar imagen del logo institucional anterior (LogoITSJ)
$contenidoLimpio = preg_replace(
    '/<img[^>]*LogoITSJ[^>]*>/i',
    '',
    $contenidoOriginal
);

// 🔸 Eliminar texto fijo del encabezado (“LOGOTIPO DE LA EMPRESA”)
$contenidoLimpio = preg_replace(
    '/LOGOTIPO\s+DE\s+LA\s+EMPRESA/i',
    '',
    $contenidoLimpio
);

// 🔸 Quitar espacios o etiquetas residuales
$contenidoFinal = trim($contenidoLimpio);

// ===============================================================
// 6️⃣ CREAR MACHOTE HIJO EN LA BASE DE DATOS
// ===============================================================
// La primera versión local siempre comienza en v1.0.
$versionLocal = 'v1.0';

try {
    $nuevoMachoteId = $convenioMachoteModel->create(
        $convenioId,
        $machotePadreId,
        $contenidoFinal,
        $versionLocal
    );
} catch (\PDOException $e) {
    error_log('Error al crear machote hijo: ' . $e->getMessage());
    redirectToConvenio($convenioId, 'create_failed');
}

// ===============================================================
// 7️⃣ ENLAZAR EL MACHOTE HIJO CON EL CONVENIO
// ===============================================================
try {
    $statement = $connection->prepare('UPDATE rp_convenio SET machote_id = :machote_id WHERE id = :id');
    $statement->execute([
        ':machote_id' => $nuevoMachoteId,
        ':id' => $convenioId,
    ]);
} catch (\PDOException $e) {
    error_log('Error al enlazar machote con convenio: ' . $e->getMessage());
    redirectToConvenio($convenioId, 'link_failed');
}

// ===============================================================
// 8️⃣ REGISTRAR EVENTO EN AUDITORÍA
// ===============================================================
$auditContext = convenioCurrentAuditContext();
$machoteNombre = $empresaNombre !== ''
    ? $empresaNombre . ' — ' . $versionLocal . ' (ID #' . $nuevoMachoteId . ')'
    : 'Machote — ' . $versionLocal . ' (ID #' . $nuevoMachoteId . ')';

$auditDetails = [
    [
        'campo' => 'machote_id',
        'campo_label' => 'Machote generado',
        'valor_anterior' => '—',
        'valor_nuevo' => $machoteNombre,
    ],
    [
        'campo' => 'machote_padre_id',
        'campo_label' => 'Plantilla institucional',
        'valor_anterior' => '—',
        'valor_nuevo' => $machotePadreVersion !== ''
            ? 'Plantilla "' . $machotePadreVersion . '" (ID #' . $machotePadreId . ')'
            : 'Plantilla institucional (ID #' . $machotePadreId . ')',
    ],
];

convenioRegisterAuditEvent('generar_machote', $convenioId, $auditContext, $auditDetails);

// ===============================================================
// 9️⃣ REDIRECCIONAR AL EDITOR DEL MACHOTE HIJO
// ===============================================================
$redirectUrl = '../../view/machote/machote_edit.php?id=' . urlencode((string) $nuevoMachoteId) . '&machote_status=created';
header('Location: ' . $redirectUrl);
exit;

// ===============================================================
// 🔁 FUNCIÓN AUXILIAR DE REDIRECCIÓN
// ===============================================================
function redirectToConvenio(?int $convenioId, string $code): void
{
    $params = [];

    if ($convenioId !== null) {
        $params['id'] = $convenioId;
    }

    $params['machote_error'] = $code;

    $query = http_build_query($params);
    $url = '../../view/convenio/convenio_view.php';
    if ($query !== '') {
        $url .= '?' . $query;
    }

    header('Location: ' . $url);
    exit;
}
