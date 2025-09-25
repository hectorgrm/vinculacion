<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../model/EstudianteModel.php';
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Serviciosocial\Model\EstudianteModel;
use Serviciosocial\Model\UserModel;
use Common\Model\Database;

// Validar sesión y rol
$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$pdo = Database::getConnection();
$estudianteModel = new EstudianteModel($pdo);
$userModel = new UserModel($pdo);

// Determinar acción
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            $nombre    = trim($_POST['nombre'] ?? '');
            $matricula = trim($_POST['matricula'] ?? '');
            $carrera   = trim($_POST['carrera'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $telefono  = trim($_POST['telefono'] ?? '');
            $password  = trim($_POST['password'] ?? '');

            if ($nombre === '' || $matricula === '' || $email === '' || $password === '') {
                throw new Exception("Faltan campos obligatorios.");
            }

            // Crear estudiante
            $estudianteId = $estudianteModel->create([
                'nombre'    => $nombre,
                'matricula' => $matricula,
                'carrera'   => $carrera,
                'email'     => $email,
                'telefono'  => $telefono
            ]);

            // Crear usuario vinculado
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $userModel->create([
                'nombre'        => $nombre,
                'email'         => $email,
                'password_hash' => $passwordHash,
                'rol'           => 'estudiante',
                'estudiante_id' => $estudianteId
            ]);

            header('Location: ../view/altaestudiante/estudiante_list.php?success=1');
            exit;

        case 'delete':
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('Solicitud para eliminar no válida.');
            }

            try {
                $pdo->beginTransaction();

                $userModel->deleteByEstudianteId($id);
                $estudianteModel->delete($id);

                $pdo->commit();
            } catch (Throwable $deleteException) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                throw $deleteException;
            }
            header('Location: ../view/altaestudiante/estudiante_list.php?deleted=1');
            exit;

        case 'edit':
            // Aquí podrías manejar la actualización (update) del estudiante.
            // Ejemplo simple (se puede expandir en estudiante_edit.php):
            $id        = (int)($_POST['id'] ?? 0);
            $nombre    = trim($_POST['nombre'] ?? '');
            $carrera   = trim($_POST['carrera'] ?? '');
            $telefono  = trim($_POST['telefono'] ?? '');

            if ($id > 0) {
                $sql = "UPDATE estudiante SET nombre=:nombre, carrera=:carrera, telefono=:telefono WHERE id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre'   => $nombre,
                    ':carrera'  => $carrera,
                    ':telefono' => $telefono,
                    ':id'       => $id
                ]);
            }
            header('Location: ../view/altaestudiante/estudiante_list.php?updated=1');
            exit;

        default:
            throw new Exception("Acción no reconocida.");
    }
} catch (Throwable $e) {
    header('Location: ../view/altaestudiante/estudiante_list.php?error=' . urlencode($e->getMessage()));
    exit;
}
