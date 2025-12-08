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
            $html .= '<td>' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div>';

    return $html;
}
