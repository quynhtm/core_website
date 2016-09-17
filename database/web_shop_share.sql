/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : db_website

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-09-17 10:17:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for web_shop_share
-- ----------------------------
DROP TABLE IF EXISTS `web_shop_share`;
CREATE TABLE `web_shop_share` (
  `shop_share_id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT '0' COMMENT 'banner id được click',
  `shop_name` varchar(255) DEFAULT NULL,
  `shop_share_ip` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ IP click',
  `shop_share_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`shop_share_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_shop_share
-- ----------------------------
INSERT INTO `web_shop_share` VALUES ('1', '13', null, '192.168.35.123', '1462520365');
INSERT INTO `web_shop_share` VALUES ('2', '13', null, '192.168.35.124', '1462520450');
INSERT INTO `web_shop_share` VALUES ('3', '13', null, '192.168.35.125', '1462521066');
INSERT INTO `web_shop_share` VALUES ('4', '13', null, '192.168.35.126', '1462521094');
INSERT INTO `web_shop_share` VALUES ('5', '13', null, '192.168.35.127', '1462521698');
INSERT INTO `web_shop_share` VALUES ('6', '13', null, '192.168.35.128', '1462521752');
INSERT INTO `web_shop_share` VALUES ('7', '13', null, '192.168.35.129', '1462522517');
INSERT INTO `web_shop_share` VALUES ('8', '14', null, '192.168.35.129', '1462522557');
INSERT INTO `web_shop_share` VALUES ('9', '14', null, '192.168.35.128', '1462522588');
INSERT INTO `web_shop_share` VALUES ('10', '15', 'Thời trang nữ', '2', null);
INSERT INTO `web_shop_share` VALUES ('11', '15', 'Thời trang nữ', '::1', null);
