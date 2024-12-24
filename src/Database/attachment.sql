测试环境
 Source Server Type    : MySQL
 Source Server Version : 50740 (5.7.40-log)
 Source Host           : 172.22.5.31:3306
 Source Schema         : tsyg

 Target Server Type    : MySQL
 Target Server Version : 50740 (5.7.40-log)
 File Encoding         : 65001

 Date: 05/06/2024 15:51:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------

-- Table structure for attachment
-- ----------------------------
DROP TABLE IF EXISTS `attachment`;
CREATE TABLE `attachment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '名称',
  `original` char(160) NOT NULL DEFAULT '' COMMENT '原始名称',
  `path_type` char(80) NOT NULL DEFAULT '' COMMENT '路径标记',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '大小（单位b）',
  `ext` char(30) NOT NULL DEFAULT '' COMMENT '类型（后缀名）',
  `type` char(30) NOT NULL DEFAULT '' COMMENT '类型（file文件, image图片, scrawl涂鸦, video视频, remote远程抓取文件）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT 'url路径',
  `hash` text COMMENT 'hash值',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `path_type` (`path_type`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6673 DEFAULT CHARSET=utf8mb4 COMMENT='附件';

SET FOREIGN_KEY_CHECKS = 1;
