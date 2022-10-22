CREATE DATABASE  IF NOT EXISTS `db19` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `db19`;
-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: db19
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

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
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'Red','#FF0000'),(2,'Green','#008000'),(3,'Blue','#0000FF'),(4,'Yellow','#FFFF00'),(7,'Grey','#808080');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='информация о группах в которых состоят пользователи';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'registered','Зарегистрированный пользователь'),(2,'writing','Пользователь, имеющий право писать сообщения');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_of_user`
--

DROP TABLE IF EXISTS `groups_of_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups_of_user` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `fk_groups_of_user_groups1_idx` (`group_id`),
  CONSTRAINT `fk_groups_of_user_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_groups_of_user_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_of_user`
--

LOCK TABLES `groups_of_user` WRITE;
/*!40000 ALTER TABLE `groups_of_user` DISABLE KEYS */;
INSERT INTO `groups_of_user` VALUES (1,1),(2,1),(2,2),(3,2),(4,1),(5,1),(5,2),(6,2);
/*!40000 ALTER TABLE `groups_of_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `sender` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `is_read` int(1) NOT NULL DEFAULT 0,
  `sections_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`sections_id`),
  KEY `fk_messages_sections1_idx` (`sections_id`),
  CONSTRAINT `fk_messages_sections1` FOREIGN KEY (`sections_id`) REFERENCES `sections` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (10,'test','self-message','2022-10-07 01:24:35','User-5','User-5',1,1),(11,'test message','from user-5','2022-10-11 20:35:51','User-5','User-3',1,2),(12,'test message from User-5 to User-2','test message from User-5','2022-10-18 01:02:26','User-5','User-2',0,3),(13,'your message has been received','answer to test message','2022-10-18 01:48:44','User-3','User-5',0,3),(14,'Hi! Subj.','welcome message','2022-10-18 01:52:51','User-3','User-6',1,9);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `colormark` int(11) NOT NULL DEFAULT 7,
  PRIMARY KEY (`id`,`colormark`),
  KEY `fk_sections_colors1_idx` (`colormark`),
  CONSTRAINT `fk_sections_colors1` FOREIGN KEY (`colormark`) REFERENCES `colors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='разделы сообщений';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'Основные','admin',7),(2,'По работе','admin',1),(3,'Личные','admin',4),(4,'Оповещения','admin',7),(5,'Магазины','admin',7),(6,'Подписки','admin',7),(7,'Подписка на лекции по PHP','admin',1),(8,'Подписка на другие лекции','admin',3),(9,'Форумы','admin',2),(10,'Спам','admin',7);
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections_treepath`
--

DROP TABLE IF EXISTS `sections_treepath`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections_treepath` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  CONSTRAINT `fk_sections_treepath_sections1` FOREIGN KEY (`child_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections_treepath`
--

LOCK TABLES `sections_treepath` WRITE;
/*!40000 ALTER TABLE `sections_treepath` DISABLE KEYS */;
INSERT INTO `sections_treepath` VALUES (1,1,1),(1,2,1),(1,3,1),(3,3,2),(2,2,2),(4,4,1),(4,5,1),(5,5,2),(4,6,1),(6,6,2),(4,7,1),(6,7,2),(7,7,3),(4,8,1),(6,8,2),(8,8,3),(4,9,1),(9,9,2),(10,10,1);
/*!40000 ALTER TABLE `sections_treepath` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `login` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` char(60) DEFAULT NULL,
  `notify_yes` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='информация о пользователях';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,0,'admin','user-1@example.com',NULL,'$2y$10$EDLbT3K2h1wKItLabCz7.OF5XeOSppZK5ZHJgr1EWYz3Zh/sU5XNe',0),(2,0,'User-2','user-2@example.com',NULL,'$2y$10$EDLbT3K2h1wKItLabCz7.OF5XeOSppZK5ZHJgr1EWYz3Zh/sU5XNe',0),(3,0,'User-3','user-3@example.com',NULL,'$2y$10$EDLbT3K2h1wKItLabCz7.OF5XeOSppZK5ZHJgr1EWYz3Zh/sU5XNe',0),(4,0,'User-4','user-4@example.com',NULL,'$2y$10$EDLbT3K2h1wKItLabCz7.OF5XeOSppZK5ZHJgr1EWYz3Zh/sU5XNe',0),(5,0,'User-5','user-5@example.com',NULL,'$2y$10$EDLbT3K2h1wKItLabCz7.OF5XeOSppZK5ZHJgr1EWYz3Zh/sU5XNe',0),(6,0,'User-6','user-6@example.com',NULL,'$2y$10$EDLbT3K2h1wKItLabCz7.OF5XeOSppZK5ZHJgr1EWYz3Zh/sU5XNe',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'db19'
--

--
-- Dumping routines for database 'db19'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-18  5:08:33
