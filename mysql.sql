
DROP TABLE IF EXISTS `hongbao_user`;
CREATE TABLE `hongbao_user` (
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
INSERT INTO `hongbao_user` VALUES (1, 'admin', '管理员',  '21232f297a57a5a743894a0e4a801fc3', now(), '', '1');


DROP TABLE IF EXISTS `hongbao_money`;
CREATE TABLE `hongbao_money` (
  `money_id` int(11) NOT NULL auto_increment,
  `money_owner` varchar(50) NOT NULL,
  `money_number` int(11) unsigned NOT NULL default 0,
  `money_from` varchar(50) NOT NULL COMMENT '原则上关联user表的user_id字段，当是初始资金时保存0',
  `money_time` datetime NOT NULL,
  PRIMARY KEY (`money_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='资金表';


DROP TABLE IF EXISTS `hongbao_setting`;
CREATE TABLE `hongbao_setting` (
  `set_id` int(11) NOT NULL auto_increment,
  `set_beginmoney` int(11) unsigned NOT NULL default 10 COMMENT '初始资金',
  `set_getmoney` int(11) unsigned NOT NULL default 1000 COMMENT '满足多少元可以体现',
  `set_sharemoney` int(11) unsigned NOT NULL default 1 COMMENT '其它用户点击时我能分享到多少钱',
  PRIMARY KEY (`set_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='设置表';