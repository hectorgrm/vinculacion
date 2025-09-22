<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/serviciosocialstyles.css">
</head>

<body>
    <header>
        <h1>Gestión de Plazas</h1>
        <p>Aquí puedes administrar las plazas de Servicio Social</p>
    </header>


    <div class="top-actions">
        <a href="../index.php" class="btn btn-back">⬅ Regresar</a>
        <a href="plaza_add.php" class="btn btn-add">➕ Dar de Alta Plaza</a>
    </div>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Empresa</th>
                <th>Dirección</th>
                <th>Cupo</th>
                <th>Activa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Desarrollo Web</td>
                <td>Empresa XYZ</td>
                <td>Av. Universidad 123</td>
                <td>5</td>
                <td>Sí</td>
                <td class="actions">
                    <a href="#" class="view" title="Ver">👁</a>
                    <a href="#" class="edit" title="Editar">✏</a>
                    <a href="#" class="delete" title="Eliminar">🗑</a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Atención Social</td>
                <td>Fundación ABC</td>
                <td>Calle Juárez 45</td>
                <td>3</td>
                <td>No</td>
                <td class="actions">
                    <a href="#" class="view" title="Ver">👁</a>
                    <a href="#" class="edit" title="Editar">✏</a>
                    <a href="#" class="delete" title="Eliminar">🗑</a>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>