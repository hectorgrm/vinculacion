import pathlib
text = pathlib.Path('recidencia/view/empresa/empresa_edit.php').read_text(encoding='utf-8')
start = text.index('<label for="estatus"')
print(text[start:start+400])
