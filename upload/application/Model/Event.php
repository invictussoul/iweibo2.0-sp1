<?php
/**
 * 活动操作类
 * @author reginx<reginx@qq.com>
 * $Id: Event.php 3153 2011-06-22 10:01:37Z cyp $
 */
class Model_Event extends Core_Model
{

	/**
     * 数据库表名
     * @var type string
     */
	protected $_tableName = 'user_event';
	/**
     * 数据库字段名
     * @var type array
     */
	protected $_fields = array (
	'id','uid','uname','realname','phone','deadline',
	'pic','addr','cost','title','sdate','joins',
	'edate','views','status','dateline','message',
	'contact',
	);
	/**
     * 数据库主键
     * @var type string
     */
	protected $_idkey = 'id';


	function getAllEvent($pid=1,$fields=array(),$cond='',$order='Id desc',$psize=20){
		$ret = array();
		$ret['pid'] = $pid;
		$ret['psize'] = $psize;
		$ret['events'] = array();
		$fields = empty($fields)?'*':'`'.implode('`,`',$fields).'`';
		$cond = $cond==''?' 1=1 ':$cond;
		$pid = empty($pid)?1:intval($pid);
		$pid = $pid>0 ? $pid:1;
		$start = ($pid - 1) * $psize;
		$ret['total'] = Core_Db::getOne("select count(Id) from ".$this->_tableName." where $cond");
		$ret['maxPage'] = $ret['total'] % $psize === 0 ?($ret['total'] / $psize):(intval($ret['total'] / $psize)+1);
		if($ret['total'] === 0){ return $ret; }
		$sql = "select $fields from ".$this->_tableName." where $cond order by $order limit  $start , $psize";
		$q = Core_Db::query($sql);
		while($row =Core_Db::fetchArray($q)){
			$this->getStatusText($row);
			$row['isend'] = $row['edate'] <= time() ? TRUE:FALSE;
			if(isset($row['dateline'])){
				$row['dateline'] = date('Y-m-d H:i',$row['dateline']);
			}
			if(isset($row['deadline'])){
				$row['deadline'] = date('Y-m-d H:i',$row['deadline']);
			}
			if(isset($row['sdate'])){
				$row['sdate'] = date('Y-m-d H:i',$row['sdate']);
			}
			if(isset($row['edate'])){
				$row['edate'] = date('Y-m-d H:i',$row['edate']);
			}
			$uinfos = Model_User_Util::getInfos(array($row['uname']));
			if($uinfos){
				$row['host'] = array_pop($uinfos);
				if($row['host']['head']){
					$row['host']['head'] .='/50';
				}else{
					$row['host']['head'] = '/resource/images/default_head_50.png';
				}
			}
			$ret['events'][] = $row;
		}
		return $ret;
	}


	function getMyAll($uid,$pid=1,$psize=20){
		$id = array();
		$q  = Core_Db::query('select eid from '.$this->_prefix."user_event_join where uid = '$uid'");
		while ($row = Core_Db::fetchArray($q)) {
			$id[] = $row['eid'];
		}
		if(!empty($id)){
			$idstr = ' id  in ('.implode(',',$id).')';
		}else{
			$idstr = 'id = 0';
		}
		return $this->getAllEvent($pid,array(),$idstr,'Id desc',$psize);
	}
	/**
     * 获取状态文本
     *
     * @param unknown_type $status
     * @param unknown_type $sdate
     * @param unknown_type $edate
     * @return unknown
     */
	function getStatusText(&$row){
		if(!empty($row)){
			$row['statusCode'] = 0;
			if($row['status'] == 1 || $row['status'] > 3){
				if($row['sdate'] <= time() && $row['edate'] >= time()){
					$row['statusText'] = '进行中';
					$row['statusCode'] = 1;
				}else    if($row['sdate'] >= time()){
					$row['statusText'] = '未开始';
					if($row['deadline'] >= time()){
						$row['statusText'] = '火热报名中';
						$row['statusCode'] = 1;
					}else{
						$row['statusText'] = '报名结束';
					}
				}else if($row['edate'] <= time()){
					$row['statusText'] = '已结束';
				}
			}
			if($row['status'] == 2){
				$row['statusText'] = '用户关闭';
			}
			if($row['status'] == 3){
				$row['statusText'] = '管理封禁';
			}
		}
	}


	/**
     * 设置活动状态值
     * @abstract $val: 0 结束 1 封禁 2 正常进行中 其他 推荐
     * @param unknown_type $id 
     * @param unknown_type $val
     */
	function setStatus($id,$val){
		Core_Db::query( "update ".$this->_tableName." set status = '$val' where id = '$id'");
	}

	/**
     * 获取状态值
     *
     * @param unknown_type $id
     * @return unknown
     */
	function getStatus($id){
		$cur = Core_Db::fetchOne("select status,edate from ".$this->_tableName." where Id = '$id'");
		if($cur['edate'] <= time()){ return 0;}
		return $cur['status'];
	}

	/**
     * 获取相关SQL条件字串
     *
     * @param unknown_type $num
     * @return unknown
     */
	function getCondStr($num){
		$ret = '1 = 1';
		switch (intval($num)) {
			case 2: $ret = '  status > 3 '; break;
			case 3: $ret = ' (status = 1 or status > 3) and sdate <='.Core_Fun::time().' and edate >'.Core_Fun::time().' ';
			break;
			case 4: $ret = '  status = 2 '; break;
			case 5: $ret = '  status = 3 '; break;
			case 6: $ret = ' (status > 3 or status = 1) and edate <= '.Core_Fun::time().' '; break;
			case 7: $ret = ' (status > 3 or status = 1) and sdate >= '.Core_Fun::time().' '; break;
		}
		return $ret;
	}

	/**
     * 获取最新的活动
     *
     * @param unknown_type $limit
     * @param unknown_type $fields
     * @param unknown_type $titleLimit
     * @param unknown_type $msgLimit
     * @param unknown_type $addStr
     * @return unknown
     */
	function  getNewEvents($limit=5,$fields=array(),$titleLimit=NULL,$msgLimit=NULL,$addStr=FALSE){
		$events = $this->getAllEvent(1,$fields,'('.$this->getCondStr(3).') or ('.$this->getCondStr(7).')','status , Id desc',$limit);
		$this->doSub($events['events'],$titleLimit,$msgLimit,$addStr);
		return $events['events'];
	}

	/**
     * 截取相关活动字段的字符串
     *
     * @param unknown_type $arr
     * @param unknown_type $titieLimit
     * @param unknown_type $msgLimit
     * @param unknown_type $addStr
     */
	function doSub(&$arr,$titieLimit=NULL,$msgLimit=NULL,$addStr=FALSE){
		for ($i=0;$i<sizeof($events['events']);$i++){
			if(isset($arr[$i]['message']) && !empty($msgLimit)){
				$$arr[$i]['message'] = Core_Comm_Util::utfSubstr($arr[$i]['message'],intval($msgLimit),$addStr?'...':'');
			}
			if(isset($arr[$i]['title']) && !empty($titleLimit)){
				$arr[$i]['title'] = Core_Comm_Util::utfSubstr($arr[$i]['title'],intval($titleLimit),$addStr?'...':'');
			}
		}
	}

	/**
     *  获取推荐热门活动列表
     *
     * @param unknown_type $limit
     * @param unknown_type $fields
     * @param unknown_type $titleLimit
     * @param unknown_type $msgLimit
     * @param unknown_type $addStr
     */
	function  getRecHotEvents($limit=5,$fields=array(),$titleLimit=NULL,$msgLimit=NULL,$pid=1,$addStr=FALSE){
		$events = $this->getAllEvent($pid,$fields,' (status = 1 or status > 3) and edate >= '.time(),' status desc ,sdate asc',$limit);
		$this->doSub($events['events'],$titleLimit,$msgLimit,$addStr);
		return $events;
	}

	/**
     * 获取指定用户的活动
     *
     * @param unknown_type $uid
     * @param unknown_type $limit
     * @param unknown_type $fields
     * @param unknown_type $titleLimit
     * @param unknown_type $msgLimit
     * @param unknown_type $addStr
     * @return unknown
     */
	function  getUserEvents($uid=0,$limit=5,$fields=array(),$titleLimit=NULL,$msgLimit=NULL,$addStr=FALSE){
		$events = $this->getAllEvent(1,$fields," uid = '$uid' ",' id desc',$limit);
		$this->doSub($events['events'],$titleLimit,$msgLimit,$addStr);
		return $events['events'];
	}

	/**
     * 获取活动信息
     *
     * @param unknown_type $id
     * @param unknown_type $loadUser
     * @return unknown
     */
	function getEvent($id){
		$sql = "select * from ".$this->_tableName." where id = '$id'";
		$event = Core_Db::fetchOne($sql);
		$this->getStatusText($event);
		$event['dateline'] = date('Y-m-d H:i',$event['dateline']);
		$event['deadline'] = date('Y-m-d H:i',$event['deadline']);
		$event['sdate']      = date('Y-m-d H:i',$event['sdate']);
		$event['edate']      = date('Y-m-d H:i',$event['edate']);
		$uinfos = Model_User_Util::getInfos(array($event['uname']));
		if($uinfos){
			$event['host'] = array_pop($uinfos);
			if($event['host']['head']){
				$event['host']['head'] .='/50';
			}else{
				$event['host']['head'] = '/resource/images/default_head_50.png';
			}
		}
		return $event;
	}

	/**
     * 是否参与了活动
     *
     * @param unknown_type $uid
     * @param unknown_type $eid
     * @return unknown
     */
	function hasJoined($uid,$eid){
		$sql = "select count(id) from ".$this->_prefix."user_event_join where eid = '$eid' and uid = '$uid'";
		return Core_Db::getOne($sql)>0?TRUE:FALSE;
	}

	/**
     * 参加活动
     *
     * @param unknown_type $uid
     * @param unknown_type $eid
     * @param unknown_type $contact
     * @param unknown_type $msg
     * @return unknown
     */
	function dojoin($uid,$uname,$eid,$contact=0,$msg=''){
		$sql = "replace into ".$this->_prefix."user_event_join(uid,uname,eid,contact,message,dateline)"
		." values($uid,'$uname',$eid,'".Core_Db::sqlescape(htmlspecialchars($contact))."','".Core_Db::sqlescape(htmlspecialchars($msg))."',".time().")";
		if(Core_Db::query($sql)){
			Core_Db::query("update ".$this->_prefix."user_event set joins = joins+1 where id = '$eid' ");
			return TRUE;
		}
		return FALSE;
	}

	/**
     * 取消参加活动
     *
     * @param unknown_type $uid
     * @param unknown_type $eid
     * @return unknown
     */
	function docjoin($uid,$eid){
		$sql = "delete from ".$this->_prefix."user_event_join where eid = '$eid' and uid = '$uid'";
		if(Core_Db::query($sql)){
			Core_Db::query("update ".$this->_prefix."user_event set joins = joins-1 where id = '$eid' ");
			return TRUE;
		}
		return FALSE;
	}

	/**
     * 获取活动的参与用户
     *
     * @param unknown_type $eid
     * @param unknown_type $pid
     * @param unknown_type $psize
     * @return unknown
     */
	function getEventUsers($eid,$pid=1,$psize=20){
		$ret = array();
		$ret['pid'] = $pid;
		$ret['psize'] = $psize;
		$pid = empty($pid)?1:intval($pid);
		$pid = $pid>0 ? $pid:1;
		$start = ($pid - 1) * $psize;
		$ret['total'] = Core_Db::getOne("select count(id) from ".$this->_prefix."user_event_join where eid = '$eid'");
		$ret['maxPage'] = $ret['total'] % $psize === 0 ?($ret['total'] / $psize):(intval($ret['total'] / $psize)+1);
		if($ret['total'] === 0){ return $ret; }
		$sql = "select * from ".$this->_prefix
		."user_event_join where  eid = '$eid' order by dateline limit $start , $psize";
		$q = Core_Db::query($sql);
		while($row = Core_Db::fetchArray($q)){
			if(isset($row['dateline'])){
				$row['dateline'] = date('Y-m-d H:i',$row['dateline']);
			}
			$ret['users'][strtolower($row['uname'])] = $row;
		}
		if(!empty($ret['users'])){
			$uinfos = Model_User_Util::getInfos(array_keys($ret['users']));
		}
		foreach ($uinfos as &$v){
			if($v['head']){
				$v['head'] .='/50';
			}else{
				$v['head'] = '/resource/images/default_head_50.png';
			}
		}
		$ret['users'] = array_merge($ret['users'],$uinfos);
		return $ret;
	}


	/**
     * 验证是否拥有修改活动的权限
     *
     * @param unknown_type $uid
     * @param unknown_type $eid
     * @return unknown
     */
	function valiteEventMgr($uid,$eid){
		$sql = "select count(id) from ".$this->_prefix."user_event where id='$eid' and uid='$uid' ";
		if(Core_Db::getOne($sql) == 1){
			return TRUE;
		}
		return FALSE;
	}


	function  getMsg($eid,&$users){
		$names = "'".implode("','",array_keys($users))."'";
		$sql = "select uname,contact,message,dateline from ".$this->_prefix."user_event_join where eid = '$eid' and uname in ($names) order by id desc";
		$q = Core_Db::query($sql);
		while ($row = Core_Db::fetchArray($q)) {
			$users[strtolower($row['uname'])]['msg']        = $row['message'];
			$users[strtolower($row['uname'])]['contact']    = $row['contact'];
			$users[strtolower($row['uname'])]['dateline']    = date('Y-m-d H:i',$row['dateline']);
		}
	}
	/**
     * 保存活动
     *
     * @param unknown_type $user
     * @param unknown_type $data
     * @return unknown
     */
	function save($user,$data){
		$ret = $event = array();
		$event['id']     = trim($data['eid']);
		$event['uid']     = $user['uid'];
		$event['uname']     = $user['name'];
		$event['title']     = trim($data['title']);
		$event['phone']     = trim($data['phone']);
		$event['realname']= trim($data['realname']);
		$event['addr']    = trim($data['addr']);
		$event['deadline']= trim($data['deadline']);
		$event['sdate']    = trim($data['sdate']);
		$event['edate']    = trim($data['edate']);
		$event['cost']    = trim($data['cost']);
		$event['contact']    = trim($data['contact']);
		$event['message']    = trim($data['message']);

		// 敏感词语过滤
		foreach ($event as $v){
			if(Model_Filter::checkContent($v)){
				return '请勿输入敏感字符!'.$v;
			}
		}
		if(!$this->checkStrLength($event['title'],1,80)){
			$ret = '请填写活动标题!';
		}
		if(!$this->checkStrLength($event['addr'],1,100)){
			$ret = '请填写活动地址!';
		}
		if(!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s*\d{1,2}(\:\d{1,2}){0,2})?$/i',$event['deadline'])){
			$ret = '请选择截至时间!';
		}
		if(!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s*\d{1,2}(\:\d{1,2}){0,2})?$/i',$event['sdate'])){
			$ret = '请选择开始时间!';
		}
		if(!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s*\d{1,2}(\:\d{1,2}){0,2})?$/i',$event['edate'])){
			$ret = '请选择结束时间!';
		}
		if($event['cost'] == 1){
			$money = $data['money'];
			if(!preg_match('/\d+/i',$money)){
				$ret = '金额填写不正确!';
			}
			$event['cost'] = $money;
		}
		if(!empty($ret)){
			return $ret;
		}
		$event['contact'] = $event['contact']?1:0;
		// 图片上传
		$event['pic'] = '';
		if(isset($_FILES['pic']) && $_FILES['pic']['error'] === 0){
			$size = getimagesize($_FILES['pic']['tmp_name']);
			if(intval($size[0])!=120 || intval($size[1]) != 120){
				return  '请上传长宽为 120 的图片';
			}
			$upload = Core_Util_Upload::upload('pic','jpg,png',1024*1024);
			if($upload['code']!=0){
				return $upload['msg'];
			}else{
				$event['pic'] = $upload['link'];
			}
		}
		// 新增..进行图片验证
		if(intval($event['id']) === 0 && $event['pic'] == ''){
			return  '请上传活动图片';
		}
		$event['message']  = htmlspecialchars($event['message']);
		$event['title']      = htmlspecialchars($event['title']);
		$event['realname'] = htmlspecialchars($event['realname']);
		$event['addr']      = htmlspecialchars($event['addr']);
		$event['phone']      = htmlspecialchars($event['phone']);
		$event['deadline'] = strtotime($event['deadline']);
		$event['sdate']     = strtotime($event['sdate']);
		$event['edate']      = strtotime($event['edate']);
		$event = Core_Db::sqlescape($event);
		if(intval($event['id']) === 0){
			$sql = "insert into ".$this->_prefix."user_event(uid,uname,realname,phone,deadline,"
			." pic,addr,cost,title,sdate,joins,edate,views,status,dateline,message,contact) "
			." values($event[uid],'{$event['uname']}','{$event['realname']}','{$event['phone']}',{$event['deadline']},"
			." '{$event['pic']}','{$event['addr']}',{$event['cost']},'{$event['title']}',{$event['sdate']},1,{$event['edate']},"
			." 1,1,".time().",'{$event['message']}',{$event['contact']})";
			// 添加发起者到 参与活动表
			if(Core_Db::query($sql)){
				$eid = Core_Db::insertId();
				$ret = $eid;
				$sql = "insert into ".$this->_prefix."user_event_join(uid,uname,eid,contact,dateline) "
				." values($event[uid],'{$event['uname']}',$eid,'{$event['phone']}',".time().")";
				Core_Db::query($sql);
			}

		}else{
			// 修改保存
			$sql = "update ".$this->_prefix."user_event set realname='{$event['realname']}',phone='{$event['phone']}',"
			."deadline=$event[deadline]"
			.($event['pic']?" ,pic='{$event['pic']}' ":"").",addr='{$event['addr']}',cost={$event['cost']},"
			."title='{$event['title']}',sdate={$event['sdate']},edate={$event['edate']},modtime=".time().","
			."message='{$event['message']}',contact={$event['contact']} where uid='{$event['uid']}' and id = '{$event['id']}'";
			if(Core_Db::query($sql)){
				$ret = $event['id'];
			}

		}
		return $ret;
	}

	/**
     * 验证字符串长度
     *
     * @param string $str
     * @param int $minlen
     * @param int $maxlen
     * @return boolean
     */
	public function checkStrLength($str, $minlen, $maxlen){
		mb_internal_encoding('UTF-8');
		$len = mb_strlen($str);
		if($len < $minlen || $len > $maxlen){
			return 0;
		}
		return 1;
	}
	/**
     * 获取我发起的活动
     *
     * @param unknown_type $uid
     * @param unknown_type $pid
     * @return unknown
     */
	function getMyEvents($uid,$pid){
		return $this->getAllEvent($pid,array(),"uid='$uid'");
	}

	/**
     * 获取我参与的活动
     *
     * @param unknown_type $uid
     * @param unknown_type $pid
     * @return unknown
     */
	function getMyjoins($uid){
		$ret  = array();
		$sql  = "select ie.* from ".$this->_prefix."user_event_join iej ";
		$sql .= "left join ".$this->_prefix."user_event ie on iej.eid = ie.id ";
		$sql .= "where iej.uid = '$uid'  order by ie.id desc";
		$q    = Core_Db::query($sql);
		while ($row = Core_Db::fetchArray($q)) {
			$this->getStatusText($row);
			$row['sdate']   = date('Y-m-d H:i',$row['sdate']);
			$row['edate']   = date('Y-m-d H:i',$row['edate']);
			$row['dateline']   = date('Y-m-d H:i',$row['dateline']);
			$row['deadline']   = date('Y-m-d H:i',$row['deadline']);
			$uinfos = Model_User_Util::getInfos(array($row['uname']));
			if($uinfos){
				$row['host'] = array_pop($uinfos);
				if($row['host']['head']){
					$row['host']['head'] .='/50';
				}else{
					$row['host']['head'] = '/resource/images/default_head_50.png';
				}
			}
			$ret[] = $row;
		}
		return $ret;
	}

	/**
     * 用户关闭活动
     *
     * @param unknown_type $id
     * @return unknown
     */
	function closeEvent($id){
		return Core_Db::query("update ".$this->_prefix."user_event set status = 2 where id = '$id'");
	}

	/**
     * 删除活动
     *
     * @param unknown_type $id
     */
	function delEvent($id){
		Core_Db::query("delete from ".$this->_prefix."user_event where id = '$id'");
		Core_Db::query("delete from ".$this->_prefix."user_event_join where eid = '$id'");
		return TRUE;
	}

}