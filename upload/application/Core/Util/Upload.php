<?php

/**
 * Upload上传 封装
 * @author Icehu
 */
class Core_Util_Upload
{
	const EXT = 'txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid';

	/**
	 * 上传文件工具
	 * useage Core_Utile_Upload::upload('file')
	 * useage Core_Utile_Upload::upload('file','jpg,png,gif')支持类型检查
	 * 返回数组
	 * 成功：array('code'=>0,'link'=>'不带域名的文件相对路径');
	 * 失败：array('code'=>'非0', 'msg' => '错误原因')
	 * @param string $inputFiled 文件上传的表单名
	 * @return int
	 * @author Icehu
	 */
	public static function upload( $inputFiled , $allowExt=FALSE,$maxsize=FALSE)
	{
		$attachdir= 'uploadfile';//上传文件保存路径，结尾不要带/
		$dirtype=1;//1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
		$maxattachsize=$maxsize?$maxsize:2097152;//最大上传大小，默认是2M
		
		$rt = array(
			'code' => 0,
			'msg' => 'success',
			'link' => '',
		);

		$upfile=@$_FILES[$inputFiled];
		if(!isset($upfile))	
		{
			$rt['code'] = 10;
			$rt['msg'] = 'file_empty';
		}
		elseif(!empty($upfile['error']))
		{
			$rt['code'] = $upfile['error'];
			switch ($rt['code'])
			{
				case UPLOAD_ERR_INI_SIZE:
					$rt['msg'] = 'File_too_large';
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$rt['msg'] = 'UPLOAD_ERR_FORM_SIZE';
					break;
				case UPLOAD_ERR_PARTIAL:
					$rt['msg'] = 'UPLOAD_ERR_PARTIAL';
					break;
				case UPLOAD_ERR_NO_FILE:
					$rt['msg'] = 'UPLOAD_ERR_NO_FILE';
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$rt['msg'] = 'UPLOAD_ERR_NO_TMP_DIR';
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$rt['msg'] = 'UPLOAD_ERR_CANT_WRITE';
					break;
			}
		}
		elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none')
		{
			$rt['msg'] = 10;
			$rt['msg'] = 'upload_error';
		}
		else
		{
			$temppath=$upfile['tmp_name'];
			$fileinfo=pathinfo($upfile['name']);
			$extension=$fileinfo['extension'];
			// 类型检查
			if($allowExt){
				if(strrpos($allowExt,strtolower($extension)) === FALSE){
					$rt['msg'] = '该类型文件无法上传,仅支持 '.$allowExt.' ';
					$rt['code'] = 10;
					return $rt;
				}
			}
			// end
			if( in_array(strtolower($extension), explode(',', self::EXT)) )
			{
				$bytes=filesize($temppath);
				if($bytes > $maxattachsize)
				{
					$rt['msg'] = '请不要上传大小超过'.Core_Fun::formatBytes($maxattachsize).'的文件';
					$rt['code'] = 10;
				}
				else
				{
					$attach_subdir = 'day_'.  Core_Fun::date('ymd');
					$attach_dir = $attachdir.'/'.$attach_subdir;
					if(!file_exists(HTDOCS_ROOT . $attach_dir))
					{
						Core_Fun_File::makeDir(HTDOCS_ROOT . $attach_dir);
						fclose(fopen(HTDOCS_ROOT . $attach_dir.'/index.htm', 'w'));
					}
					$filename=Core_Fun::date("YmdHis").mt_rand(1000,9999).'.'.$extension;
					$target = HTDOCS_ROOT . $attach_dir.'/'.$filename;

					rename($upfile['tmp_name'],$target);
					@chmod($target,0755);
					$_webroot = Core_Fun::getWebroot();
					$link = $_webroot . $attach_dir.'/'.$filename;
					$rt['link'] = $link;
				}
			}
			else
			{
				$rt['msg'] = '该类型不允许!';
				$rt['code'] = 11;
			}
			@unlink($temppath);
		}

		return $rt;
	}
}
