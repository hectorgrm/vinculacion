<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset de Contraseña · Residencias</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../../assets/css/modules/portalacceso.css" />


</head>

<body>
    <?php
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 901;
    $email = 'admin@casadelbarrio.mx';
    ?>
    <div class="app">
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
        <main class="main">
            <header class="topbar">
                <div>
                    <h2>🔁 Reset de contraseña</h2>
                    <nav class="breadcrumb">
                        <a href="../../index.php">Inicio</a><span>›</span>
                        <a href="portal_list.php">Portal de Acceso</a><span>›</span>
                        <a href="portal_view.php?id=<?php echo $id; ?>">Ver</a><span>›</span>
                        <span>Reset</span>
                    </nav>
                </div>
                <a class="btn" href="portal_view.php?id=<?php echo $id; ?>">⬅ Volver</a>
            </header>

            <section class="card">
                <header>🧾 Generar nueva contraseña</header>
                <div class="content">
                    <form class="form" action="portal_reset_action.php?id=<?php echo $id; ?>" method="post"
                        autocomplete="off">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="grid">
                            <div class="field col-span-2">
                                <label>Usuario</label>
                                <div><?php echo htmlspecialchars($email); ?></div>
                            </div>

                            <div class="field col-span-2">
                                <label for="new_password">Nueva contraseña temporal</label>
                                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                    <input id="new_password" name="new_password" type="text"
                                        placeholder="Genera o escribe una contraseña segura" style="flex:1">
                                    <button class="btn" type="button" onclick="genPwd()">🔐 Generar</button>
                                    <button class="btn" type="button" onclick="copyPwd()">📋 Copiar</button>
                                </div>
                                <div class="hint">Se forzará el cambio de contraseña al próximo inicio (puedes desmarcar
                                    abajo).</div>
                            </div>

                            <div class="field">
                                <label style="display:flex; gap:10px; align-items:flex-start;">
                                    <input type="checkbox" name="require_change" value="1" checked>
                                    <span>Requerir cambio de contraseña al próximo inicio.</span>
                                </label>
                            </div>

                            <div class="field">
                                <label style="display:flex; gap:10px; align-items:flex-start;">
                                    <input type="checkbox" name="revoke_sessions" value="1" checked>
                                    <span>Cerrar sesiones activas actuales (logout forzado).</span>
                                </label>
                            </div>

                            <div class="field col-span-2">
                                <label style="display:flex; gap:10px; align-items:flex-start;">
                                    <input type="checkbox" name="send_email" value="1" checked>
                                    <span>Enviar correo con instrucciones al usuario.</span>
                                </label>
                            </div>
                        </div>

                        <div class="actions">
                            <a class="btn" href="portal_view.php?id=<?php echo $id; ?>">Cancelar</a>
                            <button class="btn primary" type="submit">💾 Aplicar reset</button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="card">
                <header>⚠️ Notas de seguridad</header>
                <div class="content">
                    <ul style="margin:0;padding-left:18px;">
                        <li>Usa contraseñas largas (12+), con mayúsculas, minúsculas, números y símbolos.</li>
                        <li>Considera habilitar 2FA para este acceso.</li>
                        <li>Si hubo intento de acceso no autorizado, revisa la bitácora y filtra por IP.</li>
                    </ul>
                </div>
            </section>
        </main>
    </div>

    <script>
        function genPwd() {
            const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*?";
            let out = ""; for (let i = 0; i < 14; i++) out += chars.charAt(Math.floor(Math.random() * chars.length));
            document.getElementById('new_password').value = out;
        }
        function copyPwd() {
            const el = document.getElementById('new_password'); el.select(); el.setSelectionRange(0, 999); document.execCommand('copy');
        }
    </script>
</body>

</html>