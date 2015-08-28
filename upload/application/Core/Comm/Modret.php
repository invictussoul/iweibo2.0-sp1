<?php
/**
 * 模块控制器公共应答码
 * @param 
 * @return
 * @author luckyxiang, echoyang
 * @package 原iweibo1 /application/controller/BaseModRetCode
 * @time 2011/5/12
 */

class Core_Comm_Modret
{
    /**
     * 统一抛出错误方法
     *
     * 业务逻辑错误 code < 0
     * 系统内部错误 code > 0
	 */
	const RET_SUCC = 0;
	const RET_DEFAULT_ERR = -1;
	const RET_MISS_ARG = -100;
	const RET_ARG_ERR = -101;
	const RET_OAUTH_ERR = -102;
	const RET_API_ERR = -103;
	const RET_API_FREQ_DENY = -104;
	const RET_API_OAUTH_ERR = -105;
	const RET_API_ARG_ERR = -106;
	const RET_API_INNER_ERR = -107;
	const RET_DATA_NA = -108;
	const RET_T_MISS = -109;
	const RET_T_ILLEGAL = -110;
	const RET_PIC_SIZE = -111;
	const RET_PIC_TYPE = -112;
	const RET_T_UNVISIBLE = -113;
	const RET_T_CENSOR = -114;
	const RET_T_FILTER_WAIT = -115;
	const RET_T_FILTER_REFUSE = -116;
	const RET_U_REG_ERR = -117;
	const RET_U_CHK_ERR = -118;
	const RET_TAG_ADDED = -119;
	const RET_U_UNLOGIN = -120;
	const RET_TAG_FULL = -121;
	const RET_TAG_UNFILTER = -122;
	const RET_TAG_LOCKED = -123;
	const RET_DB_EXCEPTION = -124;
	const RET_DATA_EXCEPTION = -125;
	const RET_T_TOPIC_ERR = -126;
	const RET_T_TOPIC_REFUSE = -127;
	const RET_REPORT_LOSS = -128;
	const RET_REPORT_OK = -129;
	const RET_REPORT_ERR = -130;
	const RET_SKIN_ERR = -131;
	const RET_U_ERR = -132;
	const RET_U_FOLLOW_ERR = -133;
	const RET_U_FOLLOW_UNLOCAL = -133;
	const RET_TOPIC_ERR = -134;
	const RET_T_UPLOAD_UN_HTTP = -135;
	const RET_T_UPLOAD_ERR_INI_SIZE = -136;
	const RET_T_UPLOAD_ERR_FORM_SIZE = -137;
	const RET_T_UPLOAD_ERR_PARTIAL = -138;
	const RET_T_UPLOAD_ERR_NO_FILE = -139;
	const RET_T_UPLOAD_ERR_NO_TMP_DIR = -140;
	const RET_T_UPLOAD_ERR_CANT_WRITE = -141;
	const RET_T_UPLOAD_ERR_EXTENSION = -142;
	
	const RET_USER_REGISTERSUCCEED = -151;	//用户注册成功
	const RET_USER_REGISTERFAILED = -152;	//用户注册失败
	const RET_USER_UNBOUND = -153;			//用户未绑定
	const RET_USER_BOUND = -154;			//用户已绑定
	const RET_USER_LOGINSUCCEED = -155;		//用户登录成功
	const RET_USER_LOGINFAILED = -156;		//用户登录失败
	const RET_USER_LOGOUTSUCCEED = -157;	//用户登出成功
	const RET_USER_LOGOUTFAILED = -158;		//用户登出失败
	const RET_USER_LOGGED = -159;			//用户已登录
	const RET_USER_DOESNOTEXIST = -160;		//用户不存在
	const RET_USER_DELETED = -161;			//用户被删除
	const RET_USER_BLOCKED = -162;			//用户被屏蔽
	const RET_USER_DOESNOTMATCH = -163;		//用户不匹配，请选择其他找回密码的方式
	
	const RET_ACCOUNT_UNBOUND = -171;		//帐号未绑定，请选择其他找回密码的方式
	const RET_ACCOUNT_BOUND = -172;			//帐号已绑定
	const RET_ACCOUNT_INVALID = -173;		//TOKEN已失效，请重新绑定
	
    const RET_USERNAME_NOTNULL = -176;		//帐号不能为空
    const RET_USERNAME_FORMATERROR = -177;	//帐号格式不正确
    const RET_USERNAME_USED = -178;			//帐号已被使用
    
    const RET_PASSWORD_NOTNULL = -181;		//密码不能为空
    const RET_PASSWORD_FORMATERROR = -182;	//密码格式不正确
    const RET_PASSWORD_DOESNOTMATCH = -183;	//两次输入的密码不匹配
    const RET_PASSWORD_OLDPASSWORDDOESNOTMATCH = -184;	//旧密码不正确
    const RET_PASSWORD_CHANGESUCCEED = -185;//密码修改成功
    const RET_PASSWORD_CHANGEFAILED = -186;	//密码修改失败
    const RET_PASSWORD_RESETSUCCEED = -187;	//密码重置成功
    const RET_PASSWORD_RESETFAILED = -188;	//密码重置失败
    
    const RET_EMAIL_NOTNULL = -191;			//邮箱不能为空
    const RET_EMAIL_FORMATERROR = -192;		//邮箱格式不正确
    const RET_EMAIL_DOESNOTMATCH = -193;	//你填写的帐号和邮箱不匹配
    const RET_EMAIL_SENDSUCCEED = -194;		//取回密码的方法已经通过Email发送到你的邮箱中
    const RET_EMAIL_SENDFAILED = -195;		//邮箱发送失败
    const RET_EMAIL_USED = -196;			//邮箱已被使用
    
    const RET_CODE_CHECKFAILED = -201;		//附加码验证失败
    
    const RET_TOKEN_GENERATEFAILED = -206;	//令牌生成失败
    const RET_TOKEN_EXPIRED = -207;			//令牌已失效
	
    const RET_IP_FORMATERROR = -211;		//IP格式不正确
    const RET_IP_BANNED = -212;				//IP已被禁止
    const RET_IP_CANNOTBANNEDSELF = -213;	//不能禁止自己
	
    const RET_FACE_UPLOADSUCCEED = -216;	//头像上传成功
    const RET_FACE_UPLOADFAILED = -217;		//头像上传失败
    
    const RET_SITE_CLOSED = -221;			//站点关闭
    
    const RET_USERINFO_UPDATESUCCEED = -231;		//用户信息更新成功
    const RET_USERINFO_UPDATEFAILED = -232;			//用户信息更新失败
    const RET_USERINFO_NICKNAMEFORMATERROR = -233;	//姓名格式不正确
    const RET_USERINFO_GENDERFORMATERROR = -234;	//性别不正确
    const RET_USERINFO_BIRTHYEARFORMATERROR = -235;	//出生年不正确
    const RET_USERINFO_BIRTHMONTHFORMATERROR = -236;//出生月不正确
    const RET_USERINFO_BIRTHDAYFORMATERROR = -237;	//出生日不正确
    const RET_USERINFO_PRIVBIRTHFORMATERROR = -238;	//生日显示方式不正确
    const RET_USERINFO_HOMENATIONFORMATERROR = -239;//家乡国家格式不正确
    const RET_USERINFO_HOMEPROVINCEFORMATERROR = -240;	//家乡省份格式不正确
    const RET_USERINFO_HOMECITYFORMATERROR = -241;	//家乡城市不正确
    const RET_USERINFO_NATIONFORMATERROR = -242;	//所在地国家不正确
    const RET_USERINFO_PROVINCEFORMATERROR = -243;	//所在地省份不正确
    const RET_USERINFO_CITYFORMATERROR = -244;		//所在地城市不正确
    const RET_USERINFO_OCCUPATIONFORMATERROR = -245;//从事行业不正确
    const RET_USERINFO_HOMEPAGEFORMATERROR = -246;	//个人主页格式不正确
    const RET_USERINFO_SUMMARYFORMATERROR = -247;	//个人介绍不正确
    
    const RET_ADMIN_FRONTLOGIN = -251;		//请先前台登录
	const RET_ADMIN_FRONTBOUND = -252;		//请先绑定腾讯帐号
    const RET_ADMIN_NOPERMISSION = -253;	//你的帐号无后台访问权限
    const RET_ADMIN_DOESNOTMATCH = -254;	//管理员与前台登录用户不匹配
    
    const RET_UC_USERPROTECTED = -261;		//该用户受保护无权限更改
    
	//msg:h_开头的表示异步调用时不展示给用户
	static public $ARR_MSG 
		= array(  self::RET_SUCC => "成功"
				, self::RET_DEFAULT_ERR => "h_系统内部错误"
				, self::RET_MISS_ARG => "缺少参数"
				, self::RET_ARG_ERR => "参数错误"
				, self::RET_OAUTH_ERR => "h_系统鉴权错误"
				, self::RET_API_ERR => "接口调用失败"
				, self::RET_API_FREQ_DENY => "操作过于频繁，请稍后再试"
				, self::RET_API_OAUTH_ERR => "授权错误"
				, self::RET_API_ARG_ERR => "操作失败"
				, self::RET_API_INNER_ERR => "内部错误"
				, self::RET_DATA_NA => "对不起，您的页面暂时无法找到！"
				, self::RET_T_MISS => "请输入内容"
				, self::RET_T_ILLEGAL => "发表消息包含敏感内容，请重新输入"
				, self::RET_PIC_SIZE => "最大支持2M图片"
				, self::RET_PIC_TYPE => "图片仅支持jpg/jpeg/gif/png类型"
				, self::RET_T_UNVISIBLE => "词条微博已屏蔽"
				, self::RET_T_CENSOR => "对不起！您发表的内容需要审核，请等待管理员审核"
				, self::RET_T_FILTER_WAIT => "对不起！您发表的内容包含需要审核的关键词，请等待管理员审核"
				, self::RET_T_FILTER_REFUSE => "对不起！您发表的内容包含敏感词，请重新输入"
				, self::RET_U_REG_ERR => "验证信息不通过，请重新输入"
				, self::RET_U_CHK_ERR => "用户已经存在，请重新输入"
				, self::RET_TAG_ADDED => "您已经添加该标签"
				, self::RET_U_UNLOGIN => "您还未登录"
				, self::RET_TAG_FULL => "最多添加10个标签"
				, self::RET_TAG_UNFILTER => "标签包含敏感词"
				, self::RET_TAG_LOCKED => "标签已锁定"
				, self::RET_DB_EXCEPTION => "数据库异常"
				, self::RET_DATA_EXCEPTION => "数据返回异常"
				, self::RET_T_TOPIC_ERR => "消息不能多于三条话题"
				, self::RET_T_TOPIC_REFUSE => "消息含有锁定话题"            
				, self::RET_REPORT_LOSS => "举报数据丢失,失败"
				, self::RET_REPORT_OK => "举报成功"
				, self::RET_REPORT_ERR => "举报数据错误"
				, self::RET_SKIN_ERR => "皮肤错误"
				, self::RET_U_ERR => "用户账户不能为空"
				, self::RET_U_FOLLOW_ERR => "不能对自己进行收听操作"
				, self::RET_U_FOLLOW_UNLOCAL => "不是本地用户无法收听"
				, self::RET_TOPIC_ERR => "此话题请求错误"
				, self::RET_T_UPLOAD_UN_HTTP => "文件非正常上传"
				, self::RET_T_UPLOAD_ERR_INI_SIZE => "上传文件的大小超过系统限制"
				, self::RET_T_UPLOAD_ERR_FORM_SIZE => "上传文件的大小超过表单限制"
				, self::RET_T_UPLOAD_ERR_PARTIAL => "上传错误,文件只有部分被上传"
				, self::RET_T_UPLOAD_ERR_NO_FILE => "没有文件被上传"
				, self::RET_T_UPLOAD_ERR_NO_TMP_DIR => "服务器错误,找不到临时文件夹"
				, self::RET_T_UPLOAD_ERR_CANT_WRITE => "服务器错误,文件写入失败"
				, self::RET_T_UPLOAD_ERR_EXTENSION => "上传异常"
				
				, self::RET_USER_REGISTERSUCCEED => "用户注册成功"
				, self::RET_USER_REGISTERFAILED => "用户注册失败"
				, self::RET_USER_UNBOUND => "用户未绑定"
				, self::RET_USER_BOUND => "用户已绑定"
				, self::RET_USER_LOGINSUCCEED => "用户登录成功"
				, self::RET_USER_LOGINFAILED => "用户登录失败"
				, self::RET_USER_LOGOUTSUCCEED => "用户登出成功"
				, self::RET_USER_LOGOUTFAILED => "用户登出失败"
				, self::RET_USER_LOGGED => "用户已登录"
				, self::RET_USER_DOESNOTEXIST => "用户不存在"
				, self::RET_USER_DELETED => "用户被删除"
				, self::RET_USER_BLOCKED => "用户被屏蔽"
				, self::RET_USER_DOESNOTMATCH => "用户不匹配，请选择其他找回密码的方式"
				
				, self::RET_ACCOUNT_UNBOUND => "帐号未绑定，请选择其他找回密码的方式"
				, self::RET_ACCOUNT_BOUND => "帐号已绑定"
				, self::RET_ACCOUNT_INVALID => "TOKEN已失效，请重新绑定"
				
			    , self::RET_USERNAME_NOTNULL => "帐号不能为空"
			    , self::RET_USERNAME_FORMATERROR => "帐号格式不正确"
			    , self::RET_USERNAME_USED => "帐号已被使用"
			    
			    , self::RET_PASSWORD_NOTNULL => "密码不能为空"
			    , self::RET_PASSWORD_FORMATERROR => "密码格式不正确"
			    , self::RET_PASSWORD_DOESNOTMATCH => "两次输入的密码不匹配"
			    , self::RET_PASSWORD_OLDPASSWORDDOESNOTMATCH => "旧密码不正确"
			    , self::RET_PASSWORD_CHANGESUCCEED => "密码修改成功"
			    , self::RET_PASSWORD_CHANGEFAILED => "密码修改失败"
			    , self::RET_PASSWORD_RESETSUCCEED => "密码重置成功"
    			, self::RET_PASSWORD_RESETFAILED => "密码重置失败"
    			
			    , self::RET_EMAIL_NOTNULL => "邮箱不能为空"
			    , self::RET_EMAIL_FORMATERROR => "邮箱格式不正确"
			    , self::RET_EMAIL_DOESNOTMATCH => "你填写的帐号和邮箱不匹配"
			    , self::RET_EMAIL_SENDSUCCEED => "取回密码的方法已经通过Email发送到你的邮箱中"
			    , self::RET_EMAIL_SENDFAILED => "邮箱发送失败"
			    , self::RET_EMAIL_USED => "邮箱已被使用"
			    
			    , self::RET_CODE_CHECKFAILED => "附加码验证失败"
			    
			    , self::RET_TOKEN_GENERATEFAILED => "令牌生成失败"
			    , self::RET_TOKEN_EXPIRED => "令牌已失效"
				
			    , self::RET_IP_FORMATERROR => "IP格式不正确"
			    , self::RET_IP_BANNED => "IP已被禁止"
			    , self::RET_IP_CANNOTBANNEDSELF => "不能禁止自己"
			    
			    , self::RET_FACE_UPLOADSUCCEED => "头像上传成功"
				, self::RET_FACE_UPLOADFAILED => "头像上传失败"
				
				, self::RET_SITE_CLOSED => "站点关闭"
				
				, self::RET_USERINFO_UPDATESUCCEED => "用户信息更新成功"
			    , self::RET_USERINFO_UPDATEFAILED => "用户信息更新失败"
			    , self::RET_USERINFO_NICKNAMEFORMATERROR => "姓名格式不正确"
			    , self::RET_USERINFO_GENDERFORMATERROR => "性别不正确"
			    , self::RET_USERINFO_BIRTHYEARFORMATERROR => "出生年不正确"
			    , self::RET_USERINFO_BIRTHMONTHFORMATERROR => "出生月不正确"
			    , self::RET_USERINFO_BIRTHDAYFORMATERROR => "出生日不正确"
			    , self::RET_USERINFO_PRIVBIRTHFORMATERROR => "生日显示方式不正确"
			    , self::RET_USERINFO_HOMENATIONFORMATERROR => "家乡国家格式不正确"
			    , self::RET_USERINFO_HOMEPROVINCEFORMATERROR => "家乡省份格式不正确"
			    , self::RET_USERINFO_HOMECITYFORMATERROR => "家乡城市不正确"
			    , self::RET_USERINFO_NATIONFORMATERROR => "所在地国家不正确"
			    , self::RET_USERINFO_PROVINCEFORMATERROR => "所在地省份不正确"
			    , self::RET_USERINFO_CITYFORMATERROR => "所在地城市不正确"
			    , self::RET_USERINFO_OCCUPATIONFORMATERROR => "从事行业不正确"
			    , self::RET_USERINFO_HOMEPAGEFORMATERROR => "个人主页格式不正确"
			    , self::RET_USERINFO_SUMMARYFORMATERROR => "个人介绍不正确"
			    
			    , self::RET_ADMIN_FRONTLOGIN => "请先前台登录"
			    , self::RET_ADMIN_FRONTBOUND => "请先绑定腾讯帐号"
			    , self::RET_ADMIN_NOPERMISSION => "你的帐号无后台访问权限"
			    , self::RET_ADMIN_DOESNOTMATCH => "管理员与前台登录用户不匹配"
			    
			    , self::RET_UC_USERPROTECTED => "该用户受保护无权限更改"
				);
				
	static public function getMsg($code)
	{
		return isset(self::$ARR_MSG[$code]) ? self::$ARR_MSG[$code] : '';
	}
	
	//get json
	static public function getRetJson($code, $msg='', $data=NULL, $callback=NULL)
	{
		if(empty($msg))
		{
			$msg = "".self::$ARR_MSG[$code];
		}
		$jsonPrototype = array(
			"ret" => $code,
			"msg" => $msg,
		 	"timestamp" => time()//返回接口调用的服务器时间戳 michal
		);
		
		if(!empty($data))
		{
			$jsonPrototype["data"] = is_string($data) ? str_replace(array("\r","\n","\t"),'',$data) : $data;
		} 
		
		$json = json_encode($jsonPrototype);
		//验证回调函数合法性
		if(Core_Comm_Validator::checkCallback($callback))
		{
			$json = $callback."(".$json.")";
		}
		return $json;
	}
}
?>