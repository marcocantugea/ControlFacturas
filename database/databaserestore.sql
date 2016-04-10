-- MySQL dump 10.13  Distrib 5.7.9, for osx10.9 (x86_64)
--
-- Host: 127.0.0.1    Database: p_controlfacturas
-- ------------------------------------------------------
-- Server version	5.5.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `t_estados`
--

DROP TABLE IF EXISTS `t_estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_estados` (
  `idestado` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(77) COLLATE latin1_general_ci DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idestado`),
  UNIQUE KEY `idestado_UNIQUE` (`idestado`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_estados`
--

LOCK TABLES `t_estados` WRITE;
/*!40000 ALTER TABLE `t_estados` DISABLE KEYS */;
INSERT INTO `t_estados` VALUES (1,'Pagada',1),(2,'Retrasada',1),(3,'Cancelada',1);
/*!40000 ALTER TABLE `t_estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_facturaestados`
--

DROP TABLE IF EXISTS `t_facturaestados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_facturaestados` (
  `idestado` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(77) COLLATE latin1_general_ci DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idestado`),
  UNIQUE KEY `idestado_UNIQUE` (`idestado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_facturaestados`
--

LOCK TABLES `t_facturaestados` WRITE;
/*!40000 ALTER TABLE `t_facturaestados` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_facturaestados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_facturas`
--

DROP TABLE IF EXISTS `t_facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_facturas` (
  `idfactura` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `archivoruta` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `vencimiento` datetime DEFAULT NULL,
  `montoactual` float DEFAULT NULL,
  `idestado` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `numerofactura` int(11) DEFAULT NULL,
  `numeroorden` int(11) DEFAULT NULL,
  PRIMARY KEY (`idfactura`),
  UNIQUE KEY `idfactura_UNIQUE` (`idfactura`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_facturas`
--

LOCK TABLES `t_facturas` WRITE;
/*!40000 ALTER TABLE `t_facturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_facturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_facturaspagos`
--

DROP TABLE IF EXISTS `t_facturaspagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_facturaspagos` (
  `idfacturapagos` int(11) NOT NULL AUTO_INCREMENT,
  `idfactura` int(11) DEFAULT NULL,
  `montoactual` float DEFAULT NULL,
  `pagoparcial` float DEFAULT NULL,
  `montoantespago` float DEFAULT NULL,
  `fechadepago` datetime DEFAULT NULL,
  `comentarios` varchar(1024) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`idfacturapagos`),
  UNIQUE KEY `idfacturapagos_UNIQUE` (`idfacturapagos`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_facturaspagos`
--

LOCK TABLES `t_facturaspagos` WRITE;
/*!40000 ALTER TABLE `t_facturaspagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_facturaspagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_users`
--

DROP TABLE IF EXISTS `t_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `email` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `iduser_UNIQUE` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_users`
--

LOCK TABLES `t_users` WRITE;
/*!40000 ALTER TABLE `t_users` DISABLE KEYS */;
INSERT INTO `t_users` VALUES (25,'mcantu','de8878a388b1d0633dceb3ccc8144ccc',1,'marco.cantu.g@gmail.com'),(27,'tomy','85d5c2ff5dc614cc7eb017c6cfdba0d5',1,'puntotec@gmx.com');
/*!40000 ALTER TABLE `t_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-27 12:27:09
