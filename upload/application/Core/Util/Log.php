<?php
/**
 * 
 * Log 日志处理方法
 *
 * @author Icehu
 * 
 */
class Core_Util_Log
{
	/**
	 * 日志存放目录
	 * 以根目录为起点
	 */
	const LOGDIR = 'logs';

	/**
	 * 文件日志方法
	 * @param string $filename 日志文件，会自动按日期分卷
	 * @param string $logs 日志内容
	 * @author Icehu
	 */
	public static function file($filename,$logs)
	{
		$file = ROOT . self::LOGDIR . '/' . $filename . date('Ymd');

		$handle = fopen($file, 'a');
		flock($handle, LOCK_EX);
		fwrite($handle, $logs . "\r\n");
		flock($handle, LOCK_UN);
		fclose($handle);
	}
}