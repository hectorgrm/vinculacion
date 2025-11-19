import pathlib
text = pathlib.Path('recidencia/view/empresa/empresa_edit.php').read_text(encoding='utf-8')
start = text.index('<label for="estatus"')
end = text.index('<label for="regimen_fiscal"')
print(text[start:end])
