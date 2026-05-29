/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - gor_kamandanoe
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gor_kamandanoe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `gor_kamandanoe`;

/*Table structure for table `booking` */

DROP TABLE IF EXISTS `booking`;

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(100) DEFAULT NULL,
  `lapangan` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `harga` int(20) DEFAULT NULL,
  `total_bayar` int(20) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_booking`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `booking` */

insert  into `booking`(`id_booking`,`nama_user`,`lapangan`,`tanggal`,`jam`,`status`,`harga`,`total_bayar`,`metode_pembayaran`) values 
(1,'Marshel','Lapangan A','2026-05-27','19.00-20.00','Acc',NULL,NULL,NULL),
(2,'Isnan','Lapangan B','2026-05-28','16.00-18.00','Lunas',NULL,NULL,'QRIS'),
(3,'Isnan','Lapangan A','2026-05-31','16.00-17.00','Lunas',NULL,NULL,'QRIS'),
(4,'Isnan','Lapangan A','2026-05-28','19.00','Lunas',50000,50000,'QRIS'),
(5,'Isnan','Lapangan A','2026-05-28','18.00','Lunas',50000,150000,'Cash'),
(6,'Isnan','Lapangan A','2026-05-28','18.00','Menunggu',50000,150000,'-'),
(7,'Isnan','Lapangan A','2026-05-28','18.00','Menunggu',50000,150000,'-'),
(8,'Isnan','Lapangan A','2026-05-12','18.00','Menunggu',50000,100000,'-');

/*Table structure for table `turnamen` */

DROP TABLE IF EXISTS `turnamen`;

CREATE TABLE `turnamen` (
  `id_turnamen` int(11) NOT NULL AUTO_INCREMENT,
  `nama_turnamen` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `hadiah` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_turnamen`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `turnamen` */

insert  into `turnamen`(`id_turnamen`,`nama_turnamen`,`tanggal`,`hadiah`) values 
(1,'Viktor cup 2026','2026-05-30','Rp 1.000'),
(2,NULL,NULL,NULL);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`nama`,`username`,`password`,`role`) values 
(1,'Viktor','Viktor','12345','admin'),
(2,'Isnan','Isnan','12345','user'),
(3,'Marshel','Marshel',NULL,'user'),
(4,'Messi','messi','12345','user');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
