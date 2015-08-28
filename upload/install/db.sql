
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `iweibo2_beta`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_base_config`
-- 

DROP TABLE IF EXISTS `iweibo2_base_config`;
CREATE TABLE `iweibo2_base_config` (
  `group` varchar(30) NOT NULL,
  `config` text NOT NULL,
  UNIQUE KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_base_nav`
-- 

DROP TABLE IF EXISTS `iweibo2_base_nav`;
CREATE TABLE `iweibo2_base_nav` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parentid` int(10) unsigned NOT NULL default '0' COMMENT '上级菜单',
  `name` varchar(255) NOT NULL COMMENT '导航名称',
  `action` char(50) NOT NULL COMMENT 'action名',
  `link` varchar(255) NOT NULL COMMENT '导航链接',
  `system` tinyint(1) NOT NULL default '0' COMMENT '是否内置导航',
  `type` tinyint(4) NOT NULL default '0' COMMENT '导航类别 0:顶部导航 1:头部导航',
  `displayorder` tinyint(3) NOT NULL COMMENT '显示顺序',
  `newwindow` tinyint(4) NOT NULL COMMENT '是否新窗口打开',
  `useable` tinyint(1) NOT NULL default '0' COMMENT '是否启用',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_base_session`
-- 

DROP TABLE IF EXISTS `iweibo2_base_session`;
CREATE TABLE `iweibo2_base_session` (
  `skey` char(32) character set utf8 collate utf8_bin NOT NULL,
  `uid` int(10) unsigned NOT NULL default '0',
  `lastupdate` int(10) unsigned NOT NULL default '0',
  `ip` int(10) unsigned NOT NULL default '0',
  `expire` int(10) unsigned NOT NULL default '0',
  `sdata` text NOT NULL,
  PRIMARY KEY  (`skey`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_base_token`
-- 

DROP TABLE IF EXISTS `iweibo2_base_token`;
CREATE TABLE `iweibo2_base_token` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL,
  `sign` char(64) NOT NULL COMMENT '标识',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tid`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_component_banner`
-- 

DROP TABLE IF EXISTS `iweibo2_component_banner`;
CREATE TABLE `iweibo2_component_banner` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL COMMENT '名字',
  `picture` char(120) NOT NULL COMMENT '图片链接',
  `description` char(255) NOT NULL COMMENT '描述',
  `start_time` int(10) unsigned NOT NULL COMMENT '有效开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '有效结束时间',
  `url` char(255) NOT NULL COMMENT '广告链接. ',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_component_brand`
-- 

DROP TABLE IF EXISTS `iweibo2_component_brand`;
CREATE TABLE `iweibo2_component_brand` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL,
  `description` char(255) NOT NULL,
  `picture` char(120) NOT NULL,
  `link` char(120) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_component_hottopic`
-- 

DROP TABLE IF EXISTS `iweibo2_component_hottopic`;
CREATE TABLE `iweibo2_component_hottopic` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL COMMENT '热点话题名字',
  `description` char(255) NOT NULL COMMENT '热点话题描述',
  `picture` char(120) NOT NULL COMMENT '热点话题图片链接(50像素图片最合适)',
  `picture2` char(120) NOT NULL COMMENT '热点话题图片链接(120像素图片最合适)',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_component_management`
-- 

DROP TABLE IF EXISTS `iweibo2_component_management`;
CREATE TABLE `iweibo2_component_management` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sitepage` tinyint(1) unsigned NOT NULL COMMENT '配置的页面',
  `column` char(20) default NULL COMMENT '设置区域, 1是主栏, 2是右栏',
  `component_type` char(30) NOT NULL COMMENT '组件名字',
  `component_status` enum('1','0') NOT NULL default '0' COMMENT '组件是否启用. ',
  `component_sequence` tinyint(2) unsigned NOT NULL COMMENT '组件的显示顺序',
  `component_title` char(30) NOT NULL COMMENT '组件的标题',
  `component_number` tinyint(2) unsigned NOT NULL COMMENT '组件显示的条数. ',
  `component_style` tinyint(1) unsigned NOT NULL COMMENT '组件显示的样式.',
  PRIMARY KEY  USING BTREE (`id`,`component_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_component_recommend`
-- 

DROP TABLE IF EXISTS `iweibo2_component_recommend`;
CREATE TABLE `iweibo2_component_recommend` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `account` char(20) NOT NULL COMMENT '推荐账号',
  `description` char(255) NOT NULL default '' COMMENT '推荐用户描述',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_blog`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_blog`;
CREATE TABLE `iweibo2_mb_blog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` tinyint(4) NOT NULL default '0' COMMENT '类型：1发表 2 转播 3 回复 4 点评',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `opentid` varchar(20) NOT NULL COMMENT '平台微博ID',
  `account` varchar(60) NOT NULL COMMENT '平台帐号',
  `username` varchar(32) NOT NULL COMMENT '本地用户名',
  `nickname` varchar(32) NOT NULL COMMENT '本地昵称',
  `isauth` tinyint(3) unsigned NOT NULL default '0' COMMENT '是否认证用户',
  `origintid` int(10) unsigned NOT NULL COMMENT '内部原创ID',
  `originopentid` varchar(20) NOT NULL COMMENT '平台原创ID',
  `txt` text NOT NULL COMMENT '未格式化的源消息',
  `content` text NOT NULL COMMENT '微博内容',
  `comefrom` varchar(20) NOT NULL default '0' COMMENT '平台来源ID',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '发表时间',
  `ip` varchar(20) NOT NULL,
  `picture` varchar(60) NOT NULL COMMENT '图片地址',
  `audio` varchar(60) NOT NULL COMMENT '音乐',
  `video` varchar(60) NOT NULL COMMENT '视频',
  `state` tinyint(4) NOT NULL default '0' COMMENT '状态 0:默认 1:审核通过 -1:屏蔽',
  `visible` tinyint(4) NOT NULL default '0' COMMENT '显示状态 0:不显示 1:显示',
  `censortime` int(10) unsigned NOT NULL COMMENT '消息审核通过时间',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `openid` (`opentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_comment`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_comment`;
CREATE TABLE `iweibo2_mb_comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `originid` int(10) unsigned NOT NULL COMMENT '本地原创id',
  `originopentid` varchar(20) NOT NULL COMMENT '平台原创id',
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `account` varchar(60) NOT NULL,
  `comefrom` int(10) unsigned NOT NULL COMMENT '来源ID',
  `dateline` int(10) unsigned NOT NULL COMMENT '发表时间',
  `content` text NOT NULL COMMENT '发表内容',
  `ip` varchar(20) NOT NULL COMMENT 'Ip地址',
  `state` tinyint(4) NOT NULL default '0' COMMENT '状态',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_filter`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_filter`;
CREATE TABLE `iweibo2_mb_filter` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `word` char(255) NOT NULL default '' COMMENT '关键词',
  `replacement` char(255) NOT NULL default '' COMMENT '替换',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_mask`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_mask`;
CREATE TABLE `iweibo2_mb_mask` (
  `opentid` char(20) NOT NULL COMMENT '平台微博ID',
  `time` int(10) unsigned NOT NULL default '0' COMMENT '屏蔽时间',
  PRIMARY KEY  (`opentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_notice`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_notice`;
CREATE TABLE `iweibo2_mb_notice` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mbid` int(10) unsigned NOT NULL default '0' COMMENT '对应的微博ID',
  `title` char(200) NOT NULL default '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `displayorder` tinyint(3) NOT NULL default '0' COMMENT '显示顺序',
  `endtime` int(10) unsigned NOT NULL default '0' COMMENT '结束时间',
  PRIMARY KEY  (`id`),
  KEY `timespan` (`endtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_plugin`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_plugin`;
CREATE TABLE `iweibo2_mb_plugin` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '主键',
  `name` char(60) NOT NULL default '' COMMENT '插件名称',
  `foldername` char(60) NOT NULL default '' COMMENT '插件目录',
  `useable` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否启用',
  `visible` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否显示导航',
  `usehack` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否允许调用钩子',
  `orderkey` int(10) unsigned NOT NULL default '0' COMMENT '排序值',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_report`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_report`;
CREATE TABLE `iweibo2_mb_report` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL COMMENT '举报人UID(本地)',
  `name` char(20) NOT NULL COMMENT '举报人平台用户名',
  `username` char(20) NOT NULL COMMENT '举报人用户名(本地)',
  `targetaccount` char(20) NOT NULL COMMENT '举报对象(平台用户名)',
  `targetopentid` char(20) NOT NULL COMMENT '平台微博ID',
  `reason` char(255) NOT NULL COMMENT '举报原因',
  `blogcontent` text NOT NULL COMMENT '微博内容',
  `time` int(10) unsigned NOT NULL default '0' COMMENT '举报时间',
  `type` tinyint(4) unsigned NOT NULL default '0' COMMENT ' 0 色情',
  `state` tinyint(4) unsigned NOT NULL default '0' COMMENT '状态 0:未处理 1:已处理',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `openid` (`targetopentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_skin`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_skin`;
CREATE TABLE `iweibo2_mb_skin` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '主键',
  `name` char(60) NOT NULL default '' COMMENT '模板名称',
  `foldername` char(60) NOT NULL default '' COMMENT '文件夹名',
  `thumb` char(60) NOT NULL default '' COMMENT '缩略图',
  `orderkey` int(10) unsigned NOT NULL default '0' COMMENT '排序值',
  `useable` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否可用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_stat`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_stat`;
CREATE TABLE `iweibo2_mb_stat` (
  `Id` int(11) NOT NULL auto_increment,
  `skey` char(20) default NULL,
  `sname` char(20) NOT NULL default '0',
  `dateline` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `statuniqkey` (`skey`,`dateline`),
  KEY `sname` (`sname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_tag`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_tag`;
CREATE TABLE `iweibo2_mb_tag` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '主键',
  `tagname` char(60) NOT NULL default '' COMMENT '标签名称',
  `color` char(10) NOT NULL COMMENT '标签颜色',
  `usenum` int(10) unsigned NOT NULL default '1' COMMENT '使用用户数',
  `visible` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否开放',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tagname` (`tagname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_today_recommend`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_today_recommend`;
CREATE TABLE `iweibo2_mb_today_recommend` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '每日推荐',
  `content` text NOT NULL COMMENT '内容',
  `displayorder` tinyint(3) unsigned NOT NULL default '0' COMMENT '显示顺序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_topic`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_topic`;
CREATE TABLE `iweibo2_mb_topic` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `title` char(255) NOT NULL COMMENT '话题',
  `mblogs` mediumint(8) unsigned NOT NULL default '0' COMMENT '微博条数',
  `wall` tinyint(1) NOT NULL default '0' COMMENT '是否上墙话题',
  `state` tinyint(4) NOT NULL default '0' COMMENT '状态 1:锁定 0:正常',
  `wallcensor` tinyint(4) NOT NULL COMMENT '上墙消息是否需要审核',
  `wallstarttime` int(10) NOT NULL default '0' COMMENT '上墙开始时间',
  `wallendtime` int(10) NOT NULL COMMENT '上墙结束时间',
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_mb_topicblog`
-- 

DROP TABLE IF EXISTS `iweibo2_mb_topicblog`;
CREATE TABLE `iweibo2_mb_topicblog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `opentid` varchar(20) NOT NULL COMMENT '平台微博ID',
  `topicid` varchar(60) NOT NULL COMMENT '话题Id',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '发表时间',
  `visible` tinyint(4) NOT NULL default '0' COMMENT '显示状态 0:不显示 1:显示',
  `censortime` int(10) unsigned NOT NULL COMMENT '审核通过',
  PRIMARY KEY  (`id`,`opentid`,`topicid`),
  KEY `topicid` (`topicid`),
  KEY `opentid` (`opentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_banned`
-- 

DROP TABLE IF EXISTS `iweibo2_user_banned`;
CREATE TABLE `iweibo2_user_banned` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ip` char(15) NOT NULL COMMENT 'IP',
  `username` char(32) NOT NULL COMMENT '创建者',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_event`
-- 

DROP TABLE IF EXISTS `iweibo2_user_event`;
CREATE TABLE `iweibo2_user_event` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default '0',
  `uname` varchar(30) NOT NULL default '',
  `realname` varchar(30) NOT NULL default '',
  `phone` varchar(200) NOT NULL default '',
  `pic` varchar(200) NOT NULL default '',
  `addr` varchar(255) NOT NULL default '',
  `cost` float(10,2) unsigned NOT NULL default '0.00' COMMENT '费用',
  `title` varchar(255) NOT NULL default '',
  `deadline` int(10) unsigned NOT NULL default '0' COMMENT '截止时间',
  `sdate` int(10) unsigned NOT NULL default '0' COMMENT '开始时间',
  `edate` int(10) unsigned NOT NULL default '0' COMMENT '结束时间',
  `joins` int(10) unsigned NOT NULL default '0' COMMENT '加入人数',
  `views` int(10) unsigned NOT NULL default '0' COMMENT '查看数',
  `status` tinyint(1) unsigned NOT NULL default '1' COMMENT '1正常 2用户关闭 3管理禁用 4 推荐',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '发布时间',
  `message` text NOT NULL COMMENT '活动介绍',
  `contact` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否需要联系方式',
  `modtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid_index` (`uid`),
  KEY `title_index` (`title`),
  KEY `date_index` (`sdate`,`edate`),
  KEY `v_index` (`views`),
  KEY `d_index` (`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_event_join`
-- 

DROP TABLE IF EXISTS `iweibo2_user_event_join`;
CREATE TABLE `iweibo2_user_event_join` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) unsigned NOT NULL default '0',
  `uname` varchar(255) NOT NULL default '',
  `eid` int(11) unsigned NOT NULL default '0',
  `contact` varchar(255) NOT NULL default '',
  `message` tinytext,
  `dateline` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `s_index` (`uid`,`eid`),
  KEY `d_index` (`dateline`),
  KEY `unameindex` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_follow`
-- 

DROP TABLE IF EXISTS `iweibo2_user_follow`;
CREATE TABLE `iweibo2_user_follow` (
  `followeename` char(20) NOT NULL COMMENT '收听人的平台帐号',
  `followername` char(20) NOT NULL COMMENT '被收听人的平台帐号',
  `direction` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否双向收听',
  `time` int(10) unsigned NOT NULL default '0' COMMENT '收听时间',
  PRIMARY KEY  (`followeename`,`followername`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_gdsession`
-- 

DROP TABLE IF EXISTS `iweibo2_user_gdsession`;
CREATE TABLE `iweibo2_user_gdsession` (
  `id` varchar(8) NOT NULL,
  `number` varchar(12) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_group`
-- 

DROP TABLE IF EXISTS `iweibo2_user_group`;
CREATE TABLE `iweibo2_user_group` (
  `gid` int(10) unsigned NOT NULL auto_increment COMMENT '组编号',
  `type` tinyint(1) unsigned NOT NULL default '1' COMMENT '组类型',
  `title` char(32) NOT NULL default '' COMMENT '组名称',
  PRIMARY KEY  (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_member`
-- 

DROP TABLE IF EXISTS `iweibo2_user_member`;
CREATE TABLE `iweibo2_user_member` (
  `uid` int(10) unsigned NOT NULL auto_increment COMMENT '编号',
  `gid` int(10) unsigned NOT NULL default '2' COMMENT '组编号',
  `username` char(32) NOT NULL default '' COMMENT '用户名',
  `nickname` char(32) NOT NULL default '' COMMENT '昵称',
  `realname` char(32) NOT NULL default '' COMMENT '真实姓名',
  `password` char(32) NOT NULL default '' COMMENT '密码',
  `salt` char(6) NOT NULL default '',
  `secques` char(8) NOT NULL default '' COMMENT '安全提问',
  `email` char(75) NOT NULL COMMENT '邮箱',
  `mobile` char(16) NOT NULL default '' COMMENT '手机',
  `gender` tinyint(1) unsigned NOT NULL default '0' COMMENT '性别',
  `birthyear` smallint(4) unsigned NOT NULL default '0' COMMENT '出生年',
  `birthmonth` tinyint(2) unsigned NOT NULL default '0' COMMENT '出生月',
  `birthday` tinyint(2) unsigned NOT NULL default '0' COMMENT '出生日',
  `privbirth` tinyint(2) unsigned NOT NULL default '0' COMMENT '生日显示方式',
  `style` char(32) NOT NULL default '' COMMENT '使用的皮肤',
  `homenation` char(8) NOT NULL default '' COMMENT '家乡国',
  `homeprovince` char(8) NOT NULL default '' COMMENT '家乡省',
  `homecity` char(8) NOT NULL default '' COMMENT '家乡市',
  `nation` char(8) NOT NULL default '' COMMENT '国家',
  `province` char(8) NOT NULL default '' COMMENT '省份',
  `city` char(8) NOT NULL default '' COMMENT '城市',
  `occupation` smallint(4) NOT NULL default '0' COMMENT '从事行业',
  `homepage` char(75) NOT NULL COMMENT '个人主页',
  `summary` char(140) NOT NULL COMMENT '个人介绍',
  `localauth` tinyint(1) unsigned NOT NULL default '0' COMMENT '本地认证',
  `localauthtext` char(140) NOT NULL COMMENT '本地认证文字说明',
  `regtime` int(10) unsigned NOT NULL default '0' COMMENT '注册时间',
  `regip` char(15) NOT NULL default '' COMMENT '注册IP',
  `lastvisit` int(10) unsigned NOT NULL default '0' COMMENT '最后访问时间',
  `lastip` char(15) NOT NULL default '' COMMENT '最后访问IP',
  `newfollowers` int(10) unsigned NOT NULL COMMENT '新增听众数',
  `oauthtoken` char(32) NOT NULL default '' COMMENT 'access_token',
  `oauthtokensecret` char(32) NOT NULL default '' COMMENT 'access_secret',
  `name` char(32) NOT NULL default '' COMMENT '微博帐户名',
  `fansnum` int(10) unsigned NOT NULL default '0' COMMENT '粉丝数（本地）',
  `idolnum` int(10) unsigned NOT NULL default '0' COMMENT '偶像数（本地）',
  `trust` tinyint(4) unsigned NOT NULL default '0' COMMENT '是否信任用户',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_tag`
-- 

DROP TABLE IF EXISTS `iweibo2_user_tag`;
CREATE TABLE `iweibo2_user_tag` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '主键',
  `name` char(32) NOT NULL COMMENT '微博id',
  `tagname` char(30) NOT NULL COMMENT 'tag名称',
  `useful` tinyint(1) NOT NULL default '0' COMMENT '0有效 1屏蔽',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_2` (`name`,`tagname`),
  KEY `name` (`name`,`tagname`,`useful`),
  KEY `tagname` (`tagname`,`useful`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_tiview`
-- 

DROP TABLE IF EXISTS `iweibo2_user_tiview`;
CREATE TABLE `iweibo2_user_tiview` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `tname` varchar(255) NOT NULL default '' COMMENT '直播名称',
  `notice` smallint(3) NOT NULL default '0' COMMENT '提醒时间',
  `style` text,
  `host` varchar(150) NOT NULL default '' COMMENT '主持人',
  `desc` text NOT NULL,
  `direct` tinyint(1) unsigned NOT NULL default '1' COMMENT '0 为须审核 1为直接发送',
  `sdate` int(11) unsigned NOT NULL default '0' COMMENT '开始时间',
  `edate` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `dateindex` (`sdate`,`edate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_tiview_join`
-- 

DROP TABLE IF EXISTS `iweibo2_user_tiview_join`;
CREATE TABLE `iweibo2_user_tiview_join` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tid` int(10) unsigned NOT NULL default '0' COMMENT '直播ID',
  `uname` char(100) NOT NULL default '' COMMENT '帐号',
  `utype` tinyint(1) unsigned NOT NULL default '0' COMMENT '0主持人1嘉宾',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `joins` (`tid`,`uname`,`utype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_tiview_post`
-- 

DROP TABLE IF EXISTS `iweibo2_user_tiview_post`;
CREATE TABLE `iweibo2_user_tiview_post` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uname` char(150) NOT NULL default '',
  `tid` int(10) unsigned NOT NULL default '0' COMMENT '直播ID',
  `pid` bigint(25) unsigned NOT NULL default '0' COMMENT '所属问题ID 0 表示提问 其他为回答',
  `msgid` bigint(25) unsigned NOT NULL default '0' COMMENT '消息ID',
  `dateline` int(11) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0' COMMENT '0待审核1正常 2 屏蔽',
  `tuname` char(30) NOT NULL default '' COMMENT '所问嘉宾',
  `reply` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否被回答了',
  PRIMARY KEY  (`id`),
  KEY `sindex` (`tid`),
  KEY `si` (`status`,`dateline`),
  KEY `uti` (`tid`,`uname`),
  KEY `replyindex` (`reply`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_tlive`
-- 

DROP TABLE IF EXISTS `iweibo2_user_tlive`;
CREATE TABLE `iweibo2_user_tlive` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `tname` varchar(255) NOT NULL default '' COMMENT '直播名称',
  `title` varchar(255) NOT NULL default '',
  `notice` smallint(3) NOT NULL default '0' COMMENT '提醒时间',
  `style` text,
  `host` varchar(150) NOT NULL default '' COMMENT '主持人',
  `desc` text NOT NULL,
  `direct` tinyint(1) unsigned NOT NULL default '1' COMMENT '0 为须审核 1为直接发送',
  `sdate` int(11) unsigned NOT NULL default '0' COMMENT '开始时间',
  `edate` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tindex` (`title`),
  KEY `dateindex` (`sdate`,`edate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_tlive_join`
-- 

DROP TABLE IF EXISTS `iweibo2_user_tlive_join`;
CREATE TABLE `iweibo2_user_tlive_join` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tid` int(10) unsigned NOT NULL default '0' COMMENT '直播ID',
  `uname` char(100) NOT NULL default '' COMMENT '帐号',
  `utype` tinyint(1) unsigned NOT NULL default '0' COMMENT '0主持人1嘉宾',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `joins` (`tid`,`uname`,`utype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `iweibo2_user_tlive_post`
-- 

DROP TABLE IF EXISTS `iweibo2_user_tlive_post`;
CREATE TABLE `iweibo2_user_tlive_post` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uname` char(150) NOT NULL default '',
  `tid` int(10) unsigned NOT NULL default '0' COMMENT '直播ID',
  `msgid` bigint(25) unsigned NOT NULL default '0' COMMENT '消息ID',
  `dateline` int(11) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0' COMMENT '0待审核1正常 2 屏蔽',
  PRIMARY KEY  (`id`),
  KEY `sindex` (`tid`),
  KEY `si` (`status`,`dateline`),
  KEY `uti` (`tid`,`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
