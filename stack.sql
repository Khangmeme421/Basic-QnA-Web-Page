/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.5-10.4.32-MariaDB : Database - stack
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`stack` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;

USE `stack`;

/*Table structure for table `answers` */

DROP TABLE IF EXISTS `answers`;

CREATE TABLE `answers` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `content` text DEFAULT NULL,
  `iduser` int(20) NOT NULL,
  `idquestion` int(20) NOT NULL,
  `date_create` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answers_ibfk_1` (`iduser`),
  KEY `answers_ibfk_2` (`idquestion`),
  CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`idquestion`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `answers` */

insert  into `answers`(`id`,`content`,`iduser`,`idquestion`,`date_create`) values (7,'Well done man',3,26,'2024-07-22'),(34,'say thank to me',3,84,'2024-08-20'),(35,'I like that',1,85,'2024-08-21');

/*Table structure for table `feedback` */

DROP TABLE IF EXISTS `feedback`;

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text DEFAULT NULL,
  `iduser` int(20) DEFAULT NULL,
  `title` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedback_ibfk_1` (`iduser`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `feedback` */

insert  into `feedback`(`id`,`content`,`iduser`,`title`) values (1,'kememez',3,'test feedback'),(2,'We have a probem',3,'I think');

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `date_create` date DEFAULT NULL,
  `idsender` int(11) DEFAULT NULL,
  `idquestion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_ibfk_1` (`iduser`),
  KEY `idsender` (`idsender`),
  KEY `idquestion` (`idquestion`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`idsender`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`idquestion`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `notifications` */

insert  into `notifications`(`id`,`iduser`,`content`,`date_create`,`idsender`,`idquestion`) values (11,3,'commented on your ','2024-07-23',1,26),(30,3,'commented on your ','2024-07-26',1,26),(31,3,'commented on your ','2024-07-26',1,26),(34,3,'commented on your ','2024-07-27',1,26),(35,3,'commented on your ','2024-07-27',1,26),(36,3,'commented on your ','2024-07-27',1,26),(38,3,'commented on your ','2024-08-10',1,26),(39,3,'commented on your ','2024-08-10',1,26),(40,3,'commented on your ','2024-08-10',1,26),(45,1,'commented on your ','2024-08-20',3,84),(46,3,'commented on your ','2024-08-21',1,85);

/*Table structure for table `questions` */

DROP TABLE IF EXISTS `questions`;

CREATE TABLE `questions` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `idsubject` int(20) NOT NULL,
  `iduser` int(20) NOT NULL,
  `date_create` date DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_ibfk_1` (`idsubject`),
  KEY `questions_ibfk_2` (`iduser`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`idsubject`) REFERENCES `subject` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `questions` */

insert  into `questions`(`id`,`title`,`content`,`idsubject`,`iduser`,`date_create`,`image`) values (26,'i\'m anh long','I cant up load file yet, so waht do you say?',1,3,'2024-07-21',NULL),(84,'heelo','I stuck',17,1,'2024-08-20','../uploads/'),(85,'My first 3d model','This is based on Arno Breker\'s Art',5,3,'2024-08-21','../uploads/natalia-burtseva-as-4.jpg');

/*Table structure for table `subject` */

DROP TABLE IF EXISTS `subject`;

CREATE TABLE `subject` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `sub_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `subject` */

insert  into `subject`(`id`,`sub_name`) values (1,'General'),(3,'Comp1841'),(5,'Curl1921'),(17,'long1911');

/*Table structure for table `upvote` */

DROP TABLE IF EXISTS `upvote`;

CREATE TABLE `upvote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idquestion` int(11) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idquestion` (`idquestion`),
  KEY `iduser` (`iduser`),
  CONSTRAINT `upvote_ibfk_1` FOREIGN KEY (`idquestion`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upvote_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `upvote` */

insert  into `upvote`(`id`,`idquestion`,`iduser`) values (4,26,1),(8,26,3),(9,85,1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`role`,`name`,`email`) values (1,'anhkhang','04df4d434d481c5bb723be1b6df1ee65','student','khang','khang@fpt.com'),(2,'baodz','04df4d434d481c5bb723be1b6df1ee65','student','bao','baogman@yahoo.com'),(3,'longz','04df4d434d481c5bb723be1b6df1ee65','admin','long','anhlong@gmail.com'),(7,'anhlong','04df4d434d481c5bb723be1b6df1ee65','student','lonz','lonh@gmail.com'),(19,'Nikkoavdo','04df4d434d481c5bb723be1b6df1ee65','admin','kiz zart','Kicaz@gmail.com');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
