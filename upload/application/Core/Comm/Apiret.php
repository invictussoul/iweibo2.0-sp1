<?php
/**
 * API发表接口错误字段errcode
 * @param 
 * @return
 * @author luckyxiang, echoyang
 * @package 原iweibo1 /application/controller/ApiErrCode
 * @time 2011/5/12
 */

class Core_Comm_Apiret
{
	const RET_SUCC = 0;
	const RET_BAD_LANGUAGE = 4;
	const RET_FORBID = 5;
	const RET_DELETED = 6;
	const RET_TOO_LONG = 8;
	const RET_BAD_MESSAGE = 9;
	const RET_FREQ_DENY = 10;
	const RET_SRC_DELETED = 11;
	const RET_CHECKING = 12;
	const RET_REPEAT = 13;
	const RET_ARG_ERR = -1;
	const RET_FREQ_LIMIT = -2;
	
	const RET_AUTH_FAIL = -3; //鉴权失败
	const RET_AUTH_INVALID = 3001; //无效TOKEN或被吊销
	const RET_AUTH_REQUESTREPLAY = 3002; //请求重放
	const RET_AUTH_DOESNOTEXIST = 3003; //access_token不存在
	const RET_AUTH_TIMEOUT = 3004; //access_token超时
	const RET_AUTH_VERSIONERROR = 3005; //oauth 版本不对
	const RET_AUTH_SIGNMETHODERROR = 3006; //oauth 签名方法不对
	const RET_AUTH_PARAMERROR = 3007; //参数错
	const RET_AUTH_PROCESSFAILED = 3008; //处理失败
	const RET_AUTH_CHECKSIGNFAILED = 3009; //验证签名失败
	const RET_AUTH_NETWORKERROR = 3010; //网络错误
	const RET_AUTH_PARAMLENGTHERROR = 3011; //参数长度不对
	
	const RET_SEND_ERROR = -4; //发表失败
	const RET_SEND_SUCCEED = 4000; //表示成功
	const RET_SEND_HASABUSE = 4004; //表示有过多脏话
	const RET_SEND_NOACCESS = 4005; //禁止访问，如城市，uin黑名单限制等
	const RET_SEND_NODEDOESNOTEXIST = 4006; //删除时：该记录不存在。发表时：父节点已不存在
	const RET_SEND_CONTENTLENGTHERROR = 4008; //内容超过最大长度：420字节 （以进行短url处理后的长度计）
	const RET_SEND_SPAMINFO = 4009; //包含垃圾信息：广告，恶意链接、黑名单号码等
	const RET_SEND_SENDTOOFAST = 4010; //发表太快，被频率限制
	const RET_SEND_SOURCEDELETED = 4011; //源消息已删除，如转播或回复时
	const RET_SEND_SOURCEREVIEW = 4012; //源消息审核中
	const RET_SEND_REPEAT = 4013; //重复发表
	
	const RET_SERVER_ERROR = -5;
	
	//msg
	public static $ARR_MSG 
		= array(self::RET_SUCC => "成功"
				
				, self::RET_BAD_LANGUAGE => "发表内容中含敏感词，请重新输入"
				, self::RET_FORBID => "禁止访问"
				, self::RET_DELETED => "此消息不存在"
				, self::RET_TOO_LONG => "内容超过最大长度"
				, self::RET_BAD_MESSAGE => "所在网络有安全问题，请稍候重试"
				, self::RET_FREQ_DENY => "发表过于频繁，请稍候再发"
				, self::RET_SRC_DELETED => "原文已被删除"
				, self::RET_CHECKING => "原文已被删除"
				, self::RET_REPEAT => "请不要连续发送重复的内容"
				
				, self::RET_ARG_ERR => "参数错误"
				
				, self::RET_FREQ_LIMIT => "频率受限"
				
				, self::RET_AUTH_FAIL => "鉴权失败"
				, self::RET_AUTH_INVALID => "无效TOKEN或被吊销"
				, self::RET_AUTH_REQUESTREPLAY => "请求重放"
				, self::RET_AUTH_DOESNOTEXIST => "access_token不存在"
				, self::RET_AUTH_TIMEOUT => "access_token超时"
				, self::RET_AUTH_VERSIONERROR => "oauth 版本不对"
				, self::RET_AUTH_SIGNMETHODERROR => "oauth 签名方法不对"
				, self::RET_AUTH_PARAMERROR => "参数错"
				, self::RET_AUTH_PROCESSFAILED => "处理失败"
				, self::RET_AUTH_CHECKSIGNFAILED => "验证签名失败"
				, self::RET_AUTH_NETWORKERROR => "网络错误"
				, self::RET_AUTH_PARAMLENGTHERROR => "参数长度不对"
				
				, self::RET_SEND_ERROR => "发表失败"
				, self::RET_SEND_SUCCEED => "表示成功"
				, self::RET_SEND_HASABUSE => "表示有过多脏话"
				, self::RET_SEND_NOACCESS => "禁止访问"
				, self::RET_SEND_NODEDOESNOTEXIST => "记录不存在或父节点已不存在"
				, self::RET_SEND_CONTENTLENGTHERROR => "内容超过最大长度"
				, self::RET_SEND_SPAMINFO => "包含垃圾信息"
				, self::RET_SEND_SENDTOOFAST => "发表太快"
				, self::RET_SEND_SOURCEDELETED => "源消息已删除"
				, self::RET_SEND_SOURCEREVIEW => "源消息审核中"
				, self::RET_SEND_REPEAT => "重复发表"
				
				, self::RET_SERVER_ERROR => "系统错误"
				);
				
	public static function getMsg($code)
	{
		if(key_exists($code, self::$ARR_MSG))
		{
			return self::$ARR_MSG[$code];
		}
		return "请求失败";
	}
}
?>