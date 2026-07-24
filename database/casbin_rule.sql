/*
 Navicat Premium Dump SQL

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 50744 (5.7.44-log)
 Source Host           : 127.0.0.1:3306
 Source Schema         : fssoa

 Target Server Type    : MySQL
 Target Server Version : 50744 (5.7.44-log)
 File Encoding         : 65001

 Date: 22/07/2026 15:22:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for casbin_rule
-- ----------------------------
DROP TABLE IF EXISTS `casbin_rule`;
CREATE TABLE `casbin_rule`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ptype` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '策略类型: p(权限) / g(角色继承) / g2(部门继承)',
  `v0` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第1个参数: 用户ID/角色ID/部门ID',
  `v1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第2个参数: 资源/角色/部门',
  `v2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第3个参数: 操作/动作',
  `v3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第4个参数: 扩展字段',
  `v4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第5个参数: 扩展字段',
  `v5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第6个参数: 扩展字段',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_ptype`(`ptype`) USING BTREE,
  INDEX `idx_v0`(`v0`) USING BTREE,
  INDEX `idx_v1`(`v1`) USING BTREE,
  INDEX `idx_v0_v1`(`v0`, `v1`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2435 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Casbin权限规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of casbin_rule
-- ----------------------------
INSERT INTO `casbin_rule` VALUES (886, 'p', '1', '/api/api/core/system/user', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1196, 'p', 'ceo', '/api/core/console', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1197, 'p', 'ceo', '/api/core/console/list', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1198, 'p', 'ceo', '/api/core/console/*', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1202, 'g', '105', 'ceo', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (1204, 'g', '101', 'ceo', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (1278, 'p', 'JTCEO', '/api/core/console', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1279, 'p', 'JTCEO', '/api/core/console/list', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1280, 'p', 'JTCEO', '/api/core/console/*', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1281, 'p', 'JTCEO', '/api/tool/crontab', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1282, 'p', 'JTCEO', '/api/tool/crontab', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1283, 'p', 'JTCEO', '/api/tool/crontab', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1284, 'p', 'JTCEO', '/api/tool/code', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1285, 'p', 'JTCEO', '/api/tool/code', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1544, 'p', '2', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1545, 'p', '2', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1546, 'p', '2', '/api/core/user', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1547, 'p', '2', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1548, 'p', '2', '/api/core/user', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (1549, 'p', '2', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1550, 'p', '2', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1551, 'p', '2', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1552, 'p', '2', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1553, 'p', '2', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1554, 'p', '2', '/api/core/dept', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1555, 'p', '2', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1556, 'p', '2', '/api/core/dept', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (1557, 'p', '2', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1558, 'p', '2', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1559, 'p', '2', '/api/core/role', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1560, 'p', '2', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1561, 'p', '2', '/api/core/role', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (1562, 'p', '2', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1563, 'p', '2', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1564, 'p', '2', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1565, 'p', '2', '/api/core/post', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1566, 'p', '2', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1567, 'p', '2', '/api/core/post', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (1568, 'p', '2', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1569, 'p', '2', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1570, 'p', '2', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1571, 'p', '2', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1572, 'p', '2', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1573, 'p', '2', '/api/core/menu', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1574, 'p', '2', '/api/core/menu', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (1575, 'p', '2', '/api/core/config', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1576, 'p', '2', '/api/core/config', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1577, 'p', '2', '/api/core/config', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1578, 'p', '2', '/api/core/dict', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1579, 'p', '2', '/api/core/dict', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1580, 'p', '2', '/api/core/attachment', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1581, 'p', '2', '/api/core/attachment', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1582, 'p', '2', '/api/core/database', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1583, 'p', '2', '/api/core/database', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1584, 'p', '2', '/api/core/recycle', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1585, 'p', '2', '/api/core/recycle', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1586, 'p', '2', '/api/core/logs', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1587, 'p', '2', '/api/core/logs', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1588, 'p', '2', '/api/core/logs', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1589, 'p', '2', '/api/core/logs', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1590, 'p', '2', '/api/core/email', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1591, 'p', '2', '/api/core/email', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (1592, 'p', '2', '/api/core/server', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1593, 'p', '2', '/api/core/server', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1594, 'p', '2', '/api/core/server', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1595, 'p', '2', '/api/core/console', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1596, 'p', '2', '/api/core/console/list', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1597, 'p', '2', '/api/core/console/*', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (1598, 'p', '2', '/api/tool/crontab', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1599, 'p', '2', '/api/tool/crontab', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1600, 'p', '2', '/api/tool/crontab', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1601, 'p', '2', '/api/tool/code', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1602, 'p', '2', '/api/tool/code', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (1603, 'p', '2', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1609, 'p', '2', '/api/core/server', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (1659, 'g', '2', 'ceo', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (1791, 'g', '119', 'ceo', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (1792, 'g', '120', 'bg_president', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (1793, 'g', '121', 'gm', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (1839, 'g', '123', 'ceo', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (1840, 'g', '122', 'bg_president', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (2111, 'g', '10', 'staff', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (2132, 'p', '100', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2133, 'p', '100', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2134, 'p', '100', '/api/core/user', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2135, 'p', '100', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2136, 'p', '100', '/api/core/user', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2137, 'p', '100', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2138, 'p', '100', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2139, 'p', '100', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2140, 'p', '100', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2141, 'p', '100', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2142, 'p', '100', '/api/core/dept', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2143, 'p', '100', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2144, 'p', '100', '/api/core/dept', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2145, 'p', '100', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2146, 'p', '100', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2147, 'p', '100', '/api/core/role', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2148, 'p', '100', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2149, 'p', '100', '/api/core/role', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2150, 'p', '100', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2151, 'p', '100', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2152, 'p', '100', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2153, 'p', '100', '/api/core/post', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2154, 'p', '100', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2155, 'p', '100', '/api/core/post', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2156, 'p', '100', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2157, 'p', '100', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2158, 'p', '100', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2159, 'p', '100', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2160, 'p', '100', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2161, 'p', '100', '/api/core/menu', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2162, 'p', '100', '/api/core/menu', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2163, 'p', '100', '/api/core/config', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2164, 'p', '100', '/api/core/config', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2165, 'p', '100', '/api/core/config', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2166, 'p', '100', '/api/core/console', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (2167, 'p', '100', '/api/core/console/list', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (2168, 'p', '100', '/api/core/console/*', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (2169, 'p', '100', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2170, 'p', '100', '/api/core/tenant', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2171, 'p', '100', '/api/core/tenant', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2172, 'p', '100', '/api/core/tenant', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2173, 'p', '100', '/api/core/tenant', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2174, 'p', '100', '/api/core/tenant', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2175, 'p', '100', '/template', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2252, 'p', '10', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2253, 'p', '10', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2254, 'p', '10', '/api/core/user', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2255, 'p', '10', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2256, 'p', '10', '/api/core/user', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2257, 'p', '10', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2258, 'p', '10', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2259, 'p', '10', '/api/core/user', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2260, 'p', '10', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2261, 'p', '10', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2262, 'p', '10', '/api/core/dept', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2263, 'p', '10', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2264, 'p', '10', '/api/core/dept', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2265, 'p', '10', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2266, 'p', '10', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2267, 'p', '10', '/api/core/role', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2268, 'p', '10', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2269, 'p', '10', '/api/core/role', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2270, 'p', '10', '/api/core/role', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2271, 'p', '10', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2272, 'p', '10', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2273, 'p', '10', '/api/core/post', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2274, 'p', '10', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2275, 'p', '10', '/api/core/post', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2276, 'p', '10', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2277, 'p', '10', '/api/core/post', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2278, 'p', '10', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2279, 'p', '10', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2280, 'p', '10', '/api/core/menu', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2281, 'p', '10', '/api/core/menu', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2282, 'p', '10', '/api/core/menu', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2283, 'p', '10', '/api/core/config', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2284, 'p', '10', '/api/core/config', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2285, 'p', '10', '/api/core/config', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2286, 'p', '10', '/api/core/console', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (2287, 'p', '10', '/api/core/console/list', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (2288, 'p', '10', '/api/core/console/*', 'GET', '', '', '');
INSERT INTO `casbin_rule` VALUES (2289, 'p', '10', '/api/core/dept', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2290, 'p', '10', '/api/core/tenant', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2291, 'p', '10', '/api/core/tenant', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2292, 'p', '10', '/api/core/tenant', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2293, 'p', '10', '/api/core/tenant', 'PUT', '', '', '');
INSERT INTO `casbin_rule` VALUES (2294, 'p', '10', '/api/core/tenant', 'DELETE', '', '', '');
INSERT INTO `casbin_rule` VALUES (2295, 'p', '10', '/template', '*', '', '', '');
INSERT INTO `casbin_rule` VALUES (2398, 'g', '104', 'ceo', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (2423, 'g', '100', 'JTCEO', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (2424, 'g', '100', 'ceo', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (2433, 'g', '1', 'super_admin', '', '', '', '');
INSERT INTO `casbin_rule` VALUES (2434, 'g', '1', 'ceo', '', '', '', '');

-- ----------------------------
-- Table structure for system_attachment
-- ----------------------------
DROP TABLE IF EXISTS `system_attachment`;
CREATE TABLE `system_attachment`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `category_id` int(11) NULL DEFAULT 0 COMMENT '文件分类',
  `storage_mode` smallint(6) NULL DEFAULT 1 COMMENT '存储模式 (1 本地 2 阿里云 3 七牛云 4 腾讯云)',
  `origin_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原文件名',
  `object_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '新文件名',
  `hash` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件hash',
  `mime_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '资源类型',
  `storage_path` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '存储目录',
  `suffix` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件后缀',
  `size_byte` bigint(20) NULL DEFAULT NULL COMMENT '字节数',
  `size_info` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件大小',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'url地址',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `hash`(`hash`) USING BTREE,
  INDEX `idx_url`(`url`) USING BTREE,
  INDEX `idx_create_time`(`create_time`) USING BTREE,
  INDEX `idx_category_id`(`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '附件信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_attachment
-- ----------------------------
INSERT INTO `system_attachment` VALUES (14, 1, 1, '2205be50f7884aa2ad8c9fb214460729_weixin_36343299.jpg', '69c7a15ee50ff0.57272058.jpg', '4a5a5cae301074163209b84a5442659a', 'image/jpeg', 'uploads/2026/03/28', 'jpg', 8776, '8.57 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a15ee50ff0.57272058.jpg', NULL, 1, 1, '2026-03-28 17:37:34', '2026-03-28 17:37:34', NULL);
INSERT INTO `system_attachment` VALUES (15, 1, 1, 'cat.webp', '69c7a39ee92e22.73142230.webp', '5cb0bcbecf611c2dfdf9dd4071265ad5', 'image/webp', 'uploads/2026/03/28', 'webp', 6914, '6.75 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a39ee92e22.73142230.webp', NULL, 1, 1, '2026-03-28 17:47:10', '2026-03-28 17:47:10', NULL);
INSERT INTO `system_attachment` VALUES (16, 1, 1, 'mjc.88aab0a2.png', '69c7a3a1cae578.20110121.png', '9955a9a409100c212f218f6570ae5c5d', 'image/png', 'uploads/2026/03/28', 'png', 6108, '5.96 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3a1cae578.20110121.png', NULL, 1, 1, '2026-03-28 17:47:13', '2026-03-28 17:47:13', NULL);
INSERT INTO `system_attachment` VALUES (17, 1, 1, 'pic.webp', '69c7a3a453f785.28306567.webp', '40dc7c6d3b8e14cfa28df6a96124c6f8', 'image/webp', 'uploads/2026/03/28', 'webp', 2364, '2.31 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3a453f785.28306567.webp', NULL, 1, 1, '2026-03-28 17:47:16', '2026-03-28 17:47:16', NULL);
INSERT INTO `system_attachment` VALUES (18, 1, 1, 'ScreenShot_2026-03-28_171541_015.png', '69c7a3a67d5e50.12092222.png', 'b65216028a95a8503f997a62bf3ee969', 'image/png', 'uploads/2026/03/28', 'png', 16427, '16.04 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3a67d5e50.12092222.png', NULL, 1, 1, '2026-03-28 17:47:18', '2026-03-28 17:47:18', NULL);
INSERT INTO `system_attachment` VALUES (19, 1, 1, 'ScreenShot_2026-03-28_171553_082.png', '69c7a3a83f9f14.20042528.png', '48d654f3fb0c398ad12845b26d7c8145', 'image/png', 'uploads/2026/03/28', 'png', 21050, '20.56 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3a83f9f14.20042528.png', NULL, 1, 1, '2026-03-28 17:47:20', '2026-03-28 17:47:20', NULL);
INSERT INTO `system_attachment` VALUES (20, 1, 1, 'ScreenShot_2026-03-28_171606_033.png', '69c7a3a9e6c335.87540530.png', 'df0009a61f16774fa228c2779c4c5d54', 'image/png', 'uploads/2026/03/28', 'png', 16632, '16.24 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3a9e6c335.87540530.png', NULL, 1, 1, '2026-03-28 17:47:21', '2026-03-28 17:47:21', NULL);
INSERT INTO `system_attachment` VALUES (21, 1, 1, 'ScreenShot_2026-03-28_171614-602.png', '69c7a3acd6b581.21163358.png', '30dc6ef573fcafd0420c0ddf409756b4', 'image/png', 'uploads/2026/03/28', 'png', 6887, '6.73 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3acd6b581.21163358.png', NULL, 1, 1, '2026-03-28 17:47:24', '2026-03-28 20:42:49', NULL);
INSERT INTO `system_attachment` VALUES (22, 1, 1, 'secpw8Be3MLmuTFCaQSVDENOn7K6Hz7GZBRJxZ69.webp', '69c7a3af23d945.66099869.webp', 'f80bcc04c839d890928006ec9d598e88', 'image/webp', 'uploads/2026/03/28', 'webp', 13724, '13.4 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3af23d945.66099869.webp', NULL, 1, 1, '2026-03-28 17:47:27', '2026-03-28 17:47:27', NULL);
INSERT INTO `system_attachment` VALUES (23, 1, 1, 'vip.webp', '69c7a3b2058589.44968839.webp', 'df757eb87d81ac74faf2a04b1260fb5d', 'image/webp', 'uploads/2026/03/28', 'webp', 10204, '9.96 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3b2058589.44968839.webp', NULL, 1, 1, '2026-03-28 17:47:30', '2026-03-28 17:47:30', NULL);
INSERT INTO `system_attachment` VALUES (24, 2, 1, 'wel_tips.5624828.png', '69c7a3b48aab47.53936592.png', '5624828dcc8975b34dd8af3c5b6229d5', 'image/png', 'uploads/2026/03/28', 'png', 16937, '16.54 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3b48aab47.53936592.png', NULL, 1, 1, '2026-03-28 17:47:32', '2026-04-21 01:05:18', NULL);
INSERT INTO `system_attachment` VALUES (25, 1, 1, 'cilixian.org.txt', '69c7e30c6d3b81.92728200.txt', '285db3e41cd89dcd20c383ae42b10244', 'text/plain', 'uploads/2026/03/28', 'txt', 3470, '3.39 KB', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7e30c6d3b81.92728200.txt', NULL, 1, 1, '2026-03-28 22:17:48', '2026-03-28 23:48:12', '2026-03-28 23:48:12');
INSERT INTO `system_attachment` VALUES (26, 1, 1, 'mac_mtm3u8.txt', '69ff2c7a325e50.13047637.txt', '0bd6e5ae19ba6bec8b418098cc319c1e', 'text/plain', 'uploads/2026/05/09', 'txt', 968, '968 B', 'http://127.0.0.1:8000/uploads/2026/05/09/69ff2c7a325e50.13047637.txt', NULL, 1, 1, '2026-05-09 20:45:46', '2026-05-16 17:09:28', '2026-05-16 17:09:28');
INSERT INTO `system_attachment` VALUES (27, 1, 1, '新建文本文档 (2).txt', '69ff2d5ac201f3.70935436.txt', '699fcb3697b574eacb0ece1a150ba873', 'text/plain', 'uploads/2026/05/09', 'txt', 4719, '4.61 KB', 'http://127.0.0.1:8000/uploads/2026/05/09/69ff2d5ac201f3.70935436.txt', NULL, 1, 1, '2026-05-09 20:49:30', '2026-05-16 17:09:33', '2026-05-16 17:09:33');
INSERT INTO `system_attachment` VALUES (28, 1, 1, 'MP_verify_MCykXGaAFZK3w7LS.txt', '69ff2d5ad6f9d1.36600390.txt', '82844c241bb0ee0ed7a8ab04f9930231', 'text/plain', 'uploads/2026/05/09', 'txt', 16, '16 B', 'http://127.0.0.1:8000/uploads/2026/05/09/69ff2d5ad6f9d1.36600390.txt', NULL, 1, 1, '2026-05-09 20:49:30', '2026-05-16 17:09:31', '2026-05-16 17:09:31');

-- ----------------------------
-- Table structure for system_category
-- ----------------------------
DROP TABLE IF EXISTS `system_category`;
CREATE TABLE `system_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父id',
  `level` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '组集关系',
  `category_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`parent_id`) USING BTREE,
  INDEX `sort`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '附件分类表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_category
-- ----------------------------
INSERT INTO `system_category` VALUES (1, 0, '0,', '全部分类', 100, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_category` VALUES (2, 1, '0,1,', '图片分类', 100, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-04-21 01:04:51', NULL);
INSERT INTO `system_category` VALUES (3, 1, '0,1,', '文件分类', 100, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-03-23 21:54:20', NULL);
INSERT INTO `system_category` VALUES (4, 1, '0,1,', '系统图片', 100, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_category` VALUES (5, 1, '0,1,', '其他分类', 100, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-03-28 23:51:54', NULL);
INSERT INTO `system_category` VALUES (6, 1, '0,1,', 'ces', 100, 1, '', 1, 1, '2026-03-23 21:52:33', '2026-03-23 21:52:35', '2026-03-23 21:52:35');
INSERT INTO `system_category` VALUES (7, 2, '0,1,2,', '测试', 100, 1, '', 1, 1, '2026-03-23 21:52:46', '2026-03-23 22:02:52', '2026-03-23 22:02:52');

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `group_id` int(11) NULL DEFAULT NULL COMMENT '组id',
  `key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置键名',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置值',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置名称',
  `input_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '数据输入类型',
  `config_select_data` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置选项数据',
  `sort` smallint(5) UNSIGNED NULL DEFAULT 0 COMMENT '排序',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建人',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新人',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`, `key`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 57 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '参数配置信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_config
-- ----------------------------
INSERT INTO `system_config` VALUES (1, 1, 'site_copyright', 'Copyright © 2026 eqrayphp Team', '版权信息', 'textarea', NULL, 96, '', 1, 1, '2026-01-01 00:00:00', '2026-03-22 20:34:45', NULL);
INSERT INTO `system_config` VALUES (2, 1, 'site_desc', '基于Vue3 + eqrayphp 的极速开发框架', '网站描述', 'textarea', NULL, 97, NULL, 1, 1, '2026-01-01 00:00:00', '2026-04-23 21:36:30', NULL);
INSERT INTO `system_config` VALUES (3, 1, 'site_keywords', 'eqrayphp, Workerman，symfony，Thinkphp，后台管理系统', '网站关键字', 'input', NULL, 98, NULL, 1, 1, '2026-01-01 00:00:00', '2026-04-23 21:36:23', NULL);
INSERT INTO `system_config` VALUES (4, 1, 'site_name', 'eqrayadmin后台管理系统', '网站名称', 'input', NULL, 99, NULL, 1, 1, '2026-01-01 00:00:00', '2026-04-23 21:36:24', NULL);
INSERT INTO `system_config` VALUES (5, 1, 'site_record_number', '9527', '网站备案号', 'input', NULL, 95, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (6, 2, 'upload_allow_file', 'txt,doc,docx,xls,xlsx,ppt,pptx,rar,zip,7z,gz,pdf,wps,md,jpg,png,jpeg,mp4,pem,crt', '文件类型', 'input', NULL, 0, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (7, 2, 'upload_allow_image', 'jpg,jpeg,png,gif,svg,bmp', '图片类型', 'input', NULL, 0, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (8, 2, 'upload_mode', '1', '上传模式', 'select', '[{\"label\":\"本地上传\",\"value\":\"1\"},{\"label\":\"阿里云OSS\",\"value\":\"2\"},{\"label\":\"七牛云\",\"value\":\"3\"},{\"label\":\"腾讯云COS\",\"value\":\"4\"},{\"label\":\"亚马逊S3\",\"value\":\"5\"}]', 99, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (10, 2, 'upload_size', '52428800', '上传大小', 'input', NULL, 88, '单位Byte,1MB=1024*1024Byte', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (11, 2, 'local_root', 'public/storage/', '本地存储路径', 'input', NULL, 0, '本地存储文件路径', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (12, 2, 'local_domain', 'http://127.0.0.1:8000', '本地存储域名', 'input', NULL, 0, 'http://127.0.0.1:8787', 1, 1, '2026-01-01 00:00:00', '2026-03-22 20:07:02', NULL);
INSERT INTO `system_config` VALUES (13, 2, 'local_uri', '/storage/', '本地访问路径', 'input', NULL, 0, '访问是通过domain + uri', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (14, 2, 'qiniu_accessKey', '', '七牛key', 'input', NULL, 0, '七牛云存储secretId', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (15, 2, 'qiniu_secretKey', '', '七牛secret', 'input', NULL, 0, '七牛云存储secretKey', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (16, 2, 'qiniu_bucket', '', '七牛bucket', 'input', NULL, 0, '七牛云存储bucket', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (17, 2, 'qiniu_dirname', '', '七牛dirname', 'input', NULL, 0, '七牛云存储dirname', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (18, 2, 'qiniu_domain', '', '七牛domain', 'input', NULL, 0, '七牛云存储domain', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (19, 2, 'cos_secretId', '', '腾讯Id', 'input', NULL, 0, '腾讯云存储secretId', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (20, 2, 'cos_secretKey', '', '腾讯key', 'input', NULL, 0, '腾讯云secretKey', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (21, 2, 'cos_bucket', '', '腾讯bucket', 'input', NULL, 0, '腾讯云存储bucket', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (22, 2, 'cos_dirname', '', '腾讯dirname', 'input', NULL, 0, '腾讯云存储dirname', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (23, 2, 'cos_domain', '', '腾讯domain', 'input', NULL, 0, '腾讯云存储domain', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (24, 2, 'cos_region', '', '腾讯region', 'input', NULL, 0, '腾讯云存储region', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (25, 2, 'oss_accessKeyId', '', '阿里Id', 'input', NULL, 0, '阿里云存储accessKeyId', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (26, 2, 'oss_accessKeySecret', '', '阿里Secret', 'input', NULL, 0, '阿里云存储accessKeySecret', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (27, 2, 'oss_bucket', '', '阿里bucket', 'input', NULL, 0, '阿里云存储bucket', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (28, 2, 'oss_dirname', '', '阿里dirname', 'input', NULL, 0, '阿里云存储dirname', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (29, 2, 'oss_domain', '', '阿里domain', 'input', NULL, 0, '阿里云存储domain', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (30, 2, 'oss_endpoint', '', '阿里endpoint', 'input', NULL, 0, '阿里云存储endpoint', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (31, 3, 'Host', 'smtp.qq.com', 'SMTP服务器', 'input', '', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (32, 3, 'Port', '465', 'SMTP端口', 'input', '', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (33, 3, 'Username', '', 'SMTP用户名', 'input', '', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (34, 3, 'Password', '', 'SMTP密码', 'input', '', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (35, 3, 'SMTPSecure', 'ssl', 'SMTP验证方式', 'radio', '[\r\n    {\"label\":\"ssl\",\"value\":\"ssl\"},\r\n    {\"label\":\"tsl\",\"value\":\"tsl\"}\r\n]', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (36, 3, 'From', '', '默认发件人', 'input', '', 100, '默认发件的邮箱地址', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (37, 3, 'FromName', '账户注册', '默认发件名称', 'input', '', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (38, 3, 'CharSet', 'UTF-8', '编码', 'input', '', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (39, 3, 'SMTPDebug', '1', '调试模式', 'radio', '[\r\n    {\"label\":\"关闭\",\"value\":\"0\"},\r\n    {\"label\":\"client\",\"value\":\"1\"},\r\n    {\"label\":\"server\",\"value\":\"2\"}\r\n]', 100, '', 1, 1, '2026-01-01 00:00:00', '2026-03-22 20:06:26', NULL);
INSERT INTO `system_config` VALUES (40, 2, 's3_key', '', 'key', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (41, 2, 's3_secret', '', 'secret', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (42, 2, 's3_bucket', '', 'bucket', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (43, 2, 's3_dirname', '', 'dirname', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (44, 2, 's3_domain', '', 'domain', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (45, 2, 's3_region', '', 'region', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (46, 2, 's3_version', '', 'version', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (47, 2, 's3_use_path_style_endpoint', '', 'path_style_endpoint', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (48, 2, 's3_endpoint', '', 'endpoint', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (49, 2, 's3_acl', '', 'acl', 'input', '', 0, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config` VALUES (50, 1, 'ggg', '', 'ggg', 'uploadImage', NULL, 100, '', 1, 1, '2026-03-22 20:11:57', '2026-03-22 20:36:34', '2026-03-22 20:36:34');
INSERT INTO `system_config` VALUES (52, 1, 'Logo', 'http://127.0.0.1:8000/uploads/2026/03/28/69c7a3acd6b581.21163358.png', 'Logo', 'uploadImage', NULL, 100, '', 1, 1, '2026-03-25 21:30:52', '2026-03-28 23:36:48', '2026-03-28 23:36:48');
INSERT INTO `system_config` VALUES (54, 1, 'file', '', 'file', 'uploadFile', '[]', 100, '', 1, 1, '2026-03-28 22:17:22', '2026-03-28 22:17:32', '2026-03-28 22:17:32');
INSERT INTO `system_config` VALUES (55, 1, 'file', '', 'file', 'uploadFile', '[]', 100, '', 1, 1, '2026-03-28 22:17:23', '2026-03-28 22:17:36', '2026-03-28 22:17:36');
INSERT INTO `system_config` VALUES (56, 1, 'file', '', 'file', 'uploadFile', '[]', 100, '', 1, 1, '2026-03-28 22:17:28', '2026-03-28 23:36:48', '2026-03-28 23:36:48');

-- ----------------------------
-- Table structure for system_config_group
-- ----------------------------
DROP TABLE IF EXISTS `system_config_group`;
CREATE TABLE `system_config_group`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典名称',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典标示',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建人',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新人',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '参数配置分组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_config_group
-- ----------------------------
INSERT INTO `system_config_group` VALUES (1, '站点配置', 'site_config', '111', 1, 1, '2026-01-01 00:00:00', '2026-03-31 00:31:21', NULL);
INSERT INTO `system_config_group` VALUES (2, '上传配置', 'upload_config', NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config_group` VALUES (3, '邮件服务', 'email_config', NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_config_group` VALUES (4, '333', '333', '', 1, 1, '2026-03-22 20:36:51', '2026-03-22 20:43:05', '2026-03-22 20:43:05');
INSERT INTO `system_config_group` VALUES (7, '325235', '235235', '', 1, 1, '2026-03-28 20:36:57', '2026-03-28 20:38:44', '2026-03-28 20:38:44');

-- ----------------------------
-- Table structure for system_dept
-- ----------------------------
DROP TABLE IF EXISTS `system_dept`;
CREATE TABLE `system_dept`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '父级ID，0为根节点',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '部门名称',
  `code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '部门编码',
  `leader_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '部门负责人ID',
  `level` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '祖级列表，格式: 0,1,5, (便于查询子孙节点)',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序，数字越小越靠前',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态: 1启用, 0禁用',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_parent_id`(`parent_id`) USING BTREE,
  INDEX `idx_path`(`level`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 130 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '部门表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_dept
-- ----------------------------
INSERT INTO `system_dept` VALUES (1, 0, '腾讯集团', 'GROUP', 100, '0,', 0, 1, '00', 1, 1, '2026-01-01 00:00:00', '2026-03-23 23:27:09', NULL);

-- ----------------------------
-- Table structure for system_dict_data
-- ----------------------------
DROP TABLE IF EXISTS `system_dict_data`;
CREATE TABLE `system_dict_data`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '字典类型ID',
  `label` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典标签',
  `value` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典值',
  `color` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典颜色',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典标示',
  `sort` smallint(5) UNSIGNED NULL DEFAULT 0 COMMENT '排序',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '状态 (1正常 2停用)',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type_id`(`type_id`) USING BTREE,
  INDEX `idx_code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字典数据表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_dict_data
-- ----------------------------
INSERT INTO `system_dict_data` VALUES (2, 2, '本地存储', '1', '#60c041', 'upload_mode', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-23 23:00:39', NULL);
INSERT INTO `system_dict_data` VALUES (3, 2, '阿里云OSS', '2', '#f9901f', 'upload_mode', 98, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (4, 2, '七牛云', '3', '#00ced1', 'upload_mode', 97, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (5, 2, '腾讯云COS', '4', '#1d84ff', 'upload_mode', 96, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (6, 2, '亚马逊S3', '5', '#b48df3', 'upload_mode', 95, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-23 21:19:51', NULL);
INSERT INTO `system_dict_data` VALUES (7, 3, '正常', '1', '#60c041', 'data_status', 0, 1, '1为正常', 1, 1, '2026-01-01 00:00:00', '2026-03-24 23:16:13', NULL);
INSERT INTO `system_dict_data` VALUES (8, 3, '停用', '0', '#ff4d4f', 'data_status', 0, 1, '0为停用1', 1, 1, '2026-01-01 00:00:00', '2026-03-28 17:53:02', NULL);
INSERT INTO `system_dict_data` VALUES (9, 4, '统计页面', 'statistics', '#00ced1', 'dashboard', 100, 1, '管理员用', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (10, 4, '工作台', 'work', '#ff8c00', 'dashboard', 50, 1, '员工使用', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (11, 5, '男', '1', '#5d87ff', 'gender', 0, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (12, 5, '女', '2', '#ff4500', 'gender', 0, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (13, 5, '未知', '3', '#b48df3', 'gender', 0, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (16, 12, '图片', 'image', '#60c041', 'attachment_type', 10, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (17, 12, '文档', 'text', '#1d84ff', 'attachment_type', 9, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (18, 12, '音频', 'audio', '#00ced1', 'attachment_type', 8, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (19, 12, '视频', 'video', '#ff4500', 'attachment_type', 7, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (20, 12, '应用程序', 'application', '#ff8c00', 'attachment_type', 6, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (21, 13, '目录', '1', '#909399', 'menu_type', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (22, 13, '菜单', '2', '#1e90ff', 'menu_type', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (23, 13, '按钮', '3', '#ff4500', 'menu_type', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (24, 13, '外链', '4', '#00ced1', 'menu_type', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (25, 14, '是', '1', '#60c041', 'yes_or_no', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (26, 14, '否', '0', '#ff4500', 'yes_or_no', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-23 21:16:53', NULL);
INSERT INTO `system_dict_data` VALUES (47, 20, 'URL任务GET', '1', '#5d87ff', 'crontab_task_type', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (48, 20, 'URL任务POST', '2', '#00ced1', 'crontab_task_type', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-28 23:11:23', NULL);
INSERT INTO `system_dict_data` VALUES (49, 20, '类任务', '3', '#ff8c00', 'crontab_task_type', 100, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_data` VALUES (50, 5, 'renn', '4', '#5d87ff', NULL, 100, 1, '', 1, 1, '2026-03-23 21:20:40', '2026-03-23 21:20:44', '2026-03-23 21:20:44');
INSERT INTO `system_dict_data` VALUES (51, 5, '11', '111', '#5d87ff', NULL, 100, 0, '', 1, 1, '2026-03-23 21:20:49', '2026-03-23 21:24:19', '2026-03-23 21:24:19');
INSERT INTO `system_dict_data` VALUES (52, 5, '11', '123', '#5d87ff', NULL, 100, 1, '', 1, 1, '2026-03-23 21:24:27', '2026-03-23 21:24:30', '2026-03-23 21:24:30');

-- ----------------------------
-- Table structure for system_dict_type
-- ----------------------------
DROP TABLE IF EXISTS `system_dict_type`;
CREATE TABLE `system_dict_type`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典名称',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典标示',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '状态 (1正常 2停用)',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_code`(`code`) USING BTREE,
  INDEX `idx_name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字典类型表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_dict_type
-- ----------------------------
INSERT INTO `system_dict_type` VALUES (2, '存储模式', 'upload_mode', 1, '上传文件存储模式111', 1, 1, '2026-01-01 00:00:00', '2026-03-23 21:19:39', NULL);
INSERT INTO `system_dict_type` VALUES (3, '数据状态', 'data_status', 1, '通用数据状态', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_type` VALUES (4, '后台首页', 'dashboard', 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_type` VALUES (5, '性别', 'gender', 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-23 21:24:56', NULL);
INSERT INTO `system_dict_type` VALUES (12, '附件类型', 'attachment_type', 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_type` VALUES (13, '菜单类型', 'menu_type', 1, '', 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_dict_type` VALUES (14, '是否', 'yes_or_no', 1, '11', 1, 1, '2026-01-01 00:00:00', '2026-03-28 18:03:01', NULL);
INSERT INTO `system_dict_type` VALUES (20, '定时任务类型', 'crontab_task_type', 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-31 00:30:35', NULL);
INSERT INTO `system_dict_type` VALUES (21, '111', '111', 1, '', 1, 1, '2026-03-23 21:25:10', '2026-03-23 22:48:47', '2026-03-23 22:48:47');

-- ----------------------------
-- Table structure for system_login_log
-- ----------------------------
DROP TABLE IF EXISTS `system_login_log`;
CREATE TABLE `system_login_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `ip` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登录IP地址',
  `ip_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'IP所属地',
  `os` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作系统',
  `browser` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '浏览器',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '登录状态 (1成功 2失败)',
  `message` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '提示消息',
  `login_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '登录时间',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE,
  INDEX `idx_create_time`(`create_time`) USING BTREE,
  INDEX `idx_login_time`(`login_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 503 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '登录日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_login_log
-- ----------------------------
INSERT INTO `system_login_log` VALUES (453, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-14 23:13:56', NULL, NULL, NULL, '2026-05-14 23:13:56', '2026-05-14 23:13:56', NULL);
INSERT INTO `system_login_log` VALUES (454, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 00:14:50', NULL, NULL, NULL, '2026-05-15 00:14:50', '2026-05-15 00:14:50', NULL);
INSERT INTO `system_login_log` VALUES (455, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 00:15:08', NULL, NULL, NULL, '2026-05-15 00:15:08', '2026-05-15 00:15:08', NULL);
INSERT INTO `system_login_log` VALUES (456, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 00:15:48', NULL, NULL, NULL, '2026-05-15 00:15:48', '2026-05-15 00:15:48', NULL);
INSERT INTO `system_login_log` VALUES (457, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 00:17:29', NULL, NULL, NULL, '2026-05-15 00:17:29', '2026-05-15 00:17:29', NULL);
INSERT INTO `system_login_log` VALUES (458, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:37:22', NULL, NULL, NULL, '2026-05-15 07:37:22', '2026-05-15 07:37:22', NULL);
INSERT INTO `system_login_log` VALUES (459, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:49:43', NULL, NULL, NULL, '2026-05-15 07:49:43', '2026-05-15 07:49:43', NULL);
INSERT INTO `system_login_log` VALUES (460, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:50:37', NULL, NULL, NULL, '2026-05-15 07:50:37', '2026-05-15 07:50:37', NULL);
INSERT INTO `system_login_log` VALUES (461, 'timi_boss', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:51:18', NULL, NULL, NULL, '2026-05-15 07:51:18', '2026-05-15 07:51:18', NULL);
INSERT INTO `system_login_log` VALUES (462, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:52:20', NULL, NULL, NULL, '2026-05-15 07:52:20', '2026-05-15 07:52:20', NULL);
INSERT INTO `system_login_log` VALUES (463, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:55:54', NULL, NULL, NULL, '2026-05-15 07:55:54', '2026-05-15 07:55:54', NULL);
INSERT INTO `system_login_log` VALUES (464, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:56:58', NULL, NULL, NULL, '2026-05-15 07:56:58', '2026-05-15 07:56:58', NULL);
INSERT INTO `system_login_log` VALUES (465, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 07:59:50', NULL, NULL, NULL, '2026-05-15 07:59:50', '2026-05-15 07:59:50', NULL);
INSERT INTO `system_login_log` VALUES (466, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 08:03:37', NULL, NULL, NULL, '2026-05-15 08:03:37', '2026-05-15 08:03:37', NULL);
INSERT INTO `system_login_log` VALUES (467, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 08:04:07', NULL, NULL, NULL, '2026-05-15 08:04:07', '2026-05-15 08:04:07', NULL);
INSERT INTO `system_login_log` VALUES (468, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 08:04:48', NULL, NULL, NULL, '2026-05-15 08:04:48', '2026-05-15 08:04:48', NULL);
INSERT INTO `system_login_log` VALUES (469, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 18:15:04', NULL, NULL, NULL, '2026-05-15 18:15:04', '2026-05-15 18:15:04', NULL);
INSERT INTO `system_login_log` VALUES (470, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 18:38:58', NULL, NULL, NULL, '2026-05-15 18:38:58', '2026-05-15 18:38:58', NULL);
INSERT INTO `system_login_log` VALUES (471, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 18:39:13', NULL, NULL, NULL, '2026-05-15 18:39:13', '2026-05-15 18:39:13', NULL);
INSERT INTO `system_login_log` VALUES (472, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 18:40:14', NULL, NULL, NULL, '2026-05-15 18:40:14', '2026-05-15 18:40:14', NULL);
INSERT INTO `system_login_log` VALUES (473, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 18:45:10', NULL, NULL, NULL, '2026-05-15 18:45:10', '2026-05-15 18:45:10', NULL);
INSERT INTO `system_login_log` VALUES (474, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 19:04:11', NULL, NULL, NULL, '2026-05-15 19:04:11', '2026-05-15 19:04:11', NULL);
INSERT INTO `system_login_log` VALUES (475, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 19:04:54', NULL, NULL, NULL, '2026-05-15 19:04:54', '2026-05-15 19:04:54', NULL);
INSERT INTO `system_login_log` VALUES (476, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 19:05:46', NULL, NULL, NULL, '2026-05-15 19:05:46', '2026-05-15 19:05:46', NULL);
INSERT INTO `system_login_log` VALUES (477, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 22:02:25', NULL, NULL, NULL, '2026-05-15 22:02:25', '2026-05-15 22:02:25', NULL);
INSERT INTO `system_login_log` VALUES (478, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 22:50:32', NULL, NULL, NULL, '2026-05-15 22:50:32', '2026-05-15 22:50:32', NULL);
INSERT INTO `system_login_log` VALUES (479, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 22:51:12', NULL, NULL, NULL, '2026-05-15 22:51:12', '2026-05-15 22:51:12', NULL);
INSERT INTO `system_login_log` VALUES (480, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 22:52:08', NULL, NULL, NULL, '2026-05-15 22:52:08', '2026-05-15 22:52:08', NULL);
INSERT INTO `system_login_log` VALUES (481, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 22:54:01', NULL, NULL, NULL, '2026-05-15 22:54:01', '2026-05-15 22:54:01', NULL);
INSERT INTO `system_login_log` VALUES (482, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 23:16:58', NULL, NULL, NULL, '2026-05-15 23:16:58', '2026-05-15 23:16:58', NULL);
INSERT INTO `system_login_log` VALUES (483, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-15 23:25:40', NULL, NULL, NULL, '2026-05-15 23:25:40', '2026-05-15 23:25:40', NULL);
INSERT INTO `system_login_log` VALUES (484, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 16:55:56', NULL, NULL, NULL, '2026-05-16 16:55:56', '2026-05-16 16:55:56', NULL);
INSERT INTO `system_login_log` VALUES (485, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 18:45:26', NULL, NULL, NULL, '2026-05-16 18:45:26', '2026-05-16 18:45:26', NULL);
INSERT INTO `system_login_log` VALUES (486, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 18:45:52', NULL, NULL, NULL, '2026-05-16 18:45:52', '2026-05-16 18:45:52', NULL);
INSERT INTO `system_login_log` VALUES (487, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 18:53:37', NULL, NULL, NULL, '2026-05-16 18:53:37', '2026-05-16 18:53:37', NULL);
INSERT INTO `system_login_log` VALUES (488, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 18:53:58', NULL, NULL, NULL, '2026-05-16 18:53:58', '2026-05-16 18:53:58', NULL);
INSERT INTO `system_login_log` VALUES (489, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 19:00:54', NULL, NULL, NULL, '2026-05-16 19:00:54', '2026-05-16 19:00:54', NULL);
INSERT INTO `system_login_log` VALUES (490, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 19:01:19', NULL, NULL, NULL, '2026-05-16 19:01:19', '2026-05-16 19:01:19', NULL);
INSERT INTO `system_login_log` VALUES (491, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 19:02:30', NULL, NULL, NULL, '2026-05-16 19:02:30', '2026-05-16 19:02:30', NULL);
INSERT INTO `system_login_log` VALUES (492, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 19:03:54', NULL, NULL, NULL, '2026-05-16 19:03:54', '2026-05-16 19:03:54', NULL);
INSERT INTO `system_login_log` VALUES (493, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 19:05:43', NULL, NULL, NULL, '2026-05-16 19:05:43', '2026-05-16 19:05:43', NULL);
INSERT INTO `system_login_log` VALUES (494, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 19:06:44', NULL, NULL, NULL, '2026-05-16 19:06:44', '2026-05-16 19:06:44', NULL);
INSERT INTO `system_login_log` VALUES (495, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 20:39:58', NULL, NULL, NULL, '2026-05-16 20:39:58', '2026-05-16 20:39:58', NULL);
INSERT INTO `system_login_log` VALUES (496, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 20:40:57', NULL, NULL, NULL, '2026-05-16 20:40:57', '2026-05-16 20:40:57', NULL);
INSERT INTO `system_login_log` VALUES (497, 'devwang', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 20:41:40', NULL, NULL, NULL, '2026-05-16 20:41:40', '2026-05-16 20:41:40', NULL);
INSERT INTO `system_login_log` VALUES (498, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 20:42:35', NULL, NULL, NULL, '2026-05-16 20:42:35', '2026-05-16 20:42:35', NULL);
INSERT INTO `system_login_log` VALUES (499, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-16 21:33:30', NULL, NULL, NULL, '2026-05-16 21:33:30', '2026-05-16 21:33:30', NULL);
INSERT INTO `system_login_log` VALUES (500, 'admin', '127.0.0.1', '', 'Windows', 'Chrome', 1, '登录成功', '2026-05-17 09:28:27', NULL, NULL, NULL, '2026-05-17 09:28:27', '2026-05-17 09:28:27', NULL);
INSERT INTO `system_login_log` VALUES (501, 'admin', '127.0.0.1', '本地', 'Windows', 'Chrome', 1, '登录成功', '2026-07-22 15:00:14', NULL, NULL, NULL, '2026-07-22 15:00:14', '2026-07-22 15:00:14', NULL);
INSERT INTO `system_login_log` VALUES (502, 'admin', '127.0.0.1', '本地', 'Windows', 'Chrome', 1, '登录成功', '2026-07-22 15:00:36', NULL, NULL, NULL, '2026-07-22 15:00:36', '2026-07-22 15:00:36', NULL);

-- ----------------------------
-- Table structure for system_mail
-- ----------------------------
DROP TABLE IF EXISTS `system_mail`;
CREATE TABLE `system_mail`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `gateway` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '网关',
  `from` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发送人',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '接收人',
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '验证码',
  `content` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮箱内容',
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发送状态',
  `response` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '返回结果',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '邮件记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_mail
-- ----------------------------
INSERT INTO `system_mail` VALUES (1, 'mail.163.com', 'test@qq.com', 'admin@qq.com', '869', 'hello', 'success', 'data', '2026-03-23 19:53:53', NULL, NULL);
INSERT INTO `system_mail` VALUES (2, 'mail.qq.com', 'admin@test.com', 'admin@qq.com', '456', 'test', 'failure', 'data', '2026-03-23 19:52:49', '2026-03-28 18:41:38', NULL);

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '父级ID',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '菜单名称',
  `code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '组件名称',
  `slug` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限标识，如 user:list, user:add',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型: 1目录, 2菜单, 3按钮/API',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路由地址(前端)或API路径(后端)',
  `component` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '前端组件路径，如 layout/User',
  `method` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '请求方式',
  `icon` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标',
  `sort` int(11) NULL DEFAULT 100 COMMENT '排序',
  `link_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '外部链接',
  `is_iframe` tinyint(1) NULL DEFAULT 2 COMMENT '是否iframe',
  `is_keep_alive` tinyint(1) NULL DEFAULT 2 COMMENT '是否缓存',
  `is_hidden` tinyint(1) NULL DEFAULT 2 COMMENT '是否隐藏',
  `is_fixed_tab` tinyint(1) NULL DEFAULT 2 COMMENT '是否固定标签页',
  `is_full_page` tinyint(1) NULL DEFAULT 2 COMMENT '是否全屏',
  `generate_id` int(11) NULL DEFAULT 0 COMMENT '生成id',
  `generate_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生成key',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_parent_id`(`parent_id`) USING BTREE,
  INDEX `idx_slug`(`slug`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 184 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '菜单权限表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES (1, 0, '仪表盘', 'Dashboard', NULL, 1, '/dashboard', '', NULL, 'ri:pie-chart-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (2, 1, '工作台', 'Console', '', 2, 'console', '/dashboard/console', NULL, 'ri:home-smile-2-line', 100, '', 2, 2, 2, 1, 2, 0, NULL, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-24 22:06:42', NULL);
INSERT INTO `system_menu` VALUES (3, 0, '系统管理', 'System', NULL, 1, '/system', '', NULL, 'ri:user-3-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (4, 3, '用户管理', 'User', NULL, 2, 'user', '/system/user', NULL, 'ri:user-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (5, 3, '部门管理', 'Dept', NULL, 2, 'dept', '/system/dept', NULL, 'ri:node-tree', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (6, 3, '角色管理', 'Role', NULL, 2, 'role', '/system/role', NULL, 'ri:admin-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (7, 3, '岗位管理', 'Post', '', 2, 'post', '/system/post', NULL, 'ri:signpost-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (8, 3, '菜单管理', 'Menu', NULL, 2, 'menu', '/system/menu', NULL, 'ri:menu-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (10, 0, '运维管理', 'Safeguard', NULL, 1, '/safeguard', '', NULL, 'ri:shield-check-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (11, 10, '缓存管理', 'Cache', '', 2, 'cache', '/safeguard/cache', NULL, 'ri:keyboard-box-line', 80, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (12, 10, '数据字典', 'Dict', NULL, 2, 'dict', '/safeguard/dict', NULL, 'ri:database-2-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (13, 10, '附件管理', 'Attachment', '', 2, 'attachment', '/safeguard/attachment', NULL, 'ri:file-cloud-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (14, 10, '数据表维护', 'Database', '', 2, 'database', '/safeguard/database', NULL, 'ri:database-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (15, 10, '登录日志', 'LoginLog', '', 2, 'login-log', '/safeguard/login-log', NULL, 'ri:login-circle-line', 50, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (16, 10, '操作日志', 'OperLog', '', 2, 'oper-log', '/safeguard/oper-log', NULL, 'ri:shield-keyhole-line', 50, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (17, 10, '邮件日志', 'EmailLog', '', 2, 'email-log', '/safeguard/email-log', NULL, 'ri:mail-line', 50, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (18, 3, '系统设置', 'Config', NULL, 2, 'config', '/system/config', NULL, 'ri:settings-4-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (19, 0, '官方文档', 'Document', '', 4, '', '', NULL, 'ri:file-copy-2-fill', 102, 'https://v3.phpframe.org', 1, 2, 2, 2, 2, 0, NULL, 0, '', 1, 1, '2026-01-01 00:00:00', '2026-05-16 18:00:47', NULL);
INSERT INTO `system_menu` VALUES (20, 4, '数据列表', '', 'core:user:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (21, 1, '个人中心', 'UserCenter', '', 2, 'user-center', '/dashboard/user-center/index', NULL, 'ri:user-2-line', 100, '', 2, 2, 1, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (22, 4, '添加', '', 'core:user:save', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (23, 4, '修改', '', 'core:user:update', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (24, 4, '读取', '', 'core:user:read', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (25, 4, '删除', '', 'core:user:destroy', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (26, 4, '重置密码', '', 'core:user:password', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (27, 4, '清理缓存', '', 'core:user:cache', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (28, 4, '设置工作台', '', 'core:user:home', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (29, 5, '数据列表', '', 'core:dept:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (30, 5, '添加', '', 'core:dept:save', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (31, 5, '修改', '', 'core:dept:update', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (32, 5, '读取', '', 'core:dept:read', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (33, 5, '删除', '', 'core:dept:destroy', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (34, 6, '添加', '', 'core:role:save', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (35, 6, '数据列表', '', 'core:role:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (36, 6, '修改', '', 'core:role:update', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (37, 6, '读取', '', 'core:role:read', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (38, 6, '删除', '', 'core:role:destroy', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (39, 6, '菜单权限', '', 'core:role:menu', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (41, 7, '数据列表', '', 'core:post:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (42, 7, '添加', '', 'core:post:save', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (43, 7, '修改', '', 'core:post:update', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (44, 7, '读取', '', 'core:post:read', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (45, 7, '删除', '', 'core:post:destroy', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (46, 7, '导入', '', 'core:post:import', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (47, 7, '导出', '', 'core:post:export', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (48, 8, '数据列表', '', 'core:menu:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (49, 8, '读取', '', 'core:menu:read', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (50, 8, '添加', '', 'core:menu:save', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (51, 8, '修改', '', 'core:menu:update', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (52, 8, '删除', '', 'core:menu:destroy', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (53, 18, '数据列表', '', 'core:config:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (54, 18, '管理', '', 'core:config:edit', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (55, 18, '修改', '', 'core:config:update', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (56, 12, '数据列表', '', 'core:dict:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (57, 12, '管理', '', 'core:dict:edit', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (58, 13, '数据列表', '', 'core:attachment:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (59, 13, '管理', '', 'core:attachment:edit', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (60, 14, '数据表列表', '', 'core:database:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (61, 14, '数据表维护', '', 'core:database:edit', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (62, 14, '回收站数据', '', 'core:recycle:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (63, 14, '回收站管理', '', 'core:recycle:edit', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (64, 15, '数据列表', '', 'core:logs:login', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (65, 15, '删除', '', 'core:logs:deleteLogin', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (66, 16, '数据列表', '', 'core:logs:Oper', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (67, 16, '删除', '', 'core:logs:deleteOper', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (68, 17, '数据列表', '', 'core:email:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (69, 17, '删除', '', 'core:email:destroy', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (70, 10, '服务监控', 'Server', '', 2, 'server', '/safeguard/server', NULL, 'ri:server-line', 90, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (71, 70, '数据列表', '', 'core:server:monitor', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (72, 11, '数据列表', '', 'core:server:cache', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (73, 11, '缓存清理', '', 'core:server:clear', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (74, 2, '登录数据统计', '', 'core:console:list', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (75, 0, '附加权限', 'Permission', '', 1, 'permission', '', NULL, 'ri:apps-2-ai-line', 100, '', 2, 2, 1, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (76, 75, '上传图片', '', 'core:system:uploadImage', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (77, 75, '上传文件', '', 'core:system:uploadFile', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (78, 75, '附件列表', '', 'core:system:resource', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (79, 75, '用户列表', '', 'core:system:user', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (80, 0, '开发工具', 'Tool', '', 1, '/tool', '', NULL, 'ri:tools-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-03-23 23:03:00', NULL);
INSERT INTO `system_menu` VALUES (81, 80, '代码生成', 'Code', '', 2, 'code', '/tool/code', NULL, 'ri:code-s-slash-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-04-26 17:08:04', NULL);
INSERT INTO `system_menu` VALUES (82, 80, '定时任务', 'Crontab', '', 2, 'crontab', '/tool/crontab', NULL, 'ri:time-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (83, 82, '数据列表', '', 'tool:crontab:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (84, 82, '管理', '', 'tool:crontab:edit', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (85, 82, '运行任务', '', 'tool:crontab:run', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (86, 81, '数据列表', '', 'tool:code:index', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (87, 81, '管理', '', 'tool:code:edit', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, NULL, 1, 1, '2026-01-01 00:00:00', '2026-01-01 00:00:00', NULL);
INSERT INTO `system_menu` VALUES (88, 80, '插件市场', 'Plugin', '', 2, '/plugin', '/system/plugin/index', NULL, 'ri:apps-2-ai-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-01-01 00:00:00', '2026-05-16 17:06:49', NULL);
INSERT INTO `system_menu` VALUES (92, 4, '分配菜单', '', 'core:user:menu', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 0, '', 1, 1, '2026-03-24 22:56:49', '2026-03-24 22:56:49', NULL);
INSERT INTO `system_menu` VALUES (93, 1, '分析页', 'Analysis', '', 2, 'analysis', '/dashboard/analysis', NULL, 'ri:file-music-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-03-25 20:28:34', '2026-03-29 14:28:06', NULL);
INSERT INTO `system_menu` VALUES (94, 1, '电子商务', 'Ecommerce', '', 2, 'ecommerce', '/dashboard/ecommerce', NULL, 'ri:bootstrap-fill', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-03-25 20:42:29', '2026-03-29 14:39:56', NULL);
INSERT INTO `system_menu` VALUES (95, 80, '表单示例', 'Form', '', 2, 'form', '/tool/form', NULL, 'ri:article-line', 100, '', 2, 2, 2, 2, 2, 0, NULL, 0, '', 1, 1, '2026-03-25 20:47:03', '2026-03-25 20:47:44', NULL);
INSERT INTO `system_menu` VALUES (96, 88, '111', NULL, 'chajian:market:add', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-03-28 19:41:47', '2026-03-28 20:05:26', '2026-03-28 20:05:26');
INSERT INTO `system_menu` VALUES (97, 5, '菜单树', NULL, 'core:dept:tree', 3, '', '', NULL, '', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-03-29 18:03:52', '2026-03-29 18:03:52', NULL);
INSERT INTO `system_menu` VALUES (104, 10, 'Redis监控', 'Redis', 'core:server:redis', 2, 'redis', '/safeguard/redis', NULL, 'ri:exchange-cny-fill', 100, '', 2, 2, 2, 2, 2, 0, NULL, 1, '', 1, 1, '2026-04-20 22:44:29', '2026-04-21 00:33:07', NULL);
INSERT INTO `system_menu` VALUES (173, 1, 'HRM看板', 'Hrm', '', 2, 'hrm', '/dashboard/hrm', NULL, 'ri:team-line', 100, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, '人力资源看板', 1, 1, '2026-05-06 23:15:10', '2026-05-06 23:15:10', NULL);
INSERT INTO `system_menu` VALUES (183, 1, '赞助支持', 'DashboardSponsor', '', 2, 'sponsor', '/dashboard/sponsor', NULL, 'ri:hand-heart-line', 110, NULL, 2, 2, 2, 2, 2, 0, NULL, 1, '项目赞助说明页', 1, 1, '2026-05-16 21:08:42', '2026-05-16 21:08:42', NULL);

-- ----------------------------
-- Table structure for system_oper_log
-- ----------------------------
DROP TABLE IF EXISTS `system_oper_log`;
CREATE TABLE `system_oper_log`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `app` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '应用名称',
  `method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '请求方式',
  `router` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '请求路由',
  `service_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '业务名称',
  `ip` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '请求IP地址',
  `ip_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'IP所属地',
  `request_data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '请求数据',
  `duration` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '耗时',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE,
  INDEX `idx_create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1290 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '操作日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for system_post
-- ----------------------------
DROP TABLE IF EXISTS `system_post`;
CREATE TABLE `system_post`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '岗位名称',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '岗位代码',
  `sort` smallint(5) UNSIGNED NULL DEFAULT 0 COMMENT '排序',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '状态 (1正常 2停用)',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '岗位信息表' ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for system_role
-- ----------------------------
DROP TABLE IF EXISTS `system_role`;
CREATE TABLE `system_role`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父角色ID，0表示顶级角色',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色标识(英文唯一)，如: hr_manager',
  `level` int(11) NULL DEFAULT 1 COMMENT '角色级别(1-100)：用于行政控制，不可操作级别>=自己的角色',
  `data_scope` tinyint(4) NULL DEFAULT 1 COMMENT '数据范围: 1全部, 2本部门及下属, 3本部门, 4仅本人, 5自定义',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `sort` int(11) NULL DEFAULT 100,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态: 1启用, 0禁用',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_slug`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 205 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色表' ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for system_role_dept
-- ----------------------------
DROP TABLE IF EXISTS `system_role_dept`;
CREATE TABLE `system_role_dept`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `dept_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_role_id`(`role_id`) USING BTREE,
  INDEX `idx_dept_id`(`dept_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色-自定义数据权限关联' ROW_FORMAT = DYNAMIC;



-- ----------------------------
-- Table structure for system_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_role_menu`;
CREATE TABLE `system_role_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_role_menu_tenant`(`role_id`, `menu_id`) USING BTREE,
  INDEX `idx_menu_id`(`menu_id`) USING BTREE,
  INDEX `idx_role_id`(`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 519 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色权限关联' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_role_menu
-- ----------------------------

-- ----------------------------
-- Table structure for system_user
-- ----------------------------
DROP TABLE IF EXISTS `system_user`;
CREATE TABLE `system_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '登录账号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '加密密码',
  `realname` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '真实姓名',
  `gender` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '性别',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `signed` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '个性签名',
  `dashboard` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'work' COMMENT '工作台',
  `dept_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '主归属部门',
  `is_super` tinyint(1) NULL DEFAULT 0 COMMENT '是否超级管理员: 1是(跳过权限检查), 0否',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态: 1启用, 0禁用',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `login_time` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
  `login_ip` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '最后登录IP',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_username`(`username`) USING BTREE,
  INDEX `idx_dept_id`(`dept_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 124 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_user
-- ----------------------------
INSERT INTO `system_user` VALUES (1, 'admin', '$2y$10$wnixh48uDnaW/6D9EygDd.OHJK0vQY/4nHaTjMKBCVDBP2NiTatqS', '冷月如霜', '1', '/uploads/2026/03/28/69c7a15ee50ff0.57272058.jpg', 'eqrayphp@admin.com', '15888888888', 'eqrayadmin是兼具设计美学与高效开发的后台系统!11', 'statistics', 1, 1, 1, NULL, '2026-07-22 15:00:36', '127.0.0.1', 1, 1, '2026-01-01 00:00:00', '2026-07-22 15:00:36', NULL);

-- ----------------------------
-- Table structure for system_user_dept
-- ----------------------------
DROP TABLE IF EXISTS `system_user_dept`;
CREATE TABLE `system_user_dept`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `dept_id` bigint(20) UNSIGNED NOT NULL COMMENT '部门ID',
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '创建人ID',
  `updated_by` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '更新人ID',
  `create_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_user_tenant`(`user_id`) USING BTREE COMMENT '用户租户唯一索引',
  INDEX `idx_user_id`(`user_id`) USING BTREE,
  INDEX `idx_dept_id`(`dept_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of system_user_dept
-- ----------------------------

-- ----------------------------
-- Table structure for system_user_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_user_menu`;
CREATE TABLE `system_user_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `menu_id` bigint(20) UNSIGNED NOT NULL COMMENT '菜单ID',
  `created_by` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '创建人ID',
  `updated_by` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '更新人ID',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0=禁用 1=启用',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_user_menu`(`user_id`, `menu_id`) USING BTREE,
  INDEX `idx_user_id`(`user_id`) USING BTREE,
  INDEX `idx_menu_id`(`menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 789 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户菜单关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_user_menu
-- ----------------------------

-- ----------------------------
-- Table structure for system_user_post
-- ----------------------------
DROP TABLE IF EXISTS `system_user_post`;
CREATE TABLE `system_user_post`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户主键',
  `post_id` bigint(20) UNSIGNED NOT NULL COMMENT '岗位主键',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0=禁用 1=启用',
  `created_by` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `updated_by` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新人ID',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  `tenant_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '租户上下文ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_id`(`user_id`) USING BTREE,
  INDEX `idx_post_id`(`post_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户与岗位关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for system_user_role
-- ----------------------------
DROP TABLE IF EXISTS `system_user_role`;
CREATE TABLE `system_user_role`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0=禁用 1=启用',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_role_id`(`role_id`) USING BTREE,
  INDEX `idx_user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 84 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色关联' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_user_role
-- ----------------------------

-- ----------------------------
-- Table structure for tool_crontab
-- ----------------------------
DROP TABLE IF EXISTS `tool_crontab`;
CREATE TABLE `tool_crontab`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务名称',
  `type` smallint(6) NULL DEFAULT 4 COMMENT '任务类型',
  `target` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '调用任务字符串',
  `parameter` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '调用任务参数',
  `task_style` tinyint(1) NULL DEFAULT NULL COMMENT '执行类型',
  `rule` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务执行表达式',
  `singleton` smallint(6) NULL DEFAULT 1 COMMENT '是否单次执行 (1 是 2 不是)',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '状态 (1正常 2停用)',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '定时任务信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tool_crontab_log
-- ----------------------------
DROP TABLE IF EXISTS `tool_crontab_log`;
CREATE TABLE `tool_crontab_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `crontab_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '任务ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务名称',
  `target` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务调用目标字符串',
  `parameter` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任务调用参数',
  `exception_info` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '异常信息',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '执行状态 (1成功 2失败)',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '定时任务执行日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tool_generate_columns
-- ----------------------------
DROP TABLE IF EXISTS `tool_generate_columns`;
CREATE TABLE `tool_generate_columns`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `table_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '所属表ID',
  `column_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字段名称',
  `column_comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字段注释',
  `column_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字段类型',
  `default_value` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '默认值',
  `is_pk` smallint(6) NULL DEFAULT 1 COMMENT '1 非主键 2 主键',
  `is_required` smallint(6) NULL DEFAULT 1 COMMENT '1 非必填 2 必填',
  `is_insert` smallint(6) NULL DEFAULT 1 COMMENT '1 非插入字段 2 插入字段',
  `is_edit` smallint(6) NULL DEFAULT 1 COMMENT '1 非编辑字段 2 编辑字段',
  `is_list` smallint(6) NULL DEFAULT 1 COMMENT '1 非列表显示字段 2 列表显示字段',
  `is_query` smallint(6) NULL DEFAULT 1 COMMENT '1 非查询字段 2 查询字段',
  `is_sort` smallint(6) NULL DEFAULT 1 COMMENT '1 非排序 2 排序',
  `query_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'eq' COMMENT '查询方式 eq 等于, neq 不等于, gt 大于, lt 小于, like 范围',
  `view_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'text' COMMENT '页面控件,text, textarea, password, select, checkbox, radio, date, upload, ma-upload(封装的上传控件)',
  `dict_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字典类型',
  `allow_roles` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '允许查看该字段的角色',
  `options` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字段其他设置',
  `sort` tinyint(3) UNSIGNED NULL DEFAULT 0 COMMENT '排序',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 463 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '代码生成业务字段表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tool_generate_tables
-- ----------------------------
DROP TABLE IF EXISTS `tool_generate_tables`;
CREATE TABLE `tool_generate_tables`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `table_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '表名称',
  `table_comment` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '表注释',
  `stub` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'stub类型',
  `template` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '模板名称',
  `namespace` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '命名空间',
  `package_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '控制器包名',
  `business_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '业务名称',
  `class_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类名称',
  `menu_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生成菜单名',
  `belong_menu_id` int(11) NULL DEFAULT NULL COMMENT '所属菜单',
  `tpl_category` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生成类型,single 单表CRUD,tree 树表CRUD,parent_sub父子表CRUD',
  `generate_type` smallint(6) NULL DEFAULT 1 COMMENT '1 压缩包下载 2 生成到模块',
  `generate_path` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'saiadmin-artd' COMMENT '前端根目录',
  `generate_model` smallint(6) NULL DEFAULT 1 COMMENT '1 软删除 2 非软删除',
  `generate_menus` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生成菜单列表',
  `build_menu` smallint(6) NULL DEFAULT 1 COMMENT '是否构建菜单',
  `component_type` smallint(6) NULL DEFAULT 1 COMMENT '组件显示方式',
  `options` varchar(1500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '其他业务选项',
  `form_width` int(11) NULL DEFAULT 800 COMMENT '表单宽度',
  `is_full` tinyint(1) NULL DEFAULT 1 COMMENT '是否全屏',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `source` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '数据源',
  `created_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '代码生成业务表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool_generate_tables
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
