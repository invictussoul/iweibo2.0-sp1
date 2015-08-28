<?php
/**
 * 验证码处理逻辑类
 * 
 */
class Core_Lib_Gdcheck {
	/**
	 * 验证码数字(字符串)
	 *
	 * @var string $Number
	 */
	public $number;
	/**
	 * 验证码过期时间(秒)
	 *
	 * @var number $CkTime
	 */
	public $ckTime;
	/**
	 * 验证码标志ID
	 *
	 * @var string $CkID
	 */
	public $ckID;
	/**
	 * 验证码产生时间
	 *
	 * @var number $ckCrtime
	 */
	public $ckCrtime;
	/**
	 * 验证码会话保持标志
	 *
	 * @var string $ckCkey
	 */
	public $ckCkey="_CkCkey_";
	/**
	 * 验证码长度
	 * 改变该参数来改变验证码的字符数目
	 *
	 * @var number $ckLen
	 */
	public $ckLen=4;
	/**
	 * 验证码图片长度,改变该参数来改变验证码图片的长度
	 *
	 * @var number $Img_x
	 */
	public $Img_x=80;
	/**
	 * 验证码图片高,改变该参数来改变验证码图片的高度
	 *
	 * @var number $Img_y
	 */
	public $Img_y=22;
	/**
	 * 验证码背景颜色(使用RGB3原色)
	 *
	 * @var array $Img_back
	 */
	public $Img_back = array(255,255,255);
	/**
	 * 验证码边框
	 *
	 * @var array $Img_border
	 */
	public $Img_border = array(0,0,0);
	/**
	 * 验证码所用字体数组
	 *
	 * @var array
	 * @todo 添加更多GD可用的字体文件
	 */
	public $Img_font = array(1,2,3,4,5);
	/**
	 * 保存验证码的数据库表
	 *
	 * @var unknown_type
	 */
	public static $Table = '##__user_gdsession';
	
	public $domain = null;
	
	public static $_table = null;
	
	const DB = 'user';
	
	/**
	 * PHP5构造函数
	 *
	 * @param number $time 会话保持时间(分钟)
	 */
	function __construct($time=300){
		$this->domain = Core_Config::get('cookiedomain','basic',null);
		if( empty($this->domain)
		|| !preg_match('/' . str_replace('.','\.',$this->domain) . '$/i' , '.'.$_SERVER['HTTP_HOST'])
		|| !preg_match('/\./',$this->domain)
		){
			$this->domain = null;
		}
		$this->ckTime = $time;

		$this->ckID = str_replace(array('"',"'"),'',Core_Fun::getcookie($this->ckCkey));
		if(empty($this->ckID)) $this->ckID = $this->CreateRandomID();
//		$this->GetNumber();
	}
	/**
	 * 从$CkID获得验证码的相关信息
	 *
	 * @return void
	 */
	function getNumber(){
		if(empty($this->ckID)) return '';
		$_table = self::_gettable();
		$row = $_table->find($this->ckID);
		$this->ckCrtime = @$row['time'];
		$this->number = @$row['number'];
	}
	
	/**
	 * 获得一个随机验数字证码
	 *
	 * @return string 随机的数字验证码
	 */
	function CreateRandomNumber(){
		mt_srand((double)microtime() * 1000000);
		$ck = "";
		$randomNumberRange = "BCEFGHJKMPQRTVWXY";
		for ($i = 0;$i<$this->ckLen;$i++){
			//生成 a -> z 范围的随机验证码
			//$ck .= strtolower(chr(mt_rand(ord('a'),ord('z'))));
			$ck .= strtolower($randomNumberRange{mt_rand(0, 16)});
		}
		return $ck;
	}
	
	/**
	 * 生成一个随机的验证码标志
	 * 改写该函数可获得更加丰富的验证码字符
	 * 
	 * @return string 随机的验证码标志
	 */
	function CreateRandomID(){
		mt_srand((double)microtime() * 1000000);
		$ck = "";
		$len = mt_rand(5,8);
		for ($i=0;$i<$len;$i++){
			$ck .= chr(mt_rand(ord('a'),ord('z')));
		}
		$ck = substr(md5($ck.time().$_SERVER["HTTP_USER_AGENT"]),mt_rand(0,32-$len),$len);
		return $ck;
	}
	
	/**
	 * 验证一个 $num 是否正确
	 *
	 * @param string $num 待验证的验证码串
	 * @param bool $noupdate 检查后是否更新Session 更新后，当前验证码失效
	 * @return bool
	 */
	public static function check($num,$noupdate=false){
		$self = new self();
		$num = strtolower($num);
		$self->getNumber();
		//验证码错误
		if(empty($self->number) || $num!=$self->number)
		$r = false;
		//验证码过期
		else if(Core_Fun::time()-$self->ckCrtime>$self->ckTime)
		$r = false;
		//验证码有效
		else if(!empty($self->number) && $num == $self->number)
		$r = true;
		//处理数据
		if (!$noupdate) 
		{
			$self->updatesession();
		}
		return $r;
	}
	
	function updatesession(){
		$ar = array(
			'id' => $this->ckID,
			'number' => $this->CreateRandomNumber(),
			'time' => Core_Fun::time(),
		);
		$_table = self::_gettable();
		$_table->update($ar,array(),array(),true);
	}

	/**
	 * 获取 Core_Db_Table
	 * @return Core_Db_Table
	 */
	public static function _gettable(){
		if (null == self::$_table) 
		{
			self::$_table = new Core_Db_Table( self::$Table,array('id','number','time') );
		}
		return self::$_table;
	}
	
	/**
	 * 创建一个验证码图片 (仅简单生成了一个示例图片)
	 * 改写该函数或得更加丰富的验证码图像
	 *
	 */
	function createImg(){
		$this->number = $this->CreateRandomNumber();
		if(empty($this->ckID)) $this->ckID = $this->CreateRandomID();
		//设置标志位
		Core_Fun::setcookie($this->ckCkey,$this->ckID,null,true);
		
		//加入数据库
		$ar = array(
			'id' => $this->ckID,
			'number' => $this->number,
			'time' => Core_Fun::time()
		);
		$_table = self::_gettable();
		$_table->add($ar,array(),true);
		//create验证图片
		if(function_exists('imagecreate') && function_exists('imagecolorallocate') 
		&& function_exists('imagepng') && function_exists('imagesetpixel') 
		&& function_exists('imageString') && function_exists('imagedestroy') 
		&& function_exists('imagefilledrectangle') && function_exists('imagerectangle')){
			//GD库可用
//			$img = imagecreate($this->Img_x,$this->Img_y);
//			$back = imagecolorallocate($img,$this->Img_back[0],$this->Img_back[1],$this->Img_back[2]);
//			$border = imagecolorallocate($img,$this->Img_border[0],$this->Img_border[1],$this->Img_border[2]);
//			imagefilledrectangle($img,0,0,$this->Img_x-1,$this->Img_y-1,$back);
//			imagerectangle($img,0,0,$this->Img_x-1,$this->Img_y-1,$border);
			
			//获得字库
//			$font = imageloadfont(INCLUDE_PATH.'font.gdf');
			
			//画图
//			for ($i=0;$i<strlen($this->number);$i++){
//				imagestring($img,$font,$i*$this->Img_x/$this->ckLen+mt_rand(1,5),mt_rand(1,6),strtoupper($this->number[$i]),imagecolorallocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100)));
//			}
//			header("Pragma:no-cache");
//			header("Cache-control:no-cache");
//			header("Content-type: image/png");
//			imagepng($img);
//			imagedestroy($img);
			
			$code = new Core_Lib_Seccode();
			$code->code = $this->number;
			$code->fontpath = ROOT.'seccode/fonts/';
			$code->datapath = ROOT.'seccode/';
			$code->display();
			exit;
		}else{
			//GD不可用时
			echo 'GD NOT USEABLE';
		}
	}
}