-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: mayorazgoasesores
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.22.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `link` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'Nómina','Error en la nómina','Descuento. La Audiencia Nacional [AN 23-12-2022] ha confirmado que una empresa puede descontar en nómina (compensación de deudas) las cantidades abonadas de más por error cuando no existe controversia sobre la existencia de la deuda y se trata de cantidades líquidas y exigibles.\r\n\r\nDiscrepancia. Recuerde que, en general, la empresa debería recurrir a un procedimiento judicial para reclamar la deuda a los trabajadores si existiese controversia sobre ella; además, no podría aplicar la regla de la compensación de deudas de forma unilateral. \r\n\r\n¡Atención! En el caso enjuiciado, no había controversia sobre la existencia de la deuda (estaba claro que la empresa había\r\npagado un complemento en exceso) y sólo existía discrepancia respecto a la vía que debía seguir la empresa para compensarla.\r\n\r\nCompensación. Por ello, se concluye que la aplicación de la compensación en nómina es adecuada.\r\n\r\nFuente: Apuntes & Consejos Laborales','2023-04-27 00:00:00','2023-04-27 08:46:40','2023-04-27 08:46:40',0),(2,'Despido Nulo','Despido del Trabajador en situación de IT','Nulidad. Los Juzgados de lo Social han empezado a declarar nulos algunos despidos de trabajadores que se encuentran en situación de baja por IT.\r\n\r\nApunte. El Juzgado Social n.º 1 de Gijón [JS1 Gijón 15-11-2022] declaró nulo el despido de una trabajadora en situación de IT tras acreditarse que la causa de dicho despido había sido exclusivamente la situación de enfermedad.\r\n\r\nDiscriminación. Recuerde que, tras la publicación de la Ley 15/2022, la enfermedad es una causa de discriminación autónoma, sin necesidad de que sea de larga duración o se equipare a la discapacidad.\r\n\r\n¡Atención! Si el trabajador aporta indicios de discriminación, le corresponderá a la empresa acreditar una justificación objetiva y razonable para el despido.\r\n\r\nIndemnización. La declaración de nulidad del despido conlleva la readmisión del trabajador (con abono de salarios de tramitación) y el abono de una indemnización adicional.\r\n\r\nFuente: Apuntes & Consejos Laborales','2023-05-05 00:00:00','2023-05-05 09:41:33','2023-05-05 09:43:21',0),(4,'Real Decreto-Ley 4/2023 de 11 de mayo','Prohibición de trabajar al aire libre con calor extremo','https://www.iberley.es/noticias/publicada-prohibicion-trabajar-aire-libre-calor-extremo-32465','2023-05-12 00:00:00','2023-05-12 07:59:51','2023-05-31 18:02:23',1),(7,'Se flexibiliza la jubilación anticipada para las personas con discapacidad','Es publicado en el BOE del 17 de mayo de 2023 el Real Decreto 370/2023, de 16 de mayo, por el que se modifica el Real Decreto 1851/2009, de 4 de diciembre, por el que se desarrolla el artículo 161.bis de la Ley General de la Seguridad Social en cuanto a l','https://www.iberley.es/noticias/se-flexibiliza-jubilacion-anticipada-las-personas-discapacidad-32474','2023-05-17 00:00:00','2023-05-17 11:49:49','2023-05-31 17:59:27',1),(8,'El despido improcedente en la renta: ¿cómo tributan los salarios de tramitación y la indemnización?','A través de este artículo se analiza el tratamiento que se ha de dar a los salarios de tramitación y a la indemnización por despido improcedente en el IRPF del trabajador despido, con estudio de la correspondiente normativa y apoyo en múltiples resolucion','https://www.iberley.es/revista/el-despido-improcedente-renta-como-tributan-los-salarios-tramitacion-e-indemnizacion-784','2023-05-19 00:00:00','2023-05-19 08:59:43','2023-05-31 18:00:06',1),(9,'Derechos de los trabajadores para votar en las elecciones del 28 de mayo de 2023','En caso de que el horario del colegio electoral coincida con la jornada laboral existe derecho a un permiso retribuido de hasta cuatro horas libres o su parte proporcional en caso de trabajo a tiempo parcial. Cuando la persona trabajadora fuese designado','https://www.iberley.es/noticias/elecciones-28-mayo-2023-derechos-trabajadores-32496','2023-05-25 00:00:00','2023-05-25 07:38:07','2023-05-31 17:59:32',1),(10,'En vigor desde el 1 de junio de 2023 la nueva baja por menstruación incapacitante',NULL,'https://www.iberley.es/noticias/en-vigor-1-junio-2023-nueva-baja-menstruacion-incapacitante-32528','2023-06-02 00:00:00','2023-06-02 08:30:29','2023-06-02 08:32:16',1),(11,'Acuerdo para el Estatuto del Becario',NULL,'https://www.iberley.es/noticias/acuerdo-estatuto-becario-32553','2023-06-12 00:00:00','2023-06-12 07:31:18','2023-06-12 07:32:30',1),(12,'El TS anula un cambio de horario permitido en el convenio colectivo de Contact Center por existir una condición más beneficiosa',NULL,'https://www.iberley.es/noticias/el-ts-anula-un-cambio-horario-permitido-convenio-colectivo-existir-una-condicion-mas-beneficiosa-32565','2023-06-20 00:00:00','2023-06-20 14:03:30','2023-06-20 14:03:30',0);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-27 20:45:26
