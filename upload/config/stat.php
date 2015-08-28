<?php
	return array(

		'user'	=> array(
				'login'	=> '登录(UV)',
				'wapvisit'	=> '手机访问(PV)',
				'reg'		=> '新注册用户'
			),

		'content'	=> array(
				'oripic'	=> '原创图片',//t_add   type=1 && p
				'orivod'	=> '原创视频',//t_add   type=1 && audio
				'orimp3'	=> '原创音乐',//t_add   type=1 && video
				'oripoll'	=> '原创投票',
				'oritxt'	=> '原创文本',//t_add   type=1 
				'forward'	=> '转播(含评论时)',//t_add   (评论同时转播)type=2
				'comment'	=> '评论',//t_add   type=4
				'dialog'	=> '对话',//t_add   type=3
				'pm'		=> '私信',//box_add
				'topic'	=> '话题',//topic_show
		),

		'interaction' => array(
				'addfans'	=> '收听次数',//friend_follow
				'addfav'	=> '收藏次数',//favor_t
				'taguse'	=> '标签使用次数',//tag_add
				'alt'		=> '@次数',//t_add 
		
		),

	);
