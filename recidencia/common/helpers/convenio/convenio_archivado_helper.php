<?php
declare(strict_types=1);

namespace Residencia\Common\Helpers\Convenio;

function prettifyKey(string $key): string
{
    return ucwords(str_replace('_', ' ', $key));
}

/**
 * @param array<int, array<string, mixed>> $rows
 */
function renderSnapshotTable(array $rows): string
{
    if ($rows === []) {
        return '<p class="text-muted">Sin registros.</p>';
    }

    $columns = array_keys($rows[0]);
    $html = '<div class="table-responsive"><table class="table">';
    $html .= '<thead><tr>';
    foreach ($columns as $column) {
        $html .= '<th>' . htmlspecialchars(prettifyKey((string) $column), ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $html .= '</tr></thead><tbody>';

    foreach ($rows as $row) {
        $html .= '<tr>';
        foreach ($columns as $column) {
            $value = $row[$column] ?? '';
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            if (is_string($value)) {
                $trimmed = trim($value);
                $looksLikeHtml = stripos($trimmed, '<p') !== false
                    || stripos($trimmed, '<br') !== false
                    || strpos($trimmed, '&lt;') !== false
                    || strpos($trimmed, '</') !== false;
                if ($looksLikeHtml) {
                    $decoded = html_entity_decode($trimmed, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $safe = strip_tags(
                        $decoded,
                        '<p><br><strong><b><em><i><u><ul><ol><li><table><thead><tbody><tr><td><th><figure><span><div>'
                    );
                    $html .= '<td><div class="html-preview">' . $safe . '</div></td>';
                    continue;
                }
            }
            $html .= '<td>' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div>';

    return $html;
}
