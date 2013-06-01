-- MySQL dump 10.13  Distrib 5.1.67, for pc-linux-gnu (x86_64)
--
-- Host: localhost    Database: bagrintsev
-- ------------------------------------------------------
-- Server version	5.1.67-log

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `categoryID` int(3) NOT NULL AUTO_INCREMENT,
  `catName` varchar(255) DEFAULT NULL,
  `catSlug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`categoryID`,`catSlug`(3))
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `commentID` int(10) NOT NULL AUTO_INCREMENT,
  `contentID` int(3) NOT NULL,
  `userID` int(3) NOT NULL DEFAULT '0',
  `reply_to` int(3) NOT NULL DEFAULT '0',
  `dt` datetime DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `author` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `guest` enum('Y','N') DEFAULT 'N',
  `ip` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `contentID` int(3) NOT NULL AUTO_INCREMENT,
  `categoryID` varchar(255) NOT NULL,
  `userID` int(3) NOT NULL,
  `tagID` varchar(255) NOT NULL,
  `author` varchar(40) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `short` text NOT NULL,
  `comments_count` int(3) NOT NULL DEFAULT '0',
  `sort` int(3) NOT NULL,
  `dt` date DEFAULT NULL,
  `showOnSite` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`contentID`,`slug`(8)),
  FULLTEXT KEY `body` (`body`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `url_name` (`slug`),
  FULLTEXT KEY `body_2` (`body`,`title`,`author`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `content_category`
--

DROP TABLE IF EXISTS `content_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contentID` int(8) DEFAULT NULL,
  `catID` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=262 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drafts`
--

DROP TABLE IF EXISTS `drafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drafts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contentID` int(3) NOT NULL DEFAULT '0',
  `userID` int(3) NOT NULL,
  `tagID` varchar(255) NOT NULL,
  `author` varchar(40) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url_name` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `sort` int(3) NOT NULL,
  `dt` date DEFAULT NULL,
  `draft_add_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meta_tags`
--

DROP TABLE IF EXISTS `meta_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meta_tags` (
  `tagID` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`tagID`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_recovery`
--

DROP TABLE IF EXISTS `password_recovery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_recovery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `add_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolio`
--

DROP TABLE IF EXISTS `portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` date DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `showOnSite` enum('Y','N') DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `shortBody` text,
  `body` text,
  `type` varchar(50) DEFAULT 'text',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `socID` text,
  `nick` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_hash` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `social_email` varchar(100) DEFAULT NULL,
  `about` text NOT NULL,
  `source` varchar(20) DEFAULT 'direct',
  `ip` varchar(10) DEFAULT '0',
  `remote_pic` varchar(255) DEFAULT NULL,
  `profileURL` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `avatar_small` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-05-26  0:18:36
