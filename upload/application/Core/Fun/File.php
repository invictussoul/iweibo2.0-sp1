<?php
/**
 * 文件操作类
 * @author Icehu
 */
class Core_Fun_File {

	/**
	 * 创建目录，可以创建子目录
	 * @param string $dir	目录路径，绝对路径
	 * @author Icehu
	 */
	public static function makeDir( $dir ) {
		mkdir( $dir , 0777 , true);
	}

	/**
	 * 向一个文件写入数据
	 * @param string $file	写入的文件
	 * @param string $data	写入的数据
	 * @author Icehu
	 */
	public static function _write( $file , $data ) {
		file_put_contents( $file , $data , LOCK_EX );
		@chmod( $file , 0777 );
	}

	/**
	 * 向一个文件写入数据
	 * 在目录/文件不存在的时候，自动创建
	 * @param string $file	写入的文件
	 * @param string $data	写入的数据
	 * @author Icehu
	 */
	public static function write( $file , $data ) {
		$_updir = dirname( $file );
		if (file_exists( $_updir ) && is_dir( $_updir )) {
			
		}else {
			self::makeDir( $_updir );
		}
		self::_write( $file , $data );
	}

	/**
	 * 读取文件内容
	 * @param string $file	文件地址，绝对路径
	 * @param number $offset	起始位置
	 * @param number $len	读取长度，默认读取所有
	 * @return string
	 * @author Icehu
	 */
	public static function read( $file , $offset = 0 , $len = null) {
		if ($len) {
			return file_get_contents( $file , true , null , $offset , $len );
		}
		return file_get_contents( $file , true , null , $offset );
	}
	/**
	 * 删除一个文件
	 * @param string $file	文件名，绝对地址
	 * @author Icehu
	 */
	public static function delete( $file ) {
		self::remove( $file );
	}

	/**
	 * 删除一个文件
	 * @param string $file	文件名，绝对地址
	 * @author Icehu
	 */
	public static function remove( $file ) {
		@unlink( $file );
	}
}