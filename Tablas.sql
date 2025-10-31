-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 65.99.225.140    Database: nessuste_vinculacion
-- ------------------------------------------------------
-- Server version	5.5.5-10.6.23-MariaDB-cll-lve-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `actor_tipo` enum('empresa','usuario','sistema') NOT NULL,
  `actor_id` bigint(20) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `entidad` varchar(100) NOT NULL,
  `entidad_id` bigint(20) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `ts` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_auditoria_entidad` (`entidad`,`entidad_id`),
  KEY `idx_auditoria_actor` (`actor_tipo`,`actor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'usuario',1,'aprobar','documento',2,'201.141.1.10','2025-09-09 23:42:25'),(2,'empresa',1,'subir','documento',4,'187.190.22.15','2025-09-09 23:42:36'),(3,'usuario',2,'validar','doc_ss',1,'201.141.1.12','2025-09-09 23:42:45'),(4,'usuario',3,'descargar','doc_tipo_ss',1,'189.200.45.30','2025-09-09 23:42:54'),(5,'usuario',1,'generar','rp_convenio',4,'127.0.0.1','2025-10-09 22:24:59');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_asignacion`
--

DROP TABLE IF EXISTS `rp_asignacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_asignacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) NOT NULL,
  `estudiante_id` bigint(20) NOT NULL,
  `periodo_id` bigint(20) NOT NULL,
  `proyecto` varchar(255) NOT NULL,
  `estatus` enum('en_curso','concluido','cancelado') DEFAULT 'en_curso',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_rp_asig_empresa` (`empresa_id`),
  KEY `fk_rp_asig_estudiante` (`estudiante_id`),
  KEY `fk_rp_asig_periodo` (`periodo_id`),
  CONSTRAINT `fk_rp_asig_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `rp_empresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rp_asig_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `ss_estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rp_asig_periodo` FOREIGN KEY (`periodo_id`) REFERENCES `ss_periodo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_asignacion`
--

LOCK TABLES `rp_asignacion` WRITE;
/*!40000 ALTER TABLE `rp_asignacion` DISABLE KEYS */;
INSERT INTO `rp_asignacion` VALUES (1,1,1,1,'Desarrollo de sistema documental','en_curso','2025-02-10','2025-07-31','2025-10-14 20:11:37'),(2,2,2,1,'Optimización de procesos de producción','en_curso','2025-02-15','2025-07-31','2025-10-14 20:11:37'),(3,1,1,2,'Diseño de dashboard administrativo','concluido','2024-08-10','2024-12-20','2025-10-14 20:11:37'),(4,2,2,2,'Automatización de reportes internos','concluido','2024-08-05','2024-12-15','2025-10-14 20:11:37');
/*!40000 ALTER TABLE `rp_asignacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_convenio`
--

DROP TABLE IF EXISTS `rp_convenio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_convenio` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) NOT NULL,
  `machote_version` varchar(50) DEFAULT NULL,
  `estatus` enum('Activa','En revisión','Inactiva','Suspendida') NOT NULL DEFAULT 'En revisión',
  `observaciones` text DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `version_actual` varchar(100) DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `folio` varchar(32) DEFAULT NULL,
  `borrador_path` varchar(255) DEFAULT NULL,
  `firmado_path` varchar(255) DEFAULT NULL,
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_convenio_empresa` (`empresa_id`,`estatus`),
  CONSTRAINT `fk_convenio_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `rp_empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_convenio`
--

LOCK TABLES `rp_convenio` WRITE;
/*!40000 ALTER TABLE `rp_convenio` DISABLE KEYS */;
INSERT INTO `rp_convenio` VALUES (1,1,'v1.0','Activa','Convenio vigente firmado correctamente.','2025-07-01','2026-06-30','v1.2','2025-09-09 21:48:28','CBR-2025-01','/uploads/convenios/CBR_2025_borrador.pdf','/uploads/convenios/CBR_2025_firmado.pdf','2025-10-22 23:22:40'),(2,2,'v1.0','En revisión','Pendiente de revisión por Vinculación.','2025-08-15',NULL,'v1.0','2025-09-09 21:48:28','ECT-2025-02',NULL,NULL,'2025-10-22 23:22:40'),(3,3,'v0.9','Inactiva','Convenio vencido, requiere renovación.',NULL,NULL,NULL,'2025-09-09 21:48:28','YKM-2024-05',NULL,NULL,'2025-10-22 23:22:40'),(4,1,'v1.1','Inactiva','Suspendido temporalmente por falta de documentación.','2025-11-01','2026-10-31','v1.2','2025-10-09 22:24:43','DSL-2025-07',NULL,NULL,'2025-10-25 03:52:35'),(5,32,'V1','Inactiva','Se hace el test para Hector Systems levantamiento de Convenio','2025-10-23','2025-10-31','V1','2025-10-23 03:51:02','Foliotest01','uploads/convenios/convenio_20251023_095103_a041872cf4a9a633.pdf',NULL,'2025-10-25 03:58:33'),(6,32,'V2','En revisión','TEST 2','2025-10-23','2025-10-31','V2','2025-10-23 03:55:32','Foliotest01','uploads/convenios/convenio_20251023_095533_d3e81c47690e4d2a.pdf',NULL,'2025-10-23 03:55:32'),(7,4,'V3 aditar test4','Activa','SE Tiene que remplazar un nuevo convenio.','2025-10-24','2025-10-29','V 3 editar test4','2025-10-23 04:45:12','Foliotest04','uploads/convenios/convenio_20251024_065921_20b08583237e3e66.pdf',NULL,'2025-10-25 03:51:56'),(8,2,'Verciontest','Inactiva','Test MVC 22','2025-10-25','2025-10-31','V33','2025-10-25 04:25:35','Foliotest023EditTest','uploads/convenios/convenio_20251027_095646_697b71c9336462a8.pdf',NULL,'2025-10-27 03:59:32'),(9,32,'V33','Activa','test 2ddddd','2025-10-25','2025-10-31','V33','2025-10-27 04:20:03','Foliotest023sssssss','uploads/convenios/convenio_20251027_102003_fe9143e3cfa678fb.pdf',NULL,'2025-10-27 06:47:02'),(10,34,'VtestMVC','En revisión','Agregar a Convenio','2025-10-30','2025-10-30','MVC','2025-10-30 04:22:53','TEST FOLIO','uploads/convenios/convenio_20251030_102254_44708cc38ff962bb.pdf',NULL,'2025-10-30 04:23:59');
/*!40000 ALTER TABLE `rp_convenio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_documento_tipo`
--

DROP TABLE IF EXISTS `rp_documento_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_documento_tipo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT 1,
  `tipo_empresa` enum('fisica','moral','ambas') DEFAULT 'ambas',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_documento_tipo_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_documento_tipo`
--

LOCK TABLES `rp_documento_tipo` WRITE;
/*!40000 ALTER TABLE `rp_documento_tipo` DISABLE KEYS */;
INSERT INTO `rp_documento_tipo` VALUES (15,'Constancia de situación fiscal (SAT)','Copia de constancia del SAT emitida por la autoridad fiscal.',1,'fisica',1),(16,'Comprobante de domicilio','Documento vigente no mayor a tres meses.',1,'fisica',1),(17,'INE del titular','Identificación oficial del titular del negocio.',1,'fisica',1),(18,'Logotipo del negocio','Archivo JPG o PNG con el logotipo del negocio (opcional).',0,'fisica',1),(19,'Acta constitutiva','Carátula o documento donde se observe su constitución y objeto social.',1,'ambas',0),(20,'Poder notarial (si aplica)','Copia del poder notariado y la INE del apoderado legal (solo si aplica).',0,'moral',1),(21,'INE del representante legal','Identificación oficial vigente del representante legal.',1,'moral',1),(22,'Logotipo de la empresa','Archivo JPG o PNG con el logotipo institucional (opcional).',0,'moral',1),(25,'Logotipo de Mascota','MASCOTA LOGO',1,'ambas',1),(26,'Another ONE','Another one',1,'ambas',1);
/*!40000 ALTER TABLE `rp_documento_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_documento_tipo_empresa`
--

DROP TABLE IF EXISTS `rp_documento_tipo_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_documento_tipo_empresa` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT 1,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tipo_empresa` (`empresa_id`),
  CONSTRAINT `fk_tipo_empresa_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `rp_empresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_documento_tipo_empresa`
--

LOCK TABLES `rp_documento_tipo_empresa` WRITE;
/*!40000 ALTER TABLE `rp_documento_tipo_empresa` DISABLE KEYS */;
INSERT INTO `rp_documento_tipo_empresa` VALUES (1,47,'CIel','Ciel',1,1,'2025-10-31 04:16:33','2025-10-31 04:16:33'),(2,47,'bonafont test de entrada','bonafont test de entrada',1,1,'2025-10-31 04:39:42','2025-10-31 06:16:15');
/*!40000 ALTER TABLE `rp_documento_tipo_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_empresa`
--

DROP TABLE IF EXISTS `rp_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_empresa` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `numero_control` varchar(20) DEFAULT NULL,
  `nombre` varchar(191) NOT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `representante` varchar(191) DEFAULT NULL,
  `cargo_representante` varchar(191) DEFAULT NULL,
  `sector` varchar(191) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `contacto_nombre` varchar(191) DEFAULT NULL,
  `contacto_email` varchar(191) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estatus` enum('Activa','En revisión','Inactiva','Suspendida') NOT NULL DEFAULT 'En revisión',
  `regimen_fiscal` varchar(191) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_empresa_rfc` (`rfc`),
  UNIQUE KEY `numero_control` (`numero_control`),
  KEY `idx_empresa_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_empresa`
--

LOCK TABLES `rp_empresa` WRITE;
/*!40000 ALTER TABLE `rp_empresa` DISABLE KEYS */;
INSERT INTO `rp_empresa` VALUES (1,'EMP-0001','Casa del Barrio','CDB810101AA1','José Manuel Velador','Director General','Educación / Social','https://casadelbarrio.mx','José Manuel Velador','contacto@casadelbarrio.mx','(33) 1234 5678','Jalisco','Guadalajara','44100','Av. Vallarta 1200, Col. Arcos Vallarta','En revisión','Persona Moral con fines no lucrativos','Colabora en proyectos sociales con estudiantes.','2025-09-09 21:48:19','2025-10-26 04:46:39'),(2,'EMP-0002','Tequila ECT','TEC920202BB2','María González','Gerente de Producción','Industrial / Alimentario','https://tequilaect.com.mx','María González','legal@tequilaect.com','(33) 2345 6789','Jalisco','Tequila','46400','Calle Morelos 45, Centro','Activa','Persona Moral','Empresa con convenio activo en prácticas profesionales.','2025-09-09 21:48:19','2025-10-23 00:03:42'),(3,'EMP-0003','Industrias Yakumo','IYA930303CC3','Luis Pérez','Director de Vinculación','Tecnología / Manufactura','https://yakumo.com.mx','Luis Pérez','vinculacion@yakumo.com','(55) 3456 7890','Ciudad de México','Benito Juárez','03100','Av. Universidad 300, Col. Del Valle','En revisión','Sociedad Anónima de Capital Variable','Recibe alumnos de ingeniería en informática.','2025-09-09 21:48:19','2025-10-23 00:03:42'),(4,'EMP-00044','Barbería Góme','BG1234567AA1','Hector Ruizss','Propietario','Servicios / Estética','https://barberiagomez.mx','Homero Ruizxzx','contacto@barberiagomez.mx','33 1234 5678','Jalisco','Tequila','46400','Calle Hidalgo 12','En revisión','Régimen Simplificado de Confianza','Negocio local que apoya programas de residencias profesionales.','2025-10-15 00:23:17','2025-10-27 07:04:41'),(28,'EMP-0005','NEW EMPRESA','TEST123321232','TestLegal','Test General','Education','https://www.test.com','Test Hector','hector@tech.com','(22)33-33-33-33','Activo','testtequila','40209234','tequila, av tech 3333','En revisión','Fisico','Test','2025-10-15 23:19:55','2025-10-23 00:03:42'),(30,'EMP-0006','NEW EMPRESA','TEST1233212324','TestLegal','Test General','Education','https://www.test.com','Test Hector','hector@tech.com','(22)33-33-33-33','Activo','testtequila','40209234','tequila, av tech 3333','En revisión','Fisico',NULL,'2025-10-15 23:20:53','2025-10-23 00:03:42'),(32,'EMP-0007','HECTOR SYSTEMS','TEST22233445566','TestLegal','Test General','Education','https://www.test.com','Test Hector','TEst@test.com','(22)33-33-33-33','TESTestado','testtequila','40209','CalleTest3','Inactiva','Fisico','En Revision test ','2025-10-21 20:28:33','2025-10-27 06:47:23'),(34,'EMP-2005','HECTOR MVC','TESTMVC','TestLegal','Test General','Education','https://www.test.com','Test Hector','TEst@test.com','(22)33-33-33-33','TESTestado','testtequila','40209','CalleTest3','En revisión','Fisico','MVC TEST ','2025-10-25 07:11:31',NULL),(40,'EMP 001','TEST MVC','TESTMVC001','JOSE TEST','TEST JOSE','EMORESA','https://www.test.com','jose luis','contact@test.com','33333333','Jalisco','Mexico','44342','Calle independecia11','En revisión','Fiscall','NOTE MVC ','2025-10-25 07:14:43',NULL),(46,'EMP 0011','TEST MVC','RFCTEST001','JOSE TEST','TEST JOSE','EMORESA','https://www.test.com','jose luis','contact@test.com','33333333','Jalisco','Mexico','44342','Calle independecia11','En revisión','Fiscall',NULL,'2025-10-25 07:24:58',NULL),(47,'EMP1212','BONAFONT','RFCTESTBONAFONT','JOSE TEST','TEST JOSE','EMORESA','https://www.test.com','jose luis','TEst@test.com','2233333333','TESTestado','testtequila','40209','CalleTest3','En revisión','Fiscall','BONAFONT TEST','2025-10-30 23:04:33',NULL);
/*!40000 ALTER TABLE `rp_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_empresa_doc`
--

DROP TABLE IF EXISTS `rp_empresa_doc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_empresa_doc` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) NOT NULL,
  `tipo_global_id` bigint(20) DEFAULT NULL,
  `tipo_personalizado_id` bigint(20) DEFAULT NULL,
  `ruta` varchar(255) NOT NULL,
  `estatus` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `observacion` text DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_empresa_doc_global` (`empresa_id`,`tipo_global_id`),
  KEY `idx_doc_empresa` (`empresa_id`),
  KEY `idx_doc_tipo_global` (`tipo_global_id`),
  KEY `idx_doc_tipo_personalizado` (`tipo_personalizado_id`),
  KEY `idx_empresa_tipo` (`empresa_id`,`tipo_global_id`,`tipo_personalizado_id`),
  CONSTRAINT `fk_doc_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `rp_empresa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_doc_tipo_global` FOREIGN KEY (`tipo_global_id`) REFERENCES `rp_documento_tipo` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_doc_tipo_personalizado` FOREIGN KEY (`tipo_personalizado_id`) REFERENCES `rp_documento_tipo_empresa` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_empresa_doc`
--

LOCK TABLES `rp_empresa_doc` WRITE;
/*!40000 ALTER TABLE `rp_empresa_doc` DISABLE KEYS */;
INSERT INTO `rp_empresa_doc` VALUES (1,47,16,NULL,'uploads/documento/doc_47_16_20251031_103510_d6420bb7.pdf','pendiente','dddddddddddddddd','2025-10-31 04:35:09','2025-10-31 04:35:09'),(3,47,NULL,2,'uploads/documento/doc_47_2_20251031_121328_07598e1e.pdf','aprobado','Se aprobo','2025-10-31 06:13:28','2025-10-31 06:37:02'),(4,47,NULL,2,'uploads/documento/doc_47_2_20251031_123935_3d73fce6.pdf','rechazado','DOCUMENTO Error','2025-10-31 06:39:35','2025-10-31 06:44:23'),(6,47,NULL,2,'uploads/documento/doc_47_2_20251031_124133_9c4d0bd1.pdf','pendiente',NULL,'2025-10-31 06:41:33','2025-10-31 06:41:33');
/*!40000 ALTER TABLE `rp_empresa_doc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_machote_comentario`
--

DROP TABLE IF EXISTS `rp_machote_comentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_machote_comentario` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `convenio_id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `clausula` varchar(100) NOT NULL,
  `comentario` text NOT NULL,
  `estatus` enum('pendiente','resuelto') DEFAULT 'pendiente',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_mc_convenio` (`convenio_id`,`clausula`),
  KEY `fk_mc_usuario` (`usuario_id`),
  CONSTRAINT `fk_mc_convenio` FOREIGN KEY (`convenio_id`) REFERENCES `rp_convenio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_mc_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_machote_comentario`
--

LOCK TABLES `rp_machote_comentario` WRITE;
/*!40000 ALTER TABLE `rp_machote_comentario` DISABLE KEYS */;
INSERT INTO `rp_machote_comentario` VALUES (4,1,NULL,'Cláusula 3','Se solicita aclarar las obligaciones de la empresa.','pendiente','2025-09-19 02:09:54'),(5,1,NULL,'Cláusula 5','Modificar la vigencia a 18 meses en lugar de 12.','pendiente','2025-09-19 02:09:54'),(6,2,NULL,'Cláusula 2','Se requiere ajustar la redacción sobre confidencialidad.','pendiente','2025-09-19 02:09:54'),(7,2,NULL,'Cláusula 7','Eliminar apartado de penalizaciones por retraso.','pendiente','2025-09-19 02:09:54');
/*!40000 ALTER TABLE `rp_machote_comentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_machote_revision`
--

DROP TABLE IF EXISTS `rp_machote_revision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_machote_revision` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) NOT NULL,
  `machote_version` varchar(50) DEFAULT NULL,
  `estado` enum('en_revision','acordado','cancelado') DEFAULT 'en_revision',
  `aprobado_admin` tinyint(1) DEFAULT 0,
  `aprobado_empresa` tinyint(1) DEFAULT 0,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cerrado_en` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rev_emp` (`empresa_id`,`estado`),
  CONSTRAINT `fk_rev_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `rp_empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_machote_revision`
--

LOCK TABLES `rp_machote_revision` WRITE;
/*!40000 ALTER TABLE `rp_machote_revision` DISABLE KEYS */;
INSERT INTO `rp_machote_revision` VALUES (1,1,'Inst v1.2','en_revision',1,1,'2025-10-09 22:18:52','2025-10-26 04:46:03','2025-10-09 22:24:29');
/*!40000 ALTER TABLE `rp_machote_revision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_machote_revision_file`
--

DROP TABLE IF EXISTS `rp_machote_revision_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_machote_revision_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msg_id` bigint(20) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `size_bytes` int(11) DEFAULT NULL,
  `subido_por` enum('admin','empresa') NOT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_file_msg` (`msg_id`),
  CONSTRAINT `fk_file_msg` FOREIGN KEY (`msg_id`) REFERENCES `rp_machote_revision_msg` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_machote_revision_file`
--

LOCK TABLES `rp_machote_revision_file` WRITE;
/*!40000 ALTER TABLE `rp_machote_revision_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `rp_machote_revision_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_machote_revision_msg`
--

DROP TABLE IF EXISTS `rp_machote_revision_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_machote_revision_msg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `revision_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `asunto` varchar(200) DEFAULT NULL,
  `cuerpo` text NOT NULL,
  `autor` enum('admin','empresa') NOT NULL,
  `estatus` enum('abierto','resuelto') DEFAULT 'abierto',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_msg_rev` (`revision_id`),
  KEY `idx_msg_parent` (`parent_id`),
  KEY `idx_msg_status` (`revision_id`,`estatus`),
  CONSTRAINT `fk_msg_parent` FOREIGN KEY (`parent_id`) REFERENCES `rp_machote_revision_msg` (`id`),
  CONSTRAINT `fk_msg_rev` FOREIGN KEY (`revision_id`) REFERENCES `rp_machote_revision` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_machote_revision_msg`
--

LOCK TABLES `rp_machote_revision_msg` WRITE;
/*!40000 ALTER TABLE `rp_machote_revision_msg` DISABLE KEYS */;
INSERT INTO `rp_machote_revision_msg` VALUES (1,1,NULL,'Ajustar vigencia','Proponemos 18 meses','empresa','resuelto','2025-10-09 22:19:06','2025-10-09 22:24:18'),(2,1,1,NULL,'Recibido, lo revisamos','admin','abierto','2025-10-09 22:19:15','2025-10-09 22:19:15');
/*!40000 ALTER TABLE `rp_machote_revision_msg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_portal_acceso`
--

DROP TABLE IF EXISTS `rp_portal_acceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rp_portal_acceso` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) NOT NULL,
  `convenio_id` bigint(20) DEFAULT NULL,
  `token` char(36) NOT NULL,
  `nip` varchar(10) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `expiracion` datetime DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_portal_token` (`token`),
  KEY `idx_portal_empresa` (`empresa_id`,`activo`),
  KEY `fk_portal_convenio` (`convenio_id`),
  CONSTRAINT `fk_portal_convenio` FOREIGN KEY (`convenio_id`) REFERENCES `rp_convenio` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_portal_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `rp_empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_portal_acceso`
--

LOCK TABLES `rp_portal_acceso` WRITE;
/*!40000 ALTER TABLE `rp_portal_acceso` DISABLE KEYS */;
INSERT INTO `rp_portal_acceso` VALUES (1,1,NULL,'11111111-1111-1111-1111-111111111111',NULL,1,NULL,'2025-09-09 21:49:01'),(2,2,NULL,'22222222-2222-2222-2222-222222222222',NULL,1,NULL,'2025-09-09 21:49:01'),(3,3,NULL,'33333333-3333-3333-3333-333333333333',NULL,1,NULL,'2025-09-09 21:49:01');
/*!40000 ALTER TABLE `rp_portal_acceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_convenio`
--

DROP TABLE IF EXISTS `ss_convenio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_convenio` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ss_empresa_id` bigint(20) NOT NULL,
  `estatus` enum('pendiente','vigente','vencido') DEFAULT 'pendiente',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `version_actual` varchar(50) DEFAULT NULL,
  `creado_en` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_ssconvenio_ssempresa` (`ss_empresa_id`),
  CONSTRAINT `fk_ssconvenio_ssempresa` FOREIGN KEY (`ss_empresa_id`) REFERENCES `ss_empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_convenio`
--

LOCK TABLES `ss_convenio` WRITE;
/*!40000 ALTER TABLE `ss_convenio` DISABLE KEYS */;
INSERT INTO `ss_convenio` VALUES (1,1,'vigente','2025-01-01','2025-12-31','v1','2025-09-22 05:40:37'),(2,2,'vigente','2025-02-01','2025-12-31','v1','2025-09-22 05:40:37'),(3,3,'pendiente','2025-03-01',NULL,'v0.9','2025-09-22 05:40:37');
/*!40000 ALTER TABLE `ss_convenio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_descarga_log`
--

DROP TABLE IF EXISTS `ss_descarga_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_descarga_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `estudiante_id` bigint(20) NOT NULL,
  `periodo_id` bigint(20) NOT NULL,
  `doc_tipo_id` bigint(20) NOT NULL,
  `descargado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_descarga_est_per` (`estudiante_id`,`periodo_id`),
  KEY `idx_descarga_doc` (`doc_tipo_id`),
  KEY `fk_descarga_periodo` (`periodo_id`),
  CONSTRAINT `fk_descarga_doctipo` FOREIGN KEY (`doc_tipo_id`) REFERENCES `ss_doc_tipo` (`id`),
  CONSTRAINT `fk_descarga_est` FOREIGN KEY (`estudiante_id`) REFERENCES `ss_estudiante` (`id`),
  CONSTRAINT `fk_descarga_periodo` FOREIGN KEY (`periodo_id`) REFERENCES `ss_periodo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_descarga_log`
--

LOCK TABLES `ss_descarga_log` WRITE;
/*!40000 ALTER TABLE `ss_descarga_log` DISABLE KEYS */;
INSERT INTO `ss_descarga_log` VALUES (1,1,1,1,'2025-09-22 10:05:00','201.141.1.10','Mozilla/5.0 (Windows 10)'),(2,1,1,2,'2025-09-22 10:06:30','201.141.1.10','Mozilla/5.0 (Windows 10)'),(3,2,3,1,'2025-09-25 08:15:20','187.190.2.20','Mozilla/5.0 (Android 13)');
/*!40000 ALTER TABLE `ss_descarga_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_doc`
--

DROP TABLE IF EXISTS `ss_doc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_doc` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `periodo_id` bigint(20) NOT NULL,
  `tipo_id` bigint(20) NOT NULL,
  `ruta` varchar(255) DEFAULT NULL,
  `recibido` tinyint(1) NOT NULL DEFAULT 0,
  `estatus` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `observacion` text DEFAULT NULL,
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_doc_ss` (`periodo_id`,`tipo_id`),
  KEY `idx_docss_periodo` (`periodo_id`,`estatus`),
  KEY `fk_docss_tipo` (`tipo_id`),
  CONSTRAINT `fk_docss_periodo` FOREIGN KEY (`periodo_id`) REFERENCES `ss_periodo` (`id`),
  CONSTRAINT `fk_docss_tipo` FOREIGN KEY (`tipo_id`) REFERENCES `ss_doc_tipo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_doc`
--

LOCK TABLES `ss_doc` WRITE;
/*!40000 ALTER TABLE `ss_doc` DISABLE KEYS */;
INSERT INTO `ss_doc` VALUES (1,1,1,'/ss/1/periodo1/carta_presentacion.pdf',1,'aprobado',NULL,'2025-09-09 21:49:52'),(2,1,2,'/ss/1/periodo1/oficio_aceptacion.pdf',1,'rechazado','Falta sello de la empresa','2025-09-09 21:49:52'),(3,2,3,NULL,0,'pendiente',NULL,'2025-09-09 21:50:01'),(4,3,1,NULL,1,'aprobado',NULL,'2025-09-09 21:50:14'),(5,3,2,NULL,0,'pendiente','Falta Oficio de aceptacion para subir y el baje y el firme','2025-10-01 06:33:19');
/*!40000 ALTER TABLE `ss_doc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_doc_global`
--

DROP TABLE IF EXISTS `ss_doc_global`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_doc_global` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo_id` bigint(20) NOT NULL,
  `nombre` varchar(191) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ruta` varchar(255) NOT NULL,
  `estatus` enum('activo','inactivo') DEFAULT 'activo',
  `creado_en` datetime DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_ssdocglobal_tipo` (`tipo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_doc_global`
--

LOCK TABLES `ss_doc_global` WRITE;
/*!40000 ALTER TABLE `ss_doc_global` DISABLE KEYS */;
INSERT INTO `ss_doc_global` VALUES (1,1,'Carta de Presentación','Documento oficial que el estudiante debe entregar a la empresa antes de iniciar el servicio social.','uploads/documentos_globales/carta_presentacion.pdf','activo','2025-10-01 07:23:33','2025-10-01 07:23:33'),(2,2,'Carta de Aceptación','Formato que la empresa debe firmar y sellar para confirmar la aceptación del estudiante.','uploads/documentos_globales/carta_aceptacion.pdf','activo','2025-10-01 07:23:33','2025-10-01 07:23:33'),(3,3,'Plan de Trabajos','Formato que detalla las actividades, objetivos y metas que el estudiante realizará durante su servicio social.\r\ntest test test test test test test test test test test test','uploads/documentos_globales/plan_trabajo.pdf','activo','2025-10-01 07:23:33','2025-10-01 09:52:43');
/*!40000 ALTER TABLE `ss_doc_global` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_doc_tipo`
--

DROP TABLE IF EXISTS `ss_doc_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_doc_tipo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `periodo_num` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_doc_tipo_ss` (`periodo_num`,`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_doc_tipo`
--

LOCK TABLES `ss_doc_tipo` WRITE;
/*!40000 ALTER TABLE `ss_doc_tipo` DISABLE KEYS */;
INSERT INTO `ss_doc_tipo` VALUES (1,1,'Carta de presentación',NULL,1),(2,1,'Oficio de aceptación',NULL,1),(3,2,'Reporte parcial',NULL,1),(4,3,'Reporte final',NULL,1),(5,3,'Carta de término',NULL,1);
/*!40000 ALTER TABLE `ss_doc_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_empresa`
--

DROP TABLE IF EXISTS `ss_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_empresa` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `contacto_nombre` varchar(191) DEFAULT NULL,
  `contacto_email` varchar(191) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` varchar(191) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `creado_en` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_empresa`
--

LOCK TABLES `ss_empresa` WRITE;
/*!40000 ALTER TABLE `ss_empresa` DISABLE KEYS */;
INSERT INTO `ss_empresa` VALUES (1,'Universidad Tecnológica del Centro','Dra. María López','maria.lopez@utc.edu','555-1234567','Av. Principal 123, Ciudad A','activo','2025-09-22 05:40:37'),(2,'Hospital General San José','Dr. Juan Pérez','juan.perez@hgsj.mx','555-9876543','Calle Salud 45, Ciudad B','activo','2025-09-22 05:40:37'),(3,'Biblioteca Municipal Central TEST','Lic. Torres Torres','ana.torres@biblio.gob.mx.TEST','555-4567999','Plaza Mayor s/n, Ciudad C. TEST','inactivo','2025-09-22 05:40:37'),(4,'Empreza Test','ING. Test','Test@test.com','2233444433','test, av test. CP 44444','activo','2025-10-02 09:10:27'),(6,'NEW EMPRESA','ING. Hector','hector@tech.com','333333333','tequila, av tech 3333','activo','2025-10-02 10:08:55');
/*!40000 ALTER TABLE `ss_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_estudiante`
--

DROP TABLE IF EXISTS `ss_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_estudiante` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `carrera` varchar(100) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  `plaza_id` bigint(20) DEFAULT NULL,
  `dependencia_asignada` varchar(191) DEFAULT NULL,
  `proyecto` varchar(255) DEFAULT NULL,
  `periodo_inicio` date DEFAULT NULL,
  `periodo_fin` date DEFAULT NULL,
  `horas_acumuladas` int(11) DEFAULT 0,
  `horas_requeridas` int(11) DEFAULT 480,
  `estado_servicio` enum('pendiente','en_curso','concluido','cancelado') DEFAULT 'pendiente',
  `asesor_interno` varchar(191) DEFAULT NULL,
  `documentos_entregados` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_estudiante_matricula` (`matricula`),
  KEY `fk_estudiante_plaza` (`plaza_id`),
  CONSTRAINT `fk_estudiante_plaza` FOREIGN KEY (`plaza_id`) REFERENCES `ss_plaza` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_estudiante`
--

LOCK TABLES `ss_estudiante` WRITE;
/*!40000 ALTER TABLE `ss_estudiante` DISABLE KEYS */;
INSERT INTO `ss_estudiante` VALUES (1,'Ana Rodríguez','A012345','Ing. Informática',8,NULL,'Secretaría de Innovación y Tecnología del Estado','Desarrollo de Sistema de Control de Documentos para Dependencias Gubernamentales','2025-02-01','2025-07-31',120,480,'en_curso','Ing. Fernando López','Carta de presentación, Carta de aceptación, Plan de trabajo','El estudiante ha mostrado excelente desempeño en el área asignada. Pendiente entregar reporte intermedio.','ana.rodriguez@alumno.edu.mx','3310000001','2025-09-09 21:49:09'),(2,'Diego López','A067890','Ing. Sistemas',9,11,'Dirección de Tecnologías Municipales','Implementación de un sistema web de gestión de reportes ciudadanos','2025-03-01','2025-08-30',80,480,'en_curso','Ing. Patricia Morales','Carta de presentación, Carta de aceptación','Buen desempeño inicial. Falta entregar plan de trabajo.','diego.lopez@alumno.edu.mx','3310000002','2025-09-09 21:49:09'),(3,'Karla Méndez','A045678','Ing. Gestión',7,7,'Secretaría de Planeación y Desarrollo','Optimización del proceso de gestión de recursos humanos con software interno','2025-02-15','2025-07-31',200,480,'en_curso','Mtro. Alejandro Rivera','Carta de presentación, Carta de aceptación, Plan de trabajo','En proceso de cumplir con el reporte intermedio. Participación activa en el proyecto.','karla.mendez@alumno.edu.mx','3310000003','2025-09-09 21:49:09'),(4,'Juan Pérez López','20230045','Ingeniería Informática',8,NULL,'Centro Estatal de Innovación Tecnológica','Desarrollo de un módulo de autenticación para intranet institucional','2025-02-01','2025-07-15',300,480,'en_curso','Ing. Laura Fernández','Carta de presentación, Carta de aceptación, Plan de trabajo, Reporte intermedio','Avance del 60% del proyecto. Buen desempeño técnico.','juanp@escuela.mx','555-123-4567','2025-09-18 04:47:37'),(5,'hector ruiz','c230023','ING-Informatica',9,NULL,'Departamento de Innovación Educativa','Plataforma web para seguimiento de prácticas profesionales','2025-02-20','2025-07-20',150,480,'en_curso','Lic. Sofía Martínez','Carta de presentación, Carta de aceptación','Debe entregar plan de trabajo y comenzar con reporte de avance.','test@edutest.com','112211221','2025-09-18 06:21:30'),(6,'marten','c001001','ING-Informatica',8,NULL,'Secretaría de Economía Digital','Sistema de registro de proveedores en línea','2025-03-01','2025-08-01',50,480,'pendiente','Ing. Rodrigo González','Carta de presentación','Esperando aceptación de dependencia. No ha iniciado el proyecto.','test@edutest.com','332233232','2025-09-18 21:45:25'),(7,'marteenenene','C239999','ING-INFORMATICAA',7,11,'Unidad de Sistemas del Ayuntamiento','Módulo para gestión de solicitudes en portal ciudadano','2025-02-10','2025-07-30',300,480,'en_curso','Mtra. Adriana Vargas','Carta de presentación, Carta de aceptación, Plan de trabajo','Ha tenido retrasos menores por cambio en requerimientos.','test22@edutest.com','123443213','2025-09-18 21:46:09'),(9,'Oliver','OLI001199','ING.CIVIL',NULL,NULL,NULL,NULL,NULL,NULL,0,480,'pendiente',NULL,NULL,NULL,'Oliver@tech.com','3344334433','2025-09-28 03:44:50');
/*!40000 ALTER TABLE `ss_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_periodo`
--

DROP TABLE IF EXISTS `ss_periodo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_periodo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `servicio_id` bigint(20) NOT NULL,
  `numero` int(11) NOT NULL,
  `nombre_periodo` varchar(100) DEFAULT NULL,
  `estatus` enum('abierto','en_revision','completado') NOT NULL DEFAULT 'abierto',
  `abierto_en` datetime NOT NULL DEFAULT current_timestamp(),
  `cerrado_en` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_periodo_serv_num` (`servicio_id`,`numero`),
  KEY `idx_periodo_estatus` (`estatus`),
  CONSTRAINT `fk_periodo_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `ss_servicio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_periodo`
--

LOCK TABLES `ss_periodo` WRITE;
/*!40000 ALTER TABLE `ss_periodo` DISABLE KEYS */;
INSERT INTO `ss_periodo` VALUES (1,1,1,'Feb?Jul 2025','en_revision','2025-09-22 09:00:00',NULL),(2,1,2,'Ago?Dic 2025','abierto','2025-10-20 09:00:00',NULL),(3,2,1,'Feb?Jul 2024','abierto','2025-09-22 09:00:00','2025-10-31 00:00:00'),(4,3,1,'Ago?Dic 2024','en_revision','2025-09-29 06:29:00',NULL);
/*!40000 ALTER TABLE `ss_periodo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_plaza`
--

DROP TABLE IF EXISTS `ss_plaza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_plaza` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `ss_empresa_id` bigint(20) DEFAULT NULL,
  `ss_convenio_id` bigint(20) DEFAULT NULL,
  `direccion` varchar(191) DEFAULT NULL,
  `cupo` int(11) DEFAULT NULL,
  `periodo_inicio` date DEFAULT NULL,
  `periodo_fin` date DEFAULT NULL,
  `modalidad` enum('presencial','hibrida','remota') DEFAULT 'presencial',
  `actividades` text DEFAULT NULL,
  `requisitos` text DEFAULT NULL,
  `responsable_nombre` varchar(191) DEFAULT NULL,
  `responsable_puesto` varchar(191) DEFAULT NULL,
  `responsable_email` varchar(191) DEFAULT NULL,
  `responsable_tel` varchar(50) DEFAULT NULL,
  `ubicacion` varchar(191) DEFAULT NULL,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `observaciones` text DEFAULT NULL,
  `creado_en` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_plaza_ss_empresa` (`ss_empresa_id`),
  KEY `fk_plaza_ss_convenio` (`ss_convenio_id`),
  CONSTRAINT `fk_plaza_ss_convenio` FOREIGN KEY (`ss_convenio_id`) REFERENCES `ss_convenio` (`id`),
  CONSTRAINT `fk_plaza_ss_empresa` FOREIGN KEY (`ss_empresa_id`) REFERENCES `ss_empresa` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_plaza`
--

LOCK TABLES `ss_plaza` WRITE;
/*!40000 ALTER TABLE `ss_plaza` DISABLE KEYS */;
INSERT INTO `ss_plaza` VALUES (4,'Auxiliar de Laboratorio',1,1,'Campus Central, Edificio B',5,'2025-02-01','2025-07-31','presencial','Apoyo en prácticas de laboratorio, limpieza de material, registro de resultados.','Estudiante de Ingeniería Química o afín. Conocimientos básicos en seguridad de laboratorio.','Dra. Laura Sánchez','Coordinadora de Laboratorio','lsanchez@universidad.edu.mx','555-123-4567','Campus Central, Edificio B, Laboratorio 2','activa','Se requiere bata y gafas de seguridad.','2025-09-23 00:52:54'),(5,'Soporte de Sistemas',1,1,'Campus Central, Edificio C',3,'2025-03-01','2025-08-30','presencial','Mantenimiento de equipos, instalación de software, soporte a usuarios.','Estudiante de Informática o Sistemas. Conocimiento en Windows/Linux.','Ing. Marco Gómez','Jefe de Soporte Técnico','mgomez@universidad.edu.mx','555-987-6543','Edificio C, Oficina de TI','activa','Disponibilidad de medio tiempo.','2025-09-23 00:52:54'),(6,'Asistente de Enfermería',2,2,'Área de Urgencias',10,'2025-01-15','2025-06-15','presencial','Apoyo en atención básica a pacientes, registro de signos vitales, control de insumos médicos.','Estudiante de Enfermería. Conocimientos básicos en primeros auxilios.','Lic. Carmen Rivera','Jefa de Enfermería','crivera@hospital.org.mx','555-456-7890','Hospital General, Área de Urgencias','activa','Turnos rotativos.','2025-09-23 00:52:54'),(7,'Archivo Clínico',2,2,'Edificio Administrativo',2,'2025-02-10','2025-07-30','presencial','Organización de expedientes, digitalización de archivos, apoyo en consultas.','Estudiante de Archivonomía, Administración o afín.','Lic. Roberto López','Encargado de Archivo','rlopez@hospital.org.mx','555-765-4321','Edificio Administrativo, Archivo Clínico','activa','Trabajo principalmente en oficina.','2025-09-23 00:52:54'),(8,'Gestión Cultural',3,3,'Sala Principal',4,'2025-03-05','2025-08-15','presencial','Apoyo en organización de eventos culturales, difusión de actividades, atención a público.','Estudiante de Gestión Cultural, Comunicación o afín. Habilidades de comunicación.','Mtro. Alejandro Pérez','Coordinador de Cultura','aperez@instituto-cultural.mx','555-321-7894','Sala Principal del Instituto Cultural','activa','Disponibilidad algunos fines de semana.','2025-09-23 00:52:54'),(9,'Auxiliar para vinculacion',1,1,'Andares 33',3,'2025-10-01','2025-10-31','remota','se require monitorear Incidentes','Tener computadora propia e Internet','Francisco','Supervisor','francisco@hcl.com','33 133 13333','Guadalajara','activa','Horario: L - V 9:00 AM - 3:00 PM\nRevisar equipo antes de asignar estudiante','2025-09-23 02:46:34'),(11,'IT LOCAL',1,1,'Andares 33',3,'2025-10-01','2025-10-31','presencial','Ponchar cables Monitorear','Laptop equipo ponchar','Marcos','Jefe Sistema','marco@tech.com','122342342','Tequila','activa','Horario: L - V 9:AM - 3PM\nSe dara ayuda','2025-09-25 01:56:04'),(22,'Soporte Técnico',1,1,'Campus Central, Edificio B',3,'2025-02-01','2025-07-31','presencial','Brindar soporte a usuarios, mantenimiento preventivo de equipos, instalación de software.','Estudiante de Informática o Sistemas. Conocimiento básico en Windows/Linux.','Ing. Luis Hernández','Jefe de Soporte','lhernandez@empresa.com','555-123-4567','Campus Central - Oficina de TI','activa','Horario: Lunes a Viernes 9:00 - 14:00','2025-09-30 02:29:19'),(23,'Asistente Administrativo',2,2,'Edificio Administrativo, Oficina 102',2,'2025-03-01','2025-08-30','hibrida','Apoyo en gestión documental, atención telefónica, elaboración de reportes.','Estudiante de Administración o afín. Manejo de paquetería Office.','Lic. Marta Pérez','Coordinadora Administrativa','mperez@empresa.com','555-987-6543','Campus Central - Oficina 102','activa','Disponibilidad para asistir a juntas presenciales 1 vez por semana.','2025-09-30 02:29:19'),(24,'Analista de Datos Junior',3,3,'Remoto',4,'2025-04-01','2025-09-30','remota','Recolección, limpieza y análisis de datos. Elaboración de reportes en Excel y Power BI.','Estudiante de Ingeniería en Sistemas, Informática o Matemáticas. Conocimientos en Excel y SQL.','Mtro. Ricardo Flores','Analista Senior','rflores@empresa.com','555-654-7890','Trabajo 100% remoto','activa','Requiere conexión a internet estable y disponibilidad para reuniones semanales.','2025-09-30 02:29:19');
/*!40000 ALTER TABLE `ss_plaza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ss_servicio`
--

DROP TABLE IF EXISTS `ss_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ss_servicio` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `estudiante_id` bigint(20) NOT NULL,
  `plaza_id` bigint(20) DEFAULT NULL,
  `estatus` enum('prealta','activo','concluido','cancelado') NOT NULL DEFAULT 'prealta',
  `horas_acumuladas` int(11) NOT NULL DEFAULT 0,
  `observaciones` text DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_serv_est` (`estudiante_id`,`estatus`),
  KEY `idx_serv_plaza` (`plaza_id`),
  CONSTRAINT `fk_serv_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `ss_estudiante` (`id`),
  CONSTRAINT `fk_serv_plaza` FOREIGN KEY (`plaza_id`) REFERENCES `ss_plaza` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ss_servicio`
--

LOCK TABLES `ss_servicio` WRITE;
/*!40000 ALTER TABLE `ss_servicio` DISABLE KEYS */;
INSERT INTO `ss_servicio` VALUES (1,1,NULL,'activo',40,NULL,'2025-09-09 21:49:26'),(2,2,11,'concluido',10,'Se cerro El Servicio Exitosamente Veamos.\r\nFecha de cierre: 2025-10-01','2025-09-09 21:49:26'),(3,3,NULL,'prealta',0,NULL,'2025-09-09 21:49:26'),(4,1,4,'activo',40,NULL,'2025-09-30 02:17:10'),(5,2,5,'activo',20,NULL,'2025-09-30 02:17:10'),(6,3,6,'prealta',0,NULL,'2025-09-30 02:17:10');
/*!40000 ALTER TABLE `ss_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('res_admin','editor','capturista','ss_admin','estudiante') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `estudiante_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuario_email` (`email`),
  KEY `idx_usuario_rol` (`rol`),
  KEY `fk_usuario_estudiante` (`estudiante_id`),
  CONSTRAINT `fk_usuario_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `ss_estudiante` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Admin Residencia','admin.res@test.com','$2y$10$MpDYyW5ktzewZev6yyww/.aMnJK.1c6lU5xxEVb93FIBRGl674SgK','res_admin',1,'2025-09-16 04:28:53',NULL),(2,'Admin Servicio Social','admin.ss@test.com','$2y$2y$10$2NMwBI.2bvR8Oi421.bXt.e9Wy0DteZ8Mn5GBEiXSt1px76Mm4Lnq','ss_admin',1,'2025-09-16 04:28:53',NULL),(3,'Estudiante Demo','estu.demo@test.com','$2y$2y$10$dL176gHL3v1MD9tfDxx0befSnPafLYGzXI1pB6WmGm97Y0uCNuqoC','estudiante',1,'2025-09-16 04:28:53',NULL),(5,'Juan Pérez López','juanp@escuela.mx','$2y$10$KVeJuiSv4IeWhcbz4UQcYeq8h3ChRUCbfEoxJbV5Qq4KyX7AZfyue','estudiante',1,'2025-09-18 04:51:03',4),(6,'hector ruiz','test@edutest.com','$2y$10$tW0xYRX4wHnNIwveP5liEOvoO9SpvyBWDq3de5E8Y6DLko1/f3ZGe','estudiante',1,'2025-09-18 06:21:30',5),(8,'marteenenene','test22@edutest.com','$2y$10$hId85icgg/PJ8Z4NlEp3IOTdpvIC8oktaJbq0OS..Gv/IjN4LE2vO','estudiante',1,'2025-09-18 21:46:10',7),(10,'Oliver','Oliver@tech.com','$2y$10$qKBIXDNuI4zr/dVSZbg7Wuw864nEH7fdhcbBkkkGu7t0BsM7vDcSC','estudiante',1,'2025-09-28 03:44:50',9);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31  7:21:37
