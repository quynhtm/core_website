/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : db_website

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-10-13 11:46:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for web_customer_shop
-- ----------------------------
DROP TABLE IF EXISTS `web_customer_shop`;
CREATE TABLE `web_customer_shop` (
  `customer_shop_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_shop_full_name` varchar(50) NOT NULL DEFAULT '0',
  `customer_shop_email` varchar(255) NOT NULL,
  `customer_shop_phone` varchar(50) NOT NULL DEFAULT '0',
  `customer_shop_password` varchar(255) DEFAULT NULL,
  `customer_shop_status` tinyint(2) NOT NULL DEFAULT '1',
  `customer_shop_address` tinytext,
  `customer_shop_province_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Tỉnh/Thành',
  `customer_shop_last_action` int(11) DEFAULT '0' COMMENT 'Thời gian gần nhất mua hàng',
  `customer_shop_created` int(11) DEFAULT '0',
  `customer_shop_updated` int(11) DEFAULT '0',
  PRIMARY KEY (`customer_shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_customer_shop
-- ----------------------------
