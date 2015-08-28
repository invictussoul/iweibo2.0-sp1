<?php
return array(
	'首页' => array(
		array('url'=>'/admin/index/home','name'=>'首页','auth'=>''),
		),
	'全局' => array(
        array('url'=>'/admin/config/site','name'=>'站点设置','auth'=>''),
		array('url'=>'/admin/config/basic','name'=>'基础设置','auth'=>''),
		array('url'=>'/admin/certification/setup','name'=>'认证设置','auth'=>''),
		array('url'=>'/admin/config/blog','name'=>'微博功能','auth'=>''),
		array('url'=>'/admin/config/login','name'=>'登录授权','auth'=>''),
		array('url'=>'/admin/config/seo','name'=>'SEO设置','auth'=>''),
		array('url'=>'/admin/config/wap','name'=>'手机版设置','auth'=>''),
		array('url'=>'/admin/config/sec','name'=>'防灌水设置','auth'=>''),
		array('url'=>'/admin/config/search','name'=>'搜索管理','auth'=>''),
		array('url'=>'/admin/config/mail','name'=>'邮件设置','auth'=>''),
		),
	'用户' => array(
		array('url'=>'/admin/user/search','name'=>'用户管理','auth'=>''),
		array('url'=>'/admin/certification/search','name'=>'认证用户','auth'=>''),
		array('url'=>'/admin/banned/manage','name'=>'禁止IP','auth'=>''),
		array('url'=>'/admin/tag/manage','name'=>'标签管理','auth'=>''),
		array('url'=>'/admin/group/manage','name'=>'管理组','auth'=>''),
		array('url'=>'/admin/trust/search','name'=>'白名单','auth'=>''),
		),
	'微博' => array(
		array('url'=>'/admin/blog/index','name'=>'微博管理','auth'=>''),
		array('url'=>'/admin/blog/censor','name'=>'微博审核','auth'=>''),
		array('url'=>'/admin/filter/index','name'=>'词语过滤','auth'=>''),
		array('url'=>'/admin/report/index','name'=>'举报管理','auth'=>''),
		array('url'=>'/admin/topic/index','name'=>'话题管理','auth'=>''),
		),
	'运营' => array(
		array('url'=>'/admin/todayrecommend/index','name'=>'每日推荐','auth'=>''),
		array('url'=>'/admin/banner/index','name'=>'广告管理','auth'=>''),
		array('url'=>'/admin/brand/index','name'=>'推荐品牌','auth'=>''),
		array('url'=>'/admin/hottopic/index','name'=>'热门话题','auth'=>''),
		array('url'=>'/admin/recommend/index','name'=>'推荐用户','auth'=>''),
		array('url'=>'/admin/viprecommend/index','name'=>'名人推荐','auth'=>''),
		array('url'=>'/admin/ranking/index','name'=>'排行榜','auth'=>''),
		array('url'=>'/admin/event/index','name'=>'活动','auth'=>''),
		array('url'=>'/admin/wall/index','name'=>'上墙','auth'=>''),
		array('url'=>'/admin/tlive/index','name'=>'微直播','auth'=>''),
		array('url'=>'/admin/tiview/index','name'=>'微访谈','auth'=>''),
		),
	'界面' => array(
        array('url'=>'/admin/nav/index','name'=>'导航管理','auth'=>''),
		array('url'=>'/admin/skin/installed','name'=>'皮肤管理','auth'=>''),
		array('url'=>'/admin/componentmgt/index','name'=>'页面组件管理','auth'=>'')
		),
	'插件' => array(
		array('url'=>'/admin/plugin/installed','name'=>'插件管理','auth'=>''),
		),
	'工具' => array(
		array('url'=>'/admin/datatransfer/recommenduser','name'=>'数据调用','auth'=>''),
		array('url'=>'/admin/db/backup','name'=>'数据备份','auth'=>''),
		array('url'=>'/admin/db/restore','name'=>'数据恢复','auth'=>''),
		array('url'=>'/admin/stat/index','name'=>'数据统计','auth'=>''),
		array('url'=>'/admin/tool/updatecache','name'=>'更新缓存','auth'=>''),
		),
	'整合' => array(
		array('url'=>'/admin/uc/index','name'=>'UCenter整合','auth'=>''),
		)
);
