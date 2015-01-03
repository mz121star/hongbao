
DROP TABLE IF EXISTS `weishi_user`;
CREATE TABLE `weishi_user` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(50) NOT NULL COMMENT '用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `user_pw` varchar(50) NOT NULL COMMENT '用户密码',
  `user_regdate` datetime NOT NULL COMMENT '用户注册日期',
  `user_image` varchar(500) NOT NULL COMMENT '用户头像',
  `user_status` enum('1','0') NOT NULL COMMENT '用户状态，1是启用，0是停用',
  PRIMARY KEY (`id`),
  UNIQUE KEY user_id (user_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表';
INSERT INTO `weishi_user` VALUES (1, 'admin', '管理员',  '21232f297a57a5a743894a0e4a801fc3', now(), '', '1');


DROP TABLE IF EXISTS `weishi_food`;
CREATE TABLE `weishi_food` (
  `food_id` int(11) NOT NULL auto_increment,
  `food_name` varchar(250) NOT NULL,
  `food_shicai` text NOT NULL,
  `food_fucai` text NOT NULL,
  `food_zhunbei` text NOT NULL,
  `food_buzhou` text NOT NULL,
  `food_qita` text NOT NULL,
  `food_adddate` datetime NOT NULL,
  `food_qishu` varchar(500) NOT NULL COMMENT '第几期',
  `food_image` varchar(500) NOT NULL,
  `food_favcount` int(11) unsigned NOT NULL default 0,
  `food_desc` text NOT NULL,
  `food_difficulty` int(11) unsigned NOT NULL default 1,
  PRIMARY KEY (`food_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='菜品表';


DROP TABLE IF EXISTS `weishi_fav`;
CREATE TABLE `weishi_fav` (
  `fav_id` int(11) NOT NULL auto_increment,
  `favfood_id` int(11) unsigned NOT NULL,
  `favuser_id` varchar(50) NOT NULL,
  `fav_date` datetime NOT NULL,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='菜品收藏表';


DROP TABLE IF EXISTS `weishi_comment`;
CREATE TABLE `weishi_comment` (
  `comment_id` int(11) NOT NULL auto_increment,
  `commentfood_id` int(11) unsigned NOT NULL,
  `commentuser_id` varchar(50) NOT NULL,
  `commentuser_name` varchar(50) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_date` datetime NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='菜品评论表';


DROP TABLE IF EXISTS `weishi_toupiao`;
CREATE TABLE `weishi_toupiao` (
  `tp_id` int(11) NOT NULL auto_increment,
  `tp_name` varchar(100) NOT NULL,
  `tp_desc` text NOT NULL,
  `tp_adddate` datetime NOT NULL,
  `tp_image` varchar(500) NOT NULL,
  PRIMARY KEY (`tp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='投票表';


DROP TABLE IF EXISTS `weishi_tpfood`;
CREATE TABLE `weishi_tpfood` (
  `tpfood_id` int(11) NOT NULL auto_increment,
  `tpfood_name` varchar(50) NOT NULL,
  `tpfood_tpid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`tpfood_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='投票菜表';


DROP TABLE IF EXISTS `weishi_tpuser`;
CREATE TABLE `weishi_tpuser` (
  `tpuser_id` int(11) NOT NULL auto_increment,
  `tpuser_food_id` int(11) unsigned NOT NULL,
  `tpuser_user_id` varchar(50) NOT NULL,
  `tpuser_date` datetime NOT NULL,
  `tpuser_tp_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`tpuser_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='投票用户表';


DROP TABLE IF EXISTS `weishi_jxhd`;
CREATE TABLE `weishi_jxhd` (
  `jxhd_id` int(11) NOT NULL auto_increment,
  `jxhd_desc` varchar(500) NOT NULL,
  `jxhd_content` text NOT NULL,
  `jxhd_image` varchar(100) NOT NULL,
  `jxhd_title` varchar(100) NOT NULL,
  `jxhd_date` datetime NOT NULL,
  PRIMARY KEY (`jxhd_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='精选活动表';


DROP TABLE IF EXISTS `weishi_jxsp`;
CREATE TABLE `weishi_jxsp` (
  `jxsp_id` int(11) NOT NULL auto_increment,
  `jxsp_title` varchar(100) NOT NULL,
  `jxsp_desc` varchar(500) NOT NULL,
  `jxsp_content` text NOT NULL,
  `jxsp_image` varchar(100) NOT NULL,
  `jxsp_zancount` int(11) unsigned NOT NULL default 0,
  `jxsp_comcount` int(11) unsigned NOT NULL default 0,
  `jxsp_date` datetime NOT NULL,
  PRIMARY KEY (`jxsp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='精选商品表';


DROP TABLE IF EXISTS `weishi_jxspzan`;
CREATE TABLE `weishi_jxspzan` (
  `jxspzan_id` int(11) NOT NULL auto_increment,
  `jxspzan_jxsp_id` varchar(100) NOT NULL,
  `jxspzan_user_id` varchar(100) NOT NULL,
  `jxspzan_date` datetime NOT NULL,
  PRIMARY KEY (`jxspzan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='精选商品赞表';


DROP TABLE IF EXISTS `weishi_jxspcom`;
CREATE TABLE `weishi_jxspcom` (
  `jxspcom_id` int(11) NOT NULL auto_increment,
  `jxspcom_jxsp_id` int(11) unsigned NOT NULL,
  `jxspcom_user_id` varchar(50) NOT NULL,
  `jxspcom_user_name` varchar(50) NOT NULL,
  `jxspcom_content` text NOT NULL,
  `jxspcom_date` datetime NOT NULL,
  PRIMARY KEY (`jxspcom_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='精选商品评论表';