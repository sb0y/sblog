-- MySQL dump 10.14  Distrib 5.5.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: 9kg
-- ------------------------------------------------------
-- Server version	5.5.32-MariaDB-log

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
  `catType` varchar(10) DEFAULT 'news',
  PRIMARY KEY (`categoryID`,`catSlug`(3))
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_change`
--

DROP TABLE IF EXISTS `comment_change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_change` (
  `entryID` int(22) NOT NULL AUTO_INCREMENT,
  `userID` int(22) DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentID` int(22) DEFAULT NULL,
  `contentID` int(22) DEFAULT NULL,
  `voteType` enum('-','+') DEFAULT NULL,
  PRIMARY KEY (`entryID`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `commentID` int(10) NOT NULL AUTO_INCREMENT,
  `contentID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `reply_to` int(22) DEFAULT '0',
  `dt` datetime DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `author` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `guest` enum('Y','N') DEFAULT 'N',
  `ip` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `rate` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentID`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `contentID` int(22) NOT NULL AUTO_INCREMENT,
  `userID` int(3) NOT NULL,
  `tagID` varchar(255) NOT NULL,
  `author` varchar(40) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `poster` text NOT NULL,
  `short` text NOT NULL,
  `comments_count` int(3) NOT NULL DEFAULT '0',
  `sort` int(3) NOT NULL,
  `dt` datetime DEFAULT NULL,
  `showOnSite` enum('Y','N') DEFAULT 'N',
  `editedByID` int(11) DEFAULT NULL,
  `editedOn` datetime DEFAULT NULL,
  `editedByNick` varchar(255) DEFAULT '',
  `type` varchar(10) DEFAULT 'news',
  `key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contentID`,`slug`(8)),
  KEY `userID` (`userID`),
  KEY `slug` (`slug`),
  FULLTEXT KEY `searchindex` (`title`,`body`,`slug`,`author`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=535 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `content_drafts`
--

DROP TABLE IF EXISTS `content_drafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_drafts` (
  `draftID` int(11) NOT NULL AUTO_INCREMENT,
  `contentID` int(22) DEFAULT NULL,
  `userID` int(22) DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` text,
  `module` varchar(20) DEFAULT NULL,
  `draft_nick` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`draftID`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `export_log`
--

DROP TABLE IF EXISTS `export_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `export_log` (
  `entryID` int(11) NOT NULL AUTO_INCREMENT,
  `contentID` int(22) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(10) DEFAULT NULL,
  `module` varchar(20) DEFAULT NULL,
  `social_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`entryID`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `favID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `contentID` int(22) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `addDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`favID`),
  KEY `slug` (`slug`),
  KEY `contentID` (`contentID`),
  KEY `addDate` (`addDate`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friends` (
  `friendshipID` int(22) NOT NULL AUTO_INCREMENT,
  `u1` int(22) DEFAULT NULL,
  `u2` int(22) DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`friendshipID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `messageID` int(22) NOT NULL AUTO_INCREMENT,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nick` varchar(255) DEFAULT NULL,
  `senderID` int(22) DEFAULT NULL,
  `receiverID` int(22) DEFAULT NULL,
  `body` text,
  `subject` varchar(255) DEFAULT NULL,
  `isRead` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`messageID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `contentID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `dt` date NOT NULL,
  `showOnSite` enum('Y','N') NOT NULL DEFAULT 'N',
  `editedByID` int(11) DEFAULT NULL,
  `editedOn` datetime DEFAULT NULL,
  `editedByNick` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `comments_count` int(11) NOT NULL DEFAULT '0',
  `picture` varchar(255) DEFAULT NULL,
  `preview` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(25) DEFAULT 'news',
  `key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contentID`,`slug`(8)),
  KEY `userID` (`userID`),
  KEY `slug` (`slug`),
  FULLTEXT KEY `searchindex` (`title`,`description`,`slug`,`author`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `redirect`
--

DROP TABLE IF EXISTS `redirect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `redirect` (
  `entryID` int(22) NOT NULL AUTO_INCREMENT,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `URL` varchar(255) DEFAULT NULL,
  `code` text,
  PRIMARY KEY (`entryID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userID` int(22) NOT NULL AUTO_INCREMENT,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `socID` text,
  `nick` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_hash` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `social_email` varchar(100) DEFAULT NULL,
  `gplusURL` varchar(100) DEFAULT NULL,
  `facebookURL` varchar(100) DEFAULT NULL,
  `twitterURL` varchar(100) DEFAULT NULL,
  `vkURL` varchar(100) DEFAULT NULL,
  `skype` varchar(100) DEFAULT NULL,
  `about` text NOT NULL,
  `source` varchar(20) DEFAULT 'direct',
  `ip` varchar(10) DEFAULT '0',
  `remote_pic` varchar(255) DEFAULT NULL,
  `profileURL` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `avatar_small` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `showEmail` enum('Y','N') DEFAULT 'N',
  `group` varchar(30) DEFAULT 'none',
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `contentID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `dt` date NOT NULL,
  `showOnSite` enum('Y','N') NOT NULL DEFAULT 'N',
  `editedByID` int(11) DEFAULT NULL,
  `editedOn` datetime DEFAULT NULL,
  `editedByNick` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `comments_count` int(11) NOT NULL DEFAULT '0',
  `views_count` int(11) NOT NULL DEFAULT '0',
  `pictures` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`contentID`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-28 14:53:50
