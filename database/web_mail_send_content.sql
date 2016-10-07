/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : db_website

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-10-07 14:51:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for web_mail_send_content
-- ----------------------------
DROP TABLE IF EXISTS `web_mail_send_content`;
CREATE TABLE `web_mail_send_content` (
  `mail_send_id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_send_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `mail_send_content` text CHARACTER SET utf8,
  `mail_send_str_product_id` tinytext COMMENT 'chuỗi id sản phẩm:1,2,3,4',
  `mail_send_link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `mail_send_img` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `mail_send_status` tinyint(5) DEFAULT NULL,
  `mail_send_time_creater` int(11) DEFAULT '0',
  `mail_send_time_update` int(11) DEFAULT '0',
  PRIMARY KEY (`mail_send_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of web_mail_send_content
-- ----------------------------
