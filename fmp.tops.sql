-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: fmp
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

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
-- Table structure for table `blog_top_category`
--

DROP TABLE IF EXISTS `blog_top_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_top_category` (
  `blog_id` int(11) NOT NULL,
  `top_category_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`top_category_id`),
  KEY `IDX_FDA53C0ADAE07E97` (`blog_id`),
  KEY `IDX_FDA53C0AC601200` (`top_category_id`),
  CONSTRAINT `FK_FDA53C0AC601200` FOREIGN KEY (`top_category_id`) REFERENCES `top_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FDA53C0ADAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_top_category`
--

LOCK TABLES `blog_top_category` WRITE;
/*!40000 ALTER TABLE `blog_top_category` DISABLE KEYS */;
INSERT INTO `blog_top_category` VALUES (1,1),(1,4),(2,1),(2,5),(3,4),(4,3),(5,3),(6,4),(7,4),(8,1),(8,4),(9,4),(10,2),(11,2),(11,4),(12,2),(13,2),(14,5),(15,5),(16,5),(17,5),(18,1),(18,3),(19,3),(20,5),(21,5),(22,5),(23,3),(24,3),(25,3),(26,3),(27,3),(28,3),(29,3),(30,3),(31,3),(32,3),(33,3),(34,3),(35,3),(36,3),(37,3),(38,5),(39,5),(40,2),(41,4),(42,3),(43,3),(44,5),(45,3),(46,3),(47,3),(48,4),(49,3),(50,3),(51,3),(52,3),(53,3),(53,4),(54,3),(55,5),(56,3),(57,2),(57,4),(58,3),(59,3),(60,3),(61,3),(62,3),(63,3),(64,3),(65,4),(66,4),(67,3),(68,2),(69,5),(70,4),(71,4),(72,4),(73,3),(74,3),(75,4),(76,4),(77,4),(78,4),(79,3),(80,3),(81,3),(82,3),(83,3),(84,3),(85,4),(86,3),(87,3),(88,3),(89,4),(90,3),(91,3),(92,3),(93,3),(94,3),(95,3),(96,3),(97,3),(98,3),(99,4),(100,3),(101,3),(102,1),(102,2),(103,5),(104,5),(105,4),(106,5),(107,4),(108,2),(109,1),(109,4),(110,1),(110,4),(111,4),(112,1),(114,1),(115,1),(115,5),(116,1),(116,3);
/*!40000 ALTER TABLE `blog_top_category` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-12 17:31:05
