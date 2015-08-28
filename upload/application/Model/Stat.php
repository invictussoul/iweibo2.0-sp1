<?php
/**
 * 统计相关接口类
 * @author reginx<reginx@qq.com>
 * $Id: Stat.php 3130 2011-06-22 08:28:52Z cyp $
 */
class Model_Stat extends Core_Model
{
    static $sobj = NULL;
    var $tabName = '';
    var $text = array();
    var $time = array();
    var $lang = array(
    'user'=>'用户',
    'sum'=>'综合概况',
    'content'=>'内容',
    'interaction'=>'互动',
    );
    

    function init(){
        $this->text        = include ROOT . 'config/stat.php';
        $this->tabName     = $this->_prefix.'mb_stat';
        $this->time['day'] = strtotime(date('Y-m-d',time()));
        $this->time['ws']  = strtotime(date('Y-m',time()).'-'.(date('d')-(date('w')?date('w'):7)+1));
        $this->time['we']  = $this->time['ws'] + 86400 * 7;
        $this->time['ms']  = strtotime(date('Y-m',time()));
        if(date('m') == 12){
            $this->time['me'] = strtotime(date('Y')+1).'-'.'01';
        }else{
            $this->time['me'] = strtotime(date('Y').'-'.(date('m')+1));
        }
    }

    /**
     * 获取当前类操作对象
     *
     * @return unknown
     */
    public static function &getInstance(){
        if(empty(self::$sobj)){
            self::$sobj = new self();
            self::$sobj->init();
        }
        return self::$sobj;
    }

    /**
     * 获取统计配置信息
     *
     * @return unknown
     */
    public static function getConfig(){
        return self::getInstance()->text;
    }
    
    /**
     * 获取统计配置信息
     *
     * @return unknown
     */
    public static function getLang(){
        return self::getInstance()->lang;
    }
    /**
     * 添加计数项
     *
     * @param unknown_type $key
     */
    public static  function addStat($key,$sname=0){
        return self::getInstance()->_addStat($key,$sname);
    }

    /**
     * 添加计数项
     *
     * @param unknown_type $key
     * @param unknown_type $sname
     */
    public   function _addStat($key,$sname=0){
        return Core_Db::query("insert into ".$this->tabName."(skey,sname,dateline) values('$key','$sname',".time().")");
    }

    /**
     * 获取指定时间段的统计项的次数
     *
     * @param unknown_type $key
     * @param unknown_type $limit NULL: 时间不限; m: 本月; w: 本周; d: 今天
     * @return unknown
     */
    public static function getStat($key,$limit=NULL){
        return self::getInstance()->_getStat($key,$limit);
    }

    public  function _getStat($key,$limit=NULL){
        $ret   = array();
        $stype = empty($limit)?'总计':(($limit=='m')?'本月':(($limit=='w')?'本周':(($limit=='d')?'今日':'')));
        $ret['stype']  = $stype;
        if(key_exists($key,$this->text)){
            $ret['name'] = $this->text[$key];
        }else{
            foreach (array_keys($this->text) as $k=>$v){
                if(isset($this->text[$k][$key])){
                    $ret['name'] = $this->text[$k][$key];
                    break;
                }
            }
        }
        $ret['name']  = isset($ret['name'])?$ret['name']:$key;
        $ret['count'] = Core_Db::getOne("select count(Id) from ".$this->tabName." where skey = '$key'  ".$this->_getLimitSql($limit));
        return $ret;
    }

    /**
     * 获取时间限制SQL条件字串
     *
     * @param unknown_type $limit  NULL: 时间不限; m: 本月; w: 本周; d: 今天
     * @return unknown
     */
    public static function getLimitSql($limit=NULL){
        return self::getInstance()->_getLimitSql($limit);
    }

    public  function _getLimitSql($limit=NULL){
        if(!in_array($limit,array(NULL,'m','w','d'))){ throw new Core_Db_Exception("统计接口不支持 <b>\"$limit\"</b> 的时间限制 "); }
        $sql = ' and ';
        if(empty($limit)){
            $sql = '';
        }else if(strtolower($limit) == 'm'){
            $sql .= "dateline >= ".$this->time['ms']." and dateline < ".$this->time['me'];
        }else if(strtolower($limit) == 'w'){
            $sql .= "dateline >= ".$this->time['ws']." and dateline < ".$this->time['we'];
        }else if(strtolower($limit) == 'd'){
            $sql .= "dateline >= ".$this->time['day'];
        }
        return $sql;
    }

    /**
     * 删除统计项
     *
     * @param unknown_type $key NULL 为全部删除
     * @return unknown
     */
    public static function clearStat($key=NULL){
        return self::getInstance()->_clearStat($key);
    }

    public  function _clearStat($key=NULL){
        if(empty($key)){
            return Core_Db::query('truncate table '.$this->tabName);
        }else{
            return Core_Db::query("delete from ".$this->tabName." where skey = '$key' ");
        }
    }

    /**
     * 获取所有指定时间段的统计数据
     *
     * @param unknown_type $limit
     * @return unknown
     */
    public static function getTotal($grp=NULL,$limit=NULL){
        return self::getInstance()->_getTotal($grp,$limit);
    }

    public  function _getTotal($grp=NULL,$limit=NULL){
        $ret = array();
        if(empty($grp)){
            $grp = $this->text;
        }else if(!is_array($grp)){
            return $this->_getGroupStat(isset($this->text[$grp])?$this->text[$grp]:array(),$limit);
        }
        foreach (self::$text as $k=>$v){
            $ret[$k]['val']  = $this->_getGroupStat($v,$limit);
            $ret[$k]['name'] = $this->lang[$k];
        }
        return $ret;
    }


    /**
     * 获取指定统计组信息 接口
     *
     * @param mixed $grp
     * @param unknown_type $limit
     * @return unknown
     */
    private static function getGroupStat($grp,$limit = NULL){
        return self::getInstance()->_getGroupStat($grp,$limit);
    }

    private  function _getGroupStat($grp,$limit = NULL){
        if(!is_array($grp)){
            if(isset($this->$text[$grp])){
                $grp = $this->$text[$grp];
            }else{
                return $this->_getStat($grp,$limit);
            }
        }
        $ret = array();
        $stype = empty($limit)?'总计':(($limit=='m')?'本月':(($limit=='w')?'本周':(($limit=='d')?'今日':'')));
        foreach ($grp as $k=>$v){
            $field = ($k =='login'?'distinct sname':'id');
            $ret[$k]['stype'] = $stype;
            $ret[$k]['count'] = Core_Db::getOne("select count($field) from ".$this->tabName." where skey='$k' ".$this->_getLimitSql($limit));
            $ret[$k]['name']  = $v;
        }
        return $ret;
    }
    
    /**
     * 获取某时间段内某项统计结果
     *
     * @param unknown_type $key
     * @param unknown_type $sd
     * @param unknown_type $ed
     * @return unknown
     */
    static function getDayStat($key,$sd,$ed){
        return self::getInstance()->_getDayStat($key,$sd,$ed);
    }
    
    function _getDayStat($key,$sd,$ed){
        $ret = array();
        $days = intval(($ed-$sd) / 86400);
        $field = ($key=='login'?'distinct sname':'id');
        for($i=0;$i<$days;$i++){
            $start = $sd+$i*86400;
            $end   = $start+86400;
            $ret[''.$start] = Core_Db::getOne("select count($field) from $this->tabName where skey = '$key' 
                and dateline >= $start and dateline < $end");
        }
        return $ret;
    }
}