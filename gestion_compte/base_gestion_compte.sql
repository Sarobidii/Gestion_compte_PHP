-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: gestion_compte
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.22.04.1

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
-- Table structure for table `amis`
--

DROP TABLE IF EXISTS `amis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `amis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_compte` int NOT NULL,
  `id_compte_amis` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compte` (`id_compte`),
  KEY `id_compte_amis` (`id_compte_amis`),
  CONSTRAINT `amis_ibfk_1` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`),
  CONSTRAINT `amis_ibfk_2` FOREIGN KEY (`id_compte_amis`) REFERENCES `compte` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amis`
--

LOCK TABLES `amis` WRITE;
/*!40000 ALTER TABLE `amis` DISABLE KEYS */;
INSERT INTO `amis` VALUES (2,1,2);
/*!40000 ALTER TABLE `amis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commentaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_compte` int NOT NULL,
  `id_publication` int NOT NULL,
  `contenu` text NOT NULL,
  `date_lance` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compte` (`id_compte`),
  KEY `id_publication` (`id_publication`),
  CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`),
  CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_publication`) REFERENCES `publication` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentaire`
--

LOCK TABLES `commentaire` WRITE;
/*!40000 ALTER TABLE `commentaire` DISABLE KEYS */;
INSERT INTO `commentaire` VALUES (3,1,2,'I miss you','2024-09-24'),(4,1,3,'Me too','2024-09-24'),(5,1,2,'When you come here ?','2024-09-24'),(6,1,1,'Where are you from?','2024-09-24');
/*!40000 ALTER TABLE `commentaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compte`
--

DROP TABLE IF EXISTS `compte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compte`
--

LOCK TABLES `compte` WRITE;
/*!40000 ALTER TABLE `compte` DISABLE KEYS */;
INSERT INTO `compte` VALUES (1,'Ratsilavoson','Sarobidy','finaritratsilavo@gmail.com','12345'),(2,'Ratsilavoson','Fitiavana','fitiavanaratsila@gmail.com','azerty');
/*!40000 ALTER TABLE `compte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication`
--

DROP TABLE IF EXISTS `publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publication` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_compte` int NOT NULL,
  `contenu` text NOT NULL,
  `date_lance` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compte` (`id_compte`),
  CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication`
--

LOCK TABLES `publication` WRITE;
/*!40000 ALTER TABLE `publication` DISABLE KEYS */;
INSERT INTO `publication` VALUES (1,1,'Hello world !\r\nI\'m Sarobidy.','2024-09-24 05:59:54'),(2,1,'What\'s up !','2024-09-24 06:12:06'),(3,2,'I love football.','2024-09-24 06:59:40');
/*!40000 ALTER TABLE `publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction`
--

DROP TABLE IF EXISTS `reaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reaction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_reaction` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction`
--

LOCK TABLES `reaction` WRITE;
/*!40000 ALTER TABLE `reaction` DISABLE KEYS */;
INSERT INTO `reaction` VALUES (1,'J\'aime'),(2,'J\'adore'),(3,'Haha'),(4,'Wouah'),(5,'Triste'),(6,'En colère');
/*!40000 ALTER TABLE `reaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_commentaire`
--

DROP TABLE IF EXISTS `reaction_commentaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reaction_commentaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_compte` int NOT NULL,
  `id_commentaire` int NOT NULL,
  `id_reaction` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compte` (`id_compte`),
  KEY `id_commentaire` (`id_commentaire`),
  KEY `id_reaction` (`id_reaction`),
  CONSTRAINT `reaction_commentaire_ibfk_1` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`),
  CONSTRAINT `reaction_commentaire_ibfk_2` FOREIGN KEY (`id_commentaire`) REFERENCES `commentaire` (`id`),
  CONSTRAINT `reaction_commentaire_ibfk_3` FOREIGN KEY (`id_reaction`) REFERENCES `reaction` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_commentaire`
--

LOCK TABLES `reaction_commentaire` WRITE;
/*!40000 ALTER TABLE `reaction_commentaire` DISABLE KEYS */;
INSERT INTO `reaction_commentaire` VALUES (1,1,4,1),(2,2,5,2),(3,2,4,2),(4,2,4,2),(5,2,5,2),(6,2,5,2),(7,1,6,3);
/*!40000 ALTER TABLE `reaction_commentaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_publication`
--

DROP TABLE IF EXISTS `reaction_publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reaction_publication` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_compte` int NOT NULL,
  `id_publication` int NOT NULL,
  `id_reaction` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compte` (`id_compte`),
  KEY `id_publication` (`id_publication`),
  KEY `id_reaction` (`id_reaction`),
  CONSTRAINT `reaction_publication_ibfk_1` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`),
  CONSTRAINT `reaction_publication_ibfk_2` FOREIGN KEY (`id_publication`) REFERENCES `publication` (`id`),
  CONSTRAINT `reaction_publication_ibfk_3` FOREIGN KEY (`id_reaction`) REFERENCES `reaction` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_publication`
--

LOCK TABLES `reaction_publication` WRITE;
/*!40000 ALTER TABLE `reaction_publication` DISABLE KEYS */;
INSERT INTO `reaction_publication` VALUES (1,1,1,2),(2,1,1,2),(3,2,3,3),(4,1,2,5),(5,1,3,2);
/*!40000 ALTER TABLE `reaction_publication` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-25  5:12:16
