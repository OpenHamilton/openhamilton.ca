-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: openhamilton
-- ------------------------------------------------------
-- Server version	5.1.54-1ubuntu4

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
-- Table structure for table `Comment`
--

DROP TABLE IF EXISTS `Comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Comment` (
  `commentID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text,
  `upvotes` int(11) DEFAULT NULL,
  `downvotes` int(11) DEFAULT NULL,
  `submittime` datetime DEFAULT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Comment`
--

LOCK TABLES `Comment` WRITE;
/*!40000 ALTER TABLE `Comment` DISABLE KEYS */;
INSERT INTO `Comment` VALUES (65,'This is a test.',0,0,'2011-11-17 13:55:01'),(66,'Here is some more stuff.<br />\r\nWith extra<br />\r\nlines.',0,0,'2011-11-17 13:55:23'),(67,'Here\'s a list;<br />\r\n<br />\r\n1. Hello<br />\r\n2. Goodbye<br />\r\n3. Hello, again.',0,0,'2011-11-17 13:56:03'),(68,'Sample comments:<br />\r\n<br />\r\n\"Hey guys, this is great! Keep up the good work.\"',0,0,'2011-11-17 13:56:43'),(69,'Random comments found on the net left by people on Facebook.',0,0,'2011-11-17 13:57:41'),(70,'Peanut Butter Chicken McNuggets',0,0,'2011-11-17 13:58:15'),(71,'someone needs to keep this page updated!',0,0,'2011-11-17 13:58:28'),(72,'Put a banana in your ear!',0,0,'2011-11-17 13:58:45'),(73,'What is a jiffy you say? \"A ‘jiffy’ is an actual unit of time for 1/100th of a second.\"',0,0,'2011-11-17 13:59:01'),(74,'sometimes when my parents leave me alone at home, i like to go outside and pretend im a tree',0,0,'2011-11-17 13:59:34'),(75,'I think anyone who dislikes Llamas, dislikes america. And they think you fat. So... yeah.',0,0,'2011-11-17 14:00:15');
/*!40000 ALTER TABLE `Comment` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-11-17 14:11:45
