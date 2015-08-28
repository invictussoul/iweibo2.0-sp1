<?php
/**
 * 发送邮件方法
 * @author Icehu
 *
 * 使用配置组：mail
 * 说明如下：
 * array (
	  'open' => '1',	//是否开启
	  'type' => 'smtp',	//类型	smtp or sendmail or mail
	  'smtp_server' => 'smtp.exmail.qq.com',	//smtpserver
	  'smtp_port' => '25',	//smtm端口
	  'sender' => 'xxxx@xxxx.xx',	//发件人地址
	  'auth' => '1',	//是否需要授权
	  'smtp_user' => 'xxxx@xxxx.xx', //smtp 验证用户
	  'smtp_pwd' => '111111',	//smtp 用户密码
	  'sendmail_path' => '',	//sendmail地址
	  'sendmail_args' => '',	//sendmail 参数
	);
 *
 */
class Core_Mail
{
	/**
	 *
	 * 发送邮件方法
	 *
	 * @param string $recipients 收件人
	 * @param string $subject 主题
	 * @param string $body 内容
	 * @param array $headers 邮件头
	 * @param bool $debug 是否调试
	 * @return bool
	 * @author Icehu
	 */
	public static function send($recipients, $subject , $body , $headers=array() , $debug=false)
    {
        $config = Core_Config::get(null, 'mail', array());
		$charset = 'utf-8';
		$sendheaders = array();
		$sendheaders['From'] = $config['sender'];
		$sendheaders['Subject'] = "=?$charset?B?".base64_encode(str_replace(array("\r","\n"), array('',' '),$subject)).'?=';
		$sendheaders['Content-Transfer-Encoding'] = 'base64';
		$sendheaders['MIME-Version'] = '1.0';
		$sendheaders['Content-type'] = 'text/html; charset='.$charset;
		$sendheaders['X-Mailer'] = 'iWeiboMailer 1.0';
		if($headers && is_array($headers))
		{
			foreach( (array)$headers as $k => $v)
			{
				$sendheaders[$k] = $v;
			}
		}

		$body = preg_replace(array('/(?<!\r)\n/','/\r(?!\n)/'), "\r\n", $body);
        $body = str_replace("\n.", "\n..", $body);
		$body = base64_encode($body);

		if($config['open'])
		{
			$type = $config['type'];
			$params = array();
			if($type == 'smtp')
			{
				$params['host'] = $config['smtp_server'];
				$config['smtp_port'] && $params['port'] = $config['smtp_port'];
				if($config['auth'])
				{
					$params['auth'] = true;
					$params['username'] = $config['smtp_user'];
					$params['password'] = $config['smtp_pwd'];
				}
				$params['localhost'] = 'iWeibo2.0';
				$params['debug'] = $debug;
			}
			elseif($type == 'sendmail')
			{
				if($config['sendmail_path'])
					$params['sendmail_path'] = $config['sendmail_path'];
				if($config['sendmail_args'])
					$params['sendmail_args'] = $config['sendmail_args'];
			}
			$mailer = Mail::factory($type, $params);
			$e = $mailer->send($recipients,$sendheaders,$body);
			if (is_a($e, 'PEAR_Error'))
			{
				if($debug)
					die($e->getMessage() . "\n");
				else
					return false;
			}
			return true;
		}
    }

	/**
	 * 测试smtp是否可用
	 *
	 * @param array $config
	 * @return bool
	 * @author Icehu
	 */
	public static function testsmtp($config=array())
	{
		!$config && $config = Core_Config::get(null, 'mail', array());
		$params = array();
		$params['host'] = $config['smtp_server'];
		$config['smtp_port'] && $params['port'] = $config['smtp_port'];
		if ($config['auth'])
		{
			$params['auth'] = true;
			$params['username'] = $config['smtp_user'];
			$params['password'] = $config['smtp_pwd'];
		}
		$params['localhost'] = 'iWeibo2.0Mailer';
		$mailer = Mail::factory('smtp', $params);
		$result = $mailer->getSMTPObject();
        if(PEAR::isError($result))
		{
            return false;
        }
		$mailer->disconnect();
		return true;
	}
}
