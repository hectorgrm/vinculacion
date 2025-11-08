<?php
declare(strict_types=1);
// machote_revision_guardar.php
// Guarda el borrador del machote (HTML) como archivo por revision_id, sin tocar la BD.
// Responde JSON. Espera POST: revision_id (int), contenido_html (string)

header('Content-Type: application/json; charset=utf-8');

try {
    // (Opcional) Autenticación/sesión
    // session_start();
    // if (!isset($_SESSION['user_id'])) { throw new RuntimeException('No autenticado.'); }

    // Validar método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
        exit;
    }

    // Obtener entrada (application/x-www-form-urlencoded)
    $revisionId = isset($_POST['revision_id']) ? (int)$_POST['revision_id'] : 0;
    $contenido  = isset($_POST['contenido_html']) ? (string)$_POST['contenido_html'] : '';

    if ($revisionId <= 0) {
        throw new InvalidArgumentException('revision_id inválido.');
    }
    if ($contenido === '') {
        throw new InvalidArgumentException('contenido_html vacío.');
    }

    // Límite de tamaño (1.5 MB aprox)
    if (strlen($contenido) > 1_500_000) {
        throw new InvalidArgumentException('El contenido es demasiado grande.');
    }

    // Sanitización mínima (quitamos <script> y on* handlers)
    // Nota: para sanitización completa usar HTML Purifier en la etapa final.
    $contenido_sanit = preg_replace('#<script\b[^>]*>(.*?)</script>#is', '', $contenido);
    // Remueve atributos on* (onload, onclick, etc.)
    $contenido_sanit = preg_replace('/\s+on\w+="[^"]*"/i', '', $contenido_sanit);
    $contenido_sanit = preg_replace("/\s+on\w+='[^']*'/i", '', $contenido_sanit);

    // Ruta base segura: /uploads/machote_drafts/
    $baseDir = realpath(__DIR__ . '/../../uploads');
    if ($baseDir === false) {
        // Crear /uploads si no existe
        $uploadsRoot = __DIR__ . '/../../uploads';
        if (!is_dir($uploadsRoot) && !mkdir($uploadsRoot, 0775, true)) {
            throw new RuntimeException('No se pudo crear el directorio /uploads.');
        }
        $baseDir = realpath($uploadsRoot);
    }

    $draftDir = $baseDir . DIRECTORY_SEPARATOR . 'machote_drafts';
    if (!is_dir($draftDir) && !mkdir($draftDir, 0775, true)) {
        throw new RuntimeException('No se pudo crear el directorio machote_drafts.');
    }

    // Archivo por revisión
    $fileName   = 'rev_' . $revisionId . '.html';
    $finalPath  = $draftDir . DIRECTORY_SEPARATOR . $fileName;

    // Escritura atómica: primero a archivo temporal
    $tmpPath = $finalPath . '.tmp';
    $bytes   = file_put_contents($tmpPath, $contenido_sanit, LOCK_EX);
    if ($bytes === false) {
        throw new RuntimeException('No se pudo escribir el borrador temporal.');
    }

    // Renombrar a definitivo
    if (!rename($tmpPath, $finalPath)) {
        // Limpieza si falla rename
        @unlink($tmpPath);
        throw new RuntimeException('No se pudo finalizar el guardado del borrador.');
    }

    // (Opcional) Guardar un respaldo con timestamp
    // file_put_contents($draftDir . '/rev_' . $revisionId . '_' . date('Ymd_His') . '.bak.html', $contenido_sanit);

    // Ruta pública relativa para que el front pueda abrirlo si hace falta
    $publicRelative = '../../uploads/machote_drafts/' . $fileName;

    echo json_encode([
        'ok'        => true,
        'revision_id' => $revisionId,
        'bytes'     => $bytes,
        'saved_at'  => date('c'),
        'path'      => $publicRelative
    ]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
