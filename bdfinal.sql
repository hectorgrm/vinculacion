CREATE DATABASE  IF NOT EXISTS `nessuste_vinculacion` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `nessuste_vinculacion`;
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
