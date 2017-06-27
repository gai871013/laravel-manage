-- --------------------------------------------------------
-- 主机:                           192.168.10.10
-- 服务器版本:                        5.7.17-0ubuntu0.16.04.2 - (Ubuntu)
-- 服务器操作系统:                      Linux
-- HeidiSQL 版本:                  9.4.0.5174
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 导出 telecom_retain 的数据库结构
CREATE DATABASE IF NOT EXISTS `telecom_retain` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `telecom_retain`;

-- 导出  表 telecom_retain.admins 结构
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '昵称',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `action_list` text COLLATE utf8mb4_unicode_ci COMMENT '权限列表',
  `last_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '::1' COMMENT '最后登录IP',
  `last_login` timestamp NULL DEFAULT NULL COMMENT '上次登录时间',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  `login_num` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '本次登录IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.admins 的数据：~4 rows (大约)
DELETE FROM `admins`;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` (`id`, `username`, `email`, `password`, `name`, `nickname`, `role_id`, `action_list`, `last_ip`, `last_login`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `login_num`, `ip`) VALUES
	(1, 'admin', 'wang.gaichao@163.com', '$2y$10$IHE3SWwmUKBDep5ObmtD6e3JcVRsUMXFNmDSHe1Xg59ukuu6n18py', 'wgc', 'gai871013', 1, NULL, '192.168.10.1', '2017-06-27 14:02:17', 'EHJsTJ56XgK4lniNACpIC9mIoPCUeWV6TyftXH4vHDthGYCOBFJhPaRiJfHL', '2017-06-23 09:47:34', '2017-06-27 14:02:30', NULL, 4, '192.168.10.1'),
	(2, 'admin8', 'admin@admin.com', '$2y$10$f5PdWQKsAosU7LaBthaVoe737syxwvqfrjaBS2.AMm0KaXeoCePEC', 'test3', 'admin3', 4, NULL, '::1', NULL, 'LMSffhfaR6', '2017-06-23 09:47:34', '2017-06-23 17:20:40', NULL, 0, '::1'),
	(3, 'admin2', 'dusty.gaylord@example.org', '$2y$10$tCXhuSU0Iay3B8l0vFE/PeLPTeoEWSHt7oB7cJcN.vU8KltycDN4y', 'test', 'test', 2, NULL, '::1', NULL, 'e0G2tUQPPP', '2017-06-23 09:47:34', '2017-06-26 09:46:17', NULL, 0, '::1'),
	(4, 'admin4', 'admin4@admin.com', '$2y$10$rT9llEh.Z.hDPlL8es.LR.W7PzwTom5COijVytFzuQRpr//Kb7Lr.', 'admin4', 'admin44', 1, NULL, '::1', NULL, 'CO4ksDGQR50DpFOESs8QRsbpWkxKXzPZg9tVDVwT7dN8yIe8fLjtS3dig9v7', '2017-06-23 17:12:06', '2017-06-27 13:39:18', NULL, 0, '::1');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;

-- 导出  表 telecom_retain.admin_actions 结构
CREATE TABLE IF NOT EXISTS `admin_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '该id项的父id，对应本表的id字段',
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '代表权限的英文字符串，对应汉文在语言文件中，如果该字段有某个字符串，就表示有该权限',
  `lang` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限名称',
  `route` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '目录名',
  `param` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '参数',
  `enable` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示/使用',
  `remark` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `icon` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `list_order` int(11) NOT NULL DEFAULT '10' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.admin_actions 的数据：~26 rows (大约)
DELETE FROM `admin_actions`;
/*!40000 ALTER TABLE `admin_actions` DISABLE KEYS */;
INSERT INTO `admin_actions` (`id`, `parent_id`, `code`, `lang`, `route`, `param`, `enable`, `remark`, `icon`, `list_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 0, 'game', 'game', '', '', 0, '', 'gamepad', 1, NULL, '2017-06-26 17:10:40', '2017-06-26 17:10:40'),
	(2, 0, 'news', 'news', '', '', 0, '', 'newspaper-o', 1, NULL, '2017-06-27 09:31:37', '2017-06-27 09:31:37'),
	(3, 0, 'user', 'user', '', '', 1, '', 'users', 1, NULL, '2017-06-27 14:03:06', NULL),
	(4, 1, 'order', 'order', '', '', 0, '', 'shopping-cart', 6, NULL, '2017-06-26 17:10:40', '2017-06-26 17:10:40'),
	(5, 0, 'permissions', 'permissions', '', '', 1, '', 'wrench', 7, NULL, '2017-06-27 14:03:06', NULL),
	(6, 0, 'system', 'system', '', '', 1, '', 'cogs', 12, NULL, '2017-06-27 14:03:06', NULL),
	(7, 1, 'categoryManage', 'categoryManage', '', '', 1, '', 'book', 2, NULL, '2017-06-26 17:10:40', '2017-06-26 17:10:40'),
	(8, 1, 'addCategory', 'addCategory', '', '', 1, '', 'plus', 3, NULL, '2017-06-26 17:10:40', '2017-06-26 17:10:40'),
	(9, 1, 'gameModelManage', 'gameModelManage', '', '', 1, '', 'list-ol', 4, NULL, '2017-06-26 17:10:40', '2017-06-26 17:10:40'),
	(10, 1, 'addGameModel', 'addGameModel', '', '', 1, '', 'plus-circle', 5, NULL, '2017-06-26 17:10:40', '2017-06-26 17:10:40'),
	(11, 2, 'categoryManage', 'categoryManage', '', '', 1, '', 'list-ol', 2, NULL, '2017-06-27 09:31:37', '2017-06-27 09:31:37'),
	(12, 2, 'newsList', 'newsList', '', '', 1, '', 'list', 3, NULL, '2017-06-27 09:31:37', '2017-06-27 09:31:37'),
	(13, 2, 'addNews', 'addNews', '', '', 1, '', 'plus', 4, NULL, '2017-06-27 09:31:37', '2017-06-27 09:31:37'),
	(14, 2, 'addSinglePage', 'addSinglePage', '', '', 1, '', 'plus-circle', 5, NULL, '2017-06-27 09:31:37', '2017-06-27 09:31:37'),
	(15, 3, 'userManage', 'userManage', '', '', 1, '', 'users', 3, NULL, '2017-06-27 14:03:06', NULL),
	(16, 3, 'companyLists', 'companyList', '', '', 1, '', 'building', 2, NULL, '2017-06-27 14:03:06', NULL),
	(17, 5, 'adminManage', 'adminManage', '', '', 1, '', 'users', 8, NULL, '2017-06-27 14:03:06', NULL),
	(18, 5, 'roleManage', 'roleManage', '', '', 1, '', 'book', 9, NULL, '2017-06-27 14:03:06', NULL),
	(19, 6, 'config', 'systemConfig', '', '', 1, '', 'cog', 15, NULL, '2017-06-27 14:03:06', NULL),
	(20, 6, 'menuManage', 'menuManage', '', '', 1, '', 'book', 13, NULL, '2017-06-27 14:03:06', NULL),
	(21, 6, 'menuEdit', 'addMenu', 'admin.system.menuEdit', '', 1, '', 'plus', 14, NULL, '2017-06-27 14:03:06', NULL),
	(22, 3, 'userEdit', 'userEdit', '', '', 0, '', 'pencil', 4, '2017-06-27 11:19:47', '2017-06-27 14:03:06', NULL),
	(23, 3, 'userDelete', 'userDelete', '', '', 0, '', 'trash', 5, '2017-06-27 11:21:58', '2017-06-27 14:03:06', NULL),
	(24, 3, 'userAssign', 'userAssign', NULL, '', 0, '', 'calendar-plus-o', 6, '2017-06-27 11:26:56', '2017-06-27 14:03:06', NULL),
	(25, 5, 'roleEdit', 'roleEdit', NULL, '', 0, '', 'pencil', 10, '2017-06-27 11:31:17', '2017-06-27 14:03:06', NULL),
	(26, 5, 'roleDelete', 'roleDelete', NULL, '', 0, '', 'trash-o', 11, '2017-06-27 11:31:51', '2017-06-27 14:03:06', NULL);
/*!40000 ALTER TABLE `admin_actions` ENABLE KEYS */;

-- 导出  表 telecom_retain.company 结构
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公司名称',
  `account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公司账号',
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司简称',
  `organization_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '组织机构代码',
  `unique` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '工商执照注册号',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '企业类型',
  `corporate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '法定代表人/企业负责人姓名',
  `scope` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '经营范围(一般经营范围)',
  `pre_scope` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '经营范围(前置许可经营范围)',
  `scale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '企业规模 (选填)',
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '企业开户名称',
  `bank` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '企业开户银行',
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '企业银行账号',
  `company_tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司电话',
  `company_tel2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司电话2',
  `company_tel3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司电话3',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '注册地址',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '企业成立日期',
  `term` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '企业营业期限',
  `other` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '其他信息',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.company 的数据：~0 rows (大约)
DELETE FROM `company`;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
/*!40000 ALTER TABLE `company` ENABLE KEYS */;

-- 导出  表 telecom_retain.expenses 结构
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `car_id` int(11) NOT NULL DEFAULT '0' COMMENT '车辆ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `journey_id` int(11) NOT NULL DEFAULT '0' COMMENT '行程ID',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '费用类型',
  `time` timestamp NULL DEFAULT NULL COMMENT '时间',
  `fee` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '费用',
  `paid_fee` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '已支付费用',
  `unpaid_fee` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '未支付费用',
  `pay_info` text COLLATE utf8mb4_unicode_ci COMMENT '支付信息',
  `reimbursement` int(11) NOT NULL DEFAULT '0' COMMENT '报销状态',
  `cip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '::1' COMMENT '创建IP',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.expenses 的数据：~0 rows (大约)
DELETE FROM `expenses`;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;

-- 导出  表 telecom_retain.followers 结构
CREATE TABLE IF NOT EXISTS `followers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL DEFAULT '1' COMMENT '应用ID',
  `subscribe` int(11) NOT NULL COMMENT '是否关注',
  `subscribe_time` int(11) NOT NULL COMMENT '关注时间',
  `openid` varchar(28) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户OPENID',
  `nickname` text COLLATE utf8mb4_unicode_ci COMMENT '微信昵称',
  `sex` int(11) NOT NULL DEFAULT '0' COMMENT '性别',
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '省份',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '城市',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '国家',
  `language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '语言',
  `headimgurl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `unionid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `groupid` int(11) DEFAULT NULL COMMENT '分组',
  `tagid_list` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户被打上的标签ID列表',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `tel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '绑定手机号',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '地址',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '::1' COMMENT '操作IP',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `followers_openid_unique` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.followers 的数据：~0 rows (大约)
DELETE FROM `followers`;
/*!40000 ALTER TABLE `followers` DISABLE KEYS */;
/*!40000 ALTER TABLE `followers` ENABLE KEYS */;

-- 导出  表 telecom_retain.migrations 结构
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.migrations 的数据：~25 rows (大约)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2016_12_07_155329_create_admins_table', 1),
	(4, '2017_04_25_151153_create_uploads_table', 1),
	(5, '2017_04_25_155034_create_s_m_s_records_table', 1),
	(6, '2017_04_25_155431_create_sms_templates_table', 1),
	(7, '2017_04_26_073600_create_roles_table', 1),
	(8, '2017_04_26_075926_create_admin_actions_table', 1),
	(9, '2017_04_27_032130_create_followers_table', 1),
	(10, '2017_04_27_033045_create_good_netizens_marks_table', 1),
	(11, '2017_04_27_033106_create_good_netizens_declarations_table', 1),
	(12, '2017_04_27_033921_create_good_netizens_areas_table', 1),
	(13, '2017_05_05_024600_create_cars_table', 1),
	(14, '2017_05_05_025139_create_car_parts_table', 1),
	(15, '2017_05_05_025241_create_expenses_table', 1),
	(16, '2017_05_05_025311_create_tasks_table', 1),
	(17, '2017_05_05_025410_create_journeys_table', 1),
	(18, '2017_05_08_024209_add_column_to_users_table', 1),
	(19, '2017_05_08_090902_create_g_p_s_table', 1),
	(20, '2017_05_09_074128_add_column_to_gps_table', 1),
	(21, '2017_05_11_114404_add_follower_id_to_users_table', 1),
	(22, '2017_05_11_170631_add_column_to_journeys_table', 1),
	(23, '2017_05_12_092234_create_company_table', 1),
	(24, '2017_05_12_093050_add_cloumn_to_users_table', 1),
	(25, '2017_06_27_135526_add_column_to_admin_table', 2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- 导出  表 telecom_retain.password_resets 结构
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.password_resets 的数据：~0 rows (大约)
DELETE FROM `password_resets`;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- 导出  表 telecom_retain.roles 结构
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `describe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `action_list` text COLLATE utf8mb4_unicode_ci COMMENT '权限列表',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '等级',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.roles 的数据：~6 rows (大约)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `describe`, `action_list`, `level`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '超级管理员', '超级管理员', 'all', 0, NULL, NULL, NULL),
	(2, '站点管理员', '站点管理员', '7,11,17,18', 0, NULL, '2017-06-26 10:13:05', NULL),
	(3, '发布人员', '发布人员', '7,11,15,18,20', 0, NULL, '2017-06-26 09:33:40', NULL),
	(4, '运营总监', '运营总监', NULL, 0, NULL, '2017-06-26 09:33:35', NULL),
	(5, '编辑', '编辑', '11,18', 0, NULL, '2017-06-27 13:39:40', '2017-06-27 13:39:40'),
	(6, '总编', '总编', '', 0, NULL, '2017-06-27 11:34:06', '2017-06-27 11:34:06');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- 导出  表 telecom_retain.sms_records 结构
CREATE TABLE IF NOT EXISTS `sms_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建者user_id',
  `tel` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `code` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `cip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '::1' COMMENT '创建IP',
  `used` int(11) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '使用次数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.sms_records 的数据：~0 rows (大约)
DELETE FROM `sms_records`;
/*!40000 ALTER TABLE `sms_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_records` ENABLE KEYS */;

-- 导出  表 telecom_retain.sms_templates 结构
CREATE TABLE IF NOT EXISTS `sms_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '模版内容',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口地址',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'password',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default' COMMENT '类型',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.sms_templates 的数据：~0 rows (大约)
DELETE FROM `sms_templates`;
/*!40000 ALTER TABLE `sms_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_templates` ENABLE KEYS */;

-- 导出  表 telecom_retain.uploads 结构
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL DEFAULT '0' COMMENT '类型',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '后台用户ID',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '链接地址',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '目录',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `cip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '上传IP',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '大小 KB',
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '原始名称',
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'mime Type',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.uploads 的数据：~0 rows (大约)
DELETE FROM `uploads`;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;

-- 导出  表 telecom_retain.users 结构
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '昵称',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `action_list` text COLLATE utf8mb4_unicode_ci COMMENT '权限列表',
  `last_ip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '::1' COMMENT '最后登录IP',
  `last_login` timestamp NULL DEFAULT NULL COMMENT '上次登录时间',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sex` int(11) NOT NULL DEFAULT '0' COMMENT '性别',
  `uid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '工号',
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '所在单位',
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '部门',
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '职位',
  `card_address` text COLLATE utf8mb4_unicode_ci COMMENT '身份证住址',
  `address` text COLLATE utf8mb4_unicode_ci COMMENT '现住址',
  `license_date` date DEFAULT NULL COMMENT '驾驶证初次领证日期',
  `driving_age` int(11) NOT NULL DEFAULT '0' COMMENT '驾龄',
  `quasi_driving_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'C1' COMMENT '准驾车型',
  `nature` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用工性质',
  `birthday` date DEFAULT NULL COMMENT '出生年月日',
  `id_number` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证号码',
  `political_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '政治面貌',
  `cultural_level` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文化程度',
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '驾驶证编号',
  `hire_date` date DEFAULT NULL COMMENT '本单位上岗日期',
  `entry_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '自聘' COMMENT '入职方式',
  `hire_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '是否在职',
  `photos` text COLLATE utf8mb4_unicode_ci COMMENT '车辆照片',
  `front_photo` text COLLATE utf8mb4_unicode_ci COMMENT '驾驶证正本照片',
  `copy_photo` text COLLATE utf8mb4_unicode_ci COMMENT '驾驶证副本照片',
  `follower_id` int(11) NOT NULL DEFAULT '0' COMMENT '绑定微信ID',
  `company_id` int(11) NOT NULL DEFAULT '0' COMMENT '公司ID',
  `is_driver` int(11) NOT NULL DEFAULT '0' COMMENT '是否为专职司机 0:否 1:是',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 正在导出表  telecom_retain.users 的数据：~0 rows (大约)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
