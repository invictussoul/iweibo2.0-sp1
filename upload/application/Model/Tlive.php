<?php
/**
 * 微直播操作类
 * @author reginx<reginx@qq.com>
 * $Id: Tlive.php 3206 2011-06-23 05:07:49Z cyp $
 */
class Model_Tlive extends Core_Model{

    // 直播表
    var $tTLive = '';
    // 直播参与表
    var $tTJoin = '';
    // 直播内容表
    var $tTPost = '';

    /**
     * 初始化
     *
     */
    function __construct(){
        parent::__construct();
        $this->tTLive = $this->_prefix.'user_tlive';
        $this->tTJoin = $this->_prefix.'user_tlive_join';
        $this->tTPost = $this->_prefix.'user_tlive_post';
    }

    /**
     * 获取分页数据句柄
     *
     * @param String $countSQl
     * @param String $selectSQL
     * @param Integer $pid
     * @param Integer $psize
     * @return Array
     */
    function getLimitList($pid,$psize,$countSQl,$selectSQL){
        $ret = array();
        $ret['pid']       = $pid;
        $ret['psize']     = $psize;
        $ret['query']     = FALSE;
        $pid             = intval($pid) === 0?1:intval($pid);
        $start         = ($pid - 1) * $psize;
        $ret['total']     = Core_Db::getOne($countSQl);
        $ret['maxPage']     = $ret['total'] % $psize === 0 ?($ret['total'] / $psize):(intval($ret['total'] / $psize)+1);
        if($ret['total']     === 0){
            return $ret;
        }
        $ret['query']     = Core_Db::query(sprintf($selectSQL,"limit  $start , $psize"));
        return $ret;
    }


    /**
     * 获取直播数据列表(支持分页)
     *
     * @param Integer $pid
     * @param Integer $psize
     * @param Array $fields
     * @param String $where
     * @param String $order
     * @return Array
     */
    function getTliveList($pid=1,$psize=20,$fields=array(),$where='',$order=' id desc'){
        $client = Core_Open_Api::getClient();
        $ret      = array();
        $fields = empty($fields)?'*':'`'.implode('`,`',$fields).'`';
        $where  = $where ==''?' 1=1 ' : $where;
        $ret = $this->getLimitList( $pid, $psize,
        "select count(id) from $this->tTLive where $where ",
        "select $fields from $this->tTLive where $where order by $order %s");
        $ret['data'] = $tmp =  array();
        while($row = Core_Db::fetchArray($ret['query'])){
            if($row['sdate'] < Core_Fun::time() && Core_Fun::time() < $row['edate']){
                $row['statusText'] = '进行中';
            }
            if($row['sdate'] > Core_Fun::time()){
                $row['statusText'] = '未开始';
            }
            if($row['edate'] < Core_Fun::time()){
                $row['statusText'] = '已结束';
            }
            $row['sdate']    = date('Y-m-d H:i',$row['sdate']);
            $row['edate']    = date('Y-m-d H:i',$row['edate']);
            $row['dateline']    = date('Y-m-d H:i',$row['dateline']);
            if(isset($row['host'])){
                $tmp = $client->getUserInfo(array('n'=>$row['host']));
                if($tmp['msg'] == 'ok'){
                    $row['user'] = Core_Lib_Base::formatU($tmp['data'],50);
                }
            }
            $row['style']     = unserialize($row['style']);
            $ret['data'][]     = $row;
        }
        $ret['size']  = sizeof($ret['data']);
        return $ret;
    }

    /**
     * 获取直播参与人员列表(支持分页)
     *
     * @param Integer $pid
     * @param Integer $psize
     * @param Array $fields
     * @param String $where
     * @param String $order
     * @return Array
     */
    function getTJoinList($pid=1,$psize=20,$fields=array(),$where='1=1',$order=' id desc'){
        $ret = array();
        $client = Core_Open_Api::getClient();
        $csql = "select count(id) from $this->tTJoin where $where ";
        $qsql = "select * from $this->tTJoin where $where order by $order %s ";
        $ret  = $this->getLimitList($pid,$psize,$csql,$qsql);
        $ret['data'] = array();
        while($row = Core_Db::fetchArray($ret['query'])){
            $ret['data'][strtolower($row['uname'])] = $row;
        }
        $userinfos = Model_User_Util::getInfos(array_keys($ret['data']));
        foreach ($userinfos as &$v){
            if($v['head']){
                $v['head'] .='/50';
            }else{
                $v['head'] = '/resource/images/default_head_50.png';
            }
        }
        $ret['data'] = array_merge($ret['data'],$userinfos);
        return $ret;
    }

    /**
     * 保存微直播
     *
     * @param Array $tlive
     * @param Array $style
     * @param Array $users
     * @return Boolean
     */
    function saveTlive($tlive=array(),$style=array(),$users=array()){
        $ret = array();
        if(trim($tlive['title']) == ''){
            $ret['error'] = '请填写微直播主题!';
        }
        $title = Core_Db::sqlescape(htmlspecialchars($tlive['title']));
        if(trim($tlive['tname']) == ''){
            $ret['error'] = '请填写微直播名称!';
        }
        $tname = Core_Db::sqlescape(htmlspecialchars($tlive['tname']));
        $tlive['direct'] = isset($tlive['direct']) ? 0:1;
        if(trim($tlive['desc']) == ''){
            $ret['error'] = '请填写微直播简介!';
        }
        $tlive['desc'] = Core_Db::sqlescape(htmlspecialchars($tlive['desc']));
        if(intval($tlive['sdate']) === 0 || strtotime($tlive['sdate']) >= strtotime($tlive['edate'])
        || !preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s*\d{1,2}(\:\d{1,2}){0,2})?$/i',$tlive['sdate'])){
            $ret['error'] = '请填写正确开始时间!';
        }
        if(intval($tlive['edate']) === 0 || strtotime($tlive['sdate']) >= strtotime($tlive['edate'])
        || !preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s*\d{1,2}(\:\d{1,2}){0,2})?$/i',$tlive['edate'])){
            $ret['error'] = '请填写正确结束时间!';
        }
        if(empty($users[0]) || trim($users[0]) == ''){
            $ret['error'] = '请填写主持人!';
        }
        if(isset($ret['error']) && !empty($ret['error'])){
            return $ret;
        }
        $users[0] = array_unique(explode(";",preg_replace('/\s+/i',';',$users[0])));
        $ret['error'] = $this->getUser($users[0],$this->_prefix);
        if(isset($ret['error']) && !empty($ret['error'])){
            return $ret;
        }
        if(empty($users[1]) || trim($users[1]) == ''){
            $ret['error'] =  '请填写嘉宾!';
            return $ret;
        }
        $users[1] = array_unique(explode(";",preg_replace('/\s+/i',';',$users[1])));
        $ret['error'] = $this->getUser($users[1],$this->_prefix);
        if(isset($ret['error']) && !empty($ret['error'])){
            return $ret;
        }
        $same = array_uintersect($users[0], $users[1], "strcasecmp");
        if(!empty($same) && is_array($same)){
            $ret['error'] =  "同一帐号只能是主持人或嘉宾! ".implode(',',$same);
            return $ret;
        }
        $tlive['notice'] = 0;
        $tlive['sdate'] = strtotime($tlive['sdate']);
        $tlive['edate'] = strtotime($tlive['edate']);

        // 样式处理
        if(isset($_FILES['outward']) && $_FILES['outward']['error'] === 0){
            $upload = Core_Util_Upload::upload('outward','jpg,png',1024*1024);
            if($upload['code'] != 0){
                return array('error'=>$upload['msg']);
            }else{
                $style['outward'] = $upload['link'];
            }
        }
        // 封面
        if(isset($_FILES['cover']) && $_FILES['cover']['error'] === 0){
            $upload = Core_Util_Upload::upload('cover','jpg,png',1024*1024);
            if($upload['code'] != 0){
                return array('error'=>$upload['msg']);
            }else{
                $style['cover'] = $upload['link'];
            }
        }
        // 背景
        if(isset($_FILES['background']) && $_FILES['background']['error'] === 0){
            $upload = Core_Util_Upload::upload('background','jpg,png',1024*1024);
            if($upload['code'] != 0){
                return array('error'=>$upload['msg']);
            }else{
                $style['background'] = $upload['link'];
            }
        }
        // 颜色代码验证
        if(!preg_match('/^\#[0-9a-zA-Z]{6}$/i',$style['bgcolor'])){
            $style['bgcolor'] = '';
        }
        if(!preg_match('/^\#[0-9a-zA-Z]{6}$/i',$style['linkcolor'])){
            $style['linkcolor'] = '';
        }
        $style['vod']     = '0';
        $style['repeat']  = $style['repeat']?1:0;
        $style         = addslashes(serialize($style));
        if(intval($tlive['id']) === 0){
            $sql = "insert into {$this->tTLive}(tname,title,notice,style,`desc`,direct,sdate,edate,dateline,`host`)".
            " values('{$tlive['tname']}','{$tlive['title']}',{$tlive['notice']},'$style','{$tlive['desc']}',{$tlive['direct']},".
            " {$tlive['sdate']},{$tlive['edate']},".Core_Fun::time().",'{$users[0][0]}')";
            Core_Db::query($sql);
            $tlive['id']  = Core_Db::insertId();
        }else{
            $tlive['id'] = intval($tlive['id']);
            $sql = "update {$this->tTLive} set tname = '{$tlive['tname']}',title='{$tlive['title']}',notice={$tlive['notice']},".
            " style='$style',`desc`='{$tlive['desc']}',direct={$tlive['direct']},sdate={$tlive['sdate']},edate={$tlive['edate']} ,".
            " `host` = '{$users[0][0]}' where id = {$tlive['id']} ";
            Core_Db::query($sql);
        }
        // 新增主持人
        $this->saveTJoin($tlive['id'],$users[0],0);
        // 新增嘉宾
        $this->saveTJoin($tlive['id'],$users[1],1);
        return TRUE;
    }


    /**
     * 验证用户
     *
     * @param unknown_type $user
     * @param unknown_type $prefix
     * @return unknown
     */
    function getUser(&$user,$prefix){
        for ($i=0;$i<sizeof($user);$i++){
            if(trim($user[$i]) != ''){
                $userInfo = array();
                try{
                    $userInfo = Core_Open_Api::getClient()->getUserInfo(array("n" => $user[$i]));
                }catch(exception $e){
                    if(!$name){
                        return "帐号 {$user[$i]} 不存在!";
                    }
                }
                if(empty($userInfo['data']['name'])){// 平台帐号不存在
                    if(!$name){
                        return "帐号 {$user[$i]} 不存在!";
                    }
                }
            }else{
                unset($user[$i]);
            }
        }
        $user = array_unique($user);
    }

    /**
     * 添加/更新参与者(主持人OR嘉宾)
     *
     * @param Integer $tid
     * @param mixed $uname
     * @param Integer $utype 0 主持人 1 嘉宾
     * @return Boolean
     */
    function saveTJoin($tid,$uname,$utype=0){
        Core_Db::query("delete from $this->tTJoin where tid = '$tid' and utype = $utype");
        $sql = "insert into {$this->tTJoin}(tid,uname,utype,dateline) values ";
        $dateline = time();
        if(is_array($uname)){
            foreach($uname as $k=>$name){
                if(trim($name) != ''){
                    if($k === 0){
                        $sql .= "($tid,'".Core_Db::sqlescape(htmlspecialchars($name))."',$utype,$dateline)";
                    }else{
                        $sql .= ",($tid,'".Core_Db::sqlescape(htmlspecialchars($name))."',$utype,$dateline)";
                    }
                }
            }
        }else{
            $sql .= "($tid,'".Core_Db::sqlescape(htmlspecialchars($uname))."',$utype,$dateline)";
        }
        return Core_Db::query($sql);
    }

    /**
     * 新增直播内容
     *
     * @param String  $uname   用户帐号
     * @param Integer $tid     直播ID
     * @param String  $message 消息内容
     * @return Boolean
     */
    function addTPost($uname,$tid,$msgid,$status,$date){
        $date = intval($date);
        $status = intval($status);
        $sql = "insert into {$this->tTPost} (uname,tid,msgid,dateline,`status`) ".
        " values('$uname',$tid,$msgid,$date,$status)";
        return Core_DB::query($sql);
    }


    /**
     * 设置直播内容状态(0待审核,1通过)
     *
     * @param Integer $tpid
     * @param Integer $status
     * @return Boolean
     */
    function setTPostStatus($id,$status){
        $status = $status?1:0;
        $id = is_array($id)? " in (".implode(',',Core_Db::sqlescape($id)).") ":" = '".Core_Db::sqlescape($id)."' ";
        return Core_Db::query("update {$this->tTPost} set status = $status where msgid $id ");
    }

    /**
     * 删除直播
     *
     * @param Integer $tid
     * @return Boolean
     */
    function delTLive($tid){
        $tid = intval($tid);
        return Core_Db::query("delete from {$this->tTJoin} where tid = '$tid'") &&
        Core_Db::query("delete from {$this->tTPost} where tid = '$tid'") &&
        Core_Db::query("delete from {$this->tTLive} where id  = '$tid'");
    }


    /**
     * 删除直播内容
     *
     * @param Integer $tid
     * @return Boolean
     */
    function delTPost($msgid){
        return Core_Db::query("delete from {$this->tTPost} where msgid = '$msgid'");
    }

    /**
     * 获取一条微直播记录
     *
     * @param unknown_type $where
     * @return unknown
     */
    function getTLive($where,$user=FALSE,$dateFormat=true){
        if($tlive = Core_Db::fetchOne("select * from {$this->tTLive} where $where")){
            $tlive = array_map('stripslashes',$tlive);
            if($user){
                $query = $this->getTJoins($tlive['id'],TRUE);
                while ($user = Core_Db::fetchArray($query)) {
                    $tlive['users'][$user['utype']] .= $user['uname']."\n";
                }
            }
            if($dateFormat){
                $tlive['sdate'] = date('Y-m-d H:i',$tlive['sdate']);
                $tlive['edate'] = date('Y-m-d H:i',$tlive['edate']);
            }
            $tlive['style'] = unserialize($tlive['style']);
            return $tlive;
        }
        return FALSE;
    }


    /**
     * 获取参与用户(主持人/嘉宾)
     *
     * @param Integer $tid
     * @param Boolean $query
     * @return Mixed
     */
    function getTJoins($tid,$query=FALSE){
        $sql = "select uname,utype from {$this->tTJoin} where tid = '$tid' ";
        return $query?Core_Db::query($sql):Core_Db::fetchAll($sql);
    }



    /**
     * 获取直播内容
     *
     * @param unknown_type $tid
     * @param unknown_type $pid
     * @param unknown_type $psize
     * @param unknown_type $ol
     */
    function getTPostList($tid,$type,$pid,$psize=30,$ol = TRUE,$utype='all'){
        $ret = $ids = $tmp = array();
        if($utype != 'all'){
            if(intval($tid) === 0){ return $ret;}
            $tid = intval($tid);
            $pid = intval($pid);
            $utype = intval($utype);
            $sql   = "select uname from $this->tTJoin where tid = $tid and utype = $utype ";
            $query = Core_Db::query($sql);
            while ($row = Core_Db::fetchArray($query)) {
                $ids[$row['uname']] = $row['uname'];
            }
            $names = " in ('". implode("','",array_keys($ids))."') ";
            $csql = "select  count(id)  from $this->tTPost where tid = $tid and `status` = $type and uname $names ";
            $qsql = "select  *  from $this->tTPost where tid = $tid and `status` = $type and uname $names order by id desc %s";
            $ret  = $this->getLimitList($pid,$psize,$csql,$qsql);
        }else{
            $tlive = $where = array();
            if(intval($tid) !== 0){
                $where[] = " id = $tid ";
            }
            if($ol){
                $where[] = " sdate < ".time()." and edate > ".time()." and direct = 0 ";
            }
            $where = implode(' and ',$where);
            $sql   = "select id,tname from $this->tTLive where $where ";
            $query = Core_Db::query($sql);
            while ($row = Core_Db::fetchArray($query)) {
                $tlive[$row['id']] = $row['tname'];
            }
            $ids = array_keys($tlive);
            if(!empty($ids)){
                if(sizeof($ids) === 1){
                    $ids  = $tid === 0 ? " = $ids[0] ":" = $tid ";
                }else{
                    $ids  = $tid === 0 ? " in (". implode(',',array_keys($tlive)).") ":" = $tid ";
                }
                $status = empty($type)&&$type!==0?" 1=1 and ":" `status`=$type and ";
                $csql = "select  count(id)  from $this->tTPost where $status tid $ids ";
                $qsql = "select  *  from $this->tTPost where  $status tid $ids order by id desc %s";
                $ret  = $this->getLimitList($pid,$psize,$csql,$qsql);
            }
        }

        $ret['data'] = array();
        while ($row = Core_Db::fetchArray($ret['query'])) {
            $row['tname']   = $tlive[$row['tid']];
            $row['tpid']    = $row['id'];
		$row['tstatus'] = $row['status'];
            $ret['data'][$row['msgid']] = $row;
        }
        // 批量拉取
        $client = Core_Open_Api::getClient();
        $ids = array_keys($ret['data']);
        if(!empty($ids)){
            // 批量拉取数据
            $msgs   = $client->getTlineFromIds(array('ids'=>implode(',',$ids)));
            //  消息格式化
            $temp = array();
            for ($i=0;$i<sizeof($msgs['data']['info']);$i++){
                $temp[$msgs['data']['info'][$i]['id'].''] = Core_Lib_Base::formatT($msgs['data']['info'][$i]);
            }
            // 整理
            foreach($ret['data'] as $k => &$v){
                if(isset($temp[$k])){
                    $v = array_merge($v,$temp[$k]);
                }else{
                    // 清除本地消息(平台上已经被删除的消息)
                    unset($ret['data'][$k]);
                    $this->delTPost($k);
                }
            }
        }
        // 
        $ret['size'] = sizeof($ret['data']);
        $ret['tlive'] = core_DB::fetchOne("select id,tname,title from $this->tTLive where id = '".intval($tid)."'");
        return $ret;
    }


}