<?php

/**
 * iweibo2.0
 * 
 * 兼容open和本地平臺用戶model
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_User_FriendOpen.php 2011/5/26
 * @package Controller
 * @since 2.0
 */
class Model_User
{
    const USER_LOCAL_FOLLOW = 1;//本地关系链接
    const USER_LOCAL_TAG = 2;//本地标签数据
    const USER_LOCAL = 4;//本地用户数据
    const USER_OPEN = 8; //平台用户数据

    protected static $_typeKey = array(//数据类型键值数组
        self::USER_LOCAL_FOLLOW,
        self::USER_LOCAL_TAG,
        self::USER_LOCAL,
        self::USER_OPEN
    );
    
    protected static $_typeFunc = array(//数据类型内容数组
        self::USER_LOCAL_FOLLOW => 'localUserFollow',
        self::USER_LOCAL_TAG => 'localUserTag',
        self::USER_LOCAL => 'localUser', 
        self::USER_OPEN => 'openUser'
    );
    
    
    public static $getFullOne = false;//请求open类型。 true:获取open个人完整消息|false:open批量接口获取简单信息
    public static $requestType = array();//要求的类型
    public static $responseFunc = array();//处理的函数
    public static $responseData = array();//返回的原始數據
    
    public static $unLocalUser = array('fansnum'=>0,'idolnum'=>0,'gid'=>0,'tag'=>array());//没有的本地用户数据覆盖
    
    /*
     * 分析类型
     * @return array
     */
    
    public static function parseType($type)
    {
        //type不合法 默认为本地用户数据
        (empty($type) || ($type>15)) && $type=4;

        $type =  strval(base_convert($type,10,2));//转为二进制字符串
        $len = strlen($type);
        for($i=0;$i<$len;$i++)
        {
            $num = $type{$i};
            $exp = $len-1-$i;
            $key = $num * pow(2,$exp);
            if($key && in_array($key,self::$_typeKey))
            {
                self::$requestType[] = $key;
                self::$responseFunc[] = self::$_typeFunc[$key];
            }
        }
        return ;
    }
 
    /*
     * 分析返回数据
     * @return array
     */
    
    public static function parseData($data,$names,$dataType)
    {
       $returnData = $data;
       $reqTypeNum = count(self::$requestType);//请求数量
       //        
       //只搜索一项的原始的数据 去掉key
       if($reqTypeNum==1)
       {
           switch($dataType)
           {
               case self::USER_LOCAL_FOLLOW:
                   $returnData = $data['localUserFollow'];
               break;
               case self::USER_LOCAL_TAG:
                   $returnData = $data['localUserTag'];
               break;
               case self::USER_LOCAL:
                   $returnData = $data['localUser'];
               break;
               case self::USER_OPEN:
                   $returnData = $data['openUser'];
               break;           
           }
       }
       
       if($dataType==5 ||$dataType==6 || $dataType==7)//只有本地用户数据
       {
           $returnData = array();
           foreach($data['localUser'] AS $k=>$v)
           {
              if($v)
              {
                  !empty($data['localUserTag'][$k]['tag']) && $v && $v['tag'] = $data['localUserTag'][$k]['tag'];
                  $returnData[$k] = $v;
              }
           }
       }

       if($dataType>8)//肯定有平台数据时候
       {
            $localAuth = Core_Config::get('localauth', 'certification.info');//本地认证信息开关
            $openAuth = Core_Config::get('platformauth', 'certification.info');//平台认证信息开关

           $returnData = array();
           foreach($data['openUser'] AS $k => $v)
           {
                if(empty($v)) continue;
               $k = strtolower($k);
                $returnData[$k] = empty($v)?array():$v;//初始化数据为open

                if(isset($data['localUser']))//请求本地数据 用本地数据覆盖平台数据
                {
                    if(empty($data['localUser'][$k]))//本地数据不存在
                    {
                        $defaultLocal = self::$unLocalUser;
                        if(!isset($data['localUserFollow']))//无本地关系链  不覆盖粉丝数，偶像数
                        {
                            unset($defaultLocal['fansnum'], $defaultLocal['idolnum']);
                        }
                        $returnData[$k] = array_merge($v,$defaultLocal);//本地不存在此人,粉丝数 好友数清空
                    }else{
                        $defaultLocal =$data['localUser'][$k];
                        if(!isset($data['localUserFollow']))//无本地关系链 不覆盖粉丝数，偶像数
                        {
                            unset($defaultLocal['fansnum'], $defaultLocal['idolnum']);
                        }
                        $returnData[$k] = array_merge($v,$defaultLocal);//本地数据覆盖
                    }
                }


               //description,is_auth 融合了open和local的认证文字。local优先
               $returnData[$k]['is_auth'] = false;
               $returnData[$k]['description'] = '';
               if($localAuth && !empty($data['localUser'][$k]['localauth']))
                {
                    $returnData[$k]['description'] = empty($data['localUser'][$k]['localauthtext'])?'':$data['localUser'][$k]['localauthtext']; //description记录认证信息
                    $returnData[$k]['is_auth'] = true;//is_auth 记录此人是否是认证用户
                }elseif($openAuth &&  !empty($v['isvip'])){
                    $returnData[$k]['description'] = empty($v['verifyinfo'])?'腾讯微博认证名人':$v['verifyinfo'];
                    $returnData[$k]['is_auth'] = true;
                }

                if(isset($data['localUserTag']))//有本地标签数据
                {
                   $returnData[$k]['tag'] = isset($data['localUserTag'][$k]['tag'])?$data['localUserTag'][$k]['tag']:array();
                }
           }
       }


        //如果有本地收听关系 和其他状态 本地收听关系
        if(isset($data['localUserFollow']) && $reqTypeNum>1)
       {
           foreach($returnData AS $k=> &$v)
           {
                $v && $v['isidol']= empty($data['localUserFollow'][$k])?0:1;
           }
       } 
       return $returnData;
    }

    /*
     * 获取单个用户信息
     * @return array
     */

    public static function userInfo($name='', $dataType=0)
    {
        is_array($name) && $name = array_shift($name);
        self::$getFullOne=true;//打开接口
        $returnData=self::usersInfo($name, $dataType);
        self::$getFullOne = false;//关闭个人搜索
        if(isset($returnData[$name]))
        {
            $returnData = $returnData[$name];
        }elseif(isset($returnData[strtolower($name)])){
            $returnData = $returnData[strtolower($name)];
        }else{//没有此用户
            $returnData = self::$unLocalUser;
            $returnData['name'] = $name;
        }
        return $returnData;
    }
    
    /*
     * 获取用户列表的信息
     * @return array
     */

    public static function usersInfo($names='', $dataType=0)
    {
        $names = Core_Comm_Util::formatName2Array($names);
        if(empty($names)) return array();
        $names = array_map('strtolower', $names);
        
        self::$responseData = self::$responseFunc =  self::$requestType = array();//初始化数据
        
        self::parseType($dataType);//检测数据类型
        
        $returnData = array();
        foreach(self::$responseFunc AS $func)
        {
            if(method_exists('Model_User',$func))
            {
               $returnData[$func] = Model_User::$func($names);
            }
        }
        self::$responseData = $returnData;//备份原始数据
        $returnData = self::parseData($returnData,$names,$dataType);//格式化返回数据
        return $returnData;
    }
    
    //获取open平台用户数据
    public static function openUser($names)
    {
        if(self::$getFullOne)//如果是搜索完整单人信息
        {
            $returnUser = Model_User_Open::getUserInfo($names,false);
        }
        else//搜索多人接口获取简单op信息
        {
            $returnUser = Model_User_Open::getUsersInfo($names,false);
        }
        return $returnUser;
    }

    //获取local平台用户数据
    public static function localUser($names)
    {
        return Model_User_Local::getUsersInfo($names,false);
    }

    //获取local平台用户tag数据
    public static function localUserTag($names)
    {
        return Model_User_TagLocal::getUsersTag($names,false, true);
    }    
    
    //获取local用户tag数据是否是偶像
    public static function localUserFollow($names)
    {
        return Model_User_FriendLocal::checkFriend($names,1,false, true);
    }    
    
}

?>
