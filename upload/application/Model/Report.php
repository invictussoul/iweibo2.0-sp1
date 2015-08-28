<?php

/**
 * 举报
 * @author Gavin <yaojungang@comsenz.com>
 */
class Model_Report extends Core_Model
{

    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'mb_report';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array (
        'id',
        'uid',
        'name',
        'username',
        'targetaccount',
        'targetopentid',
        'reason',
        'time',
        'type',
        'state'
    );
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'id';

    //状态 0:未处理 1:已处理
    const STATE_NOMAL = 0;
    const STATE_CHECKED = 1;

    public $STATE = array (
        self::STATE_NOMAL => "未处理",
        self::STATE_CHECKED => "已处理"
    );
    public $TYPE = array (
        0 => '色情',
        1 => '虚假消息',
        2 => '政治',
        3 => '骚扰',
        4 => '含危险链接',
        5 => '其他'
    );

    /**
     * 添加
     * @param array  $data =  (
      'name' => '举报对象(平台用户名)',
      'tid' =>'平台微博ID',
      'reason' => '举报原因',
      'type' =>''
      );
     * @return bool
     */
    public static function addReport ($data)
    {
        //获取当前登录的用户啊
        $userObj = new Model_User_Member();
        $_user = $userObj->onGetCurrentUser ();
        $uid = $_user['uid'];
        $user = $userObj->getUserInfoByUid ($uid);
        $name = $user['name'];
        $obj = new self();
        $_report = array ();
        $_report['uid'] = $uid;
        $_report['name'] = $user['name'];
        $_report['username'] = $user['username'];
        $_report['targetaccount'] = $data['name'];
        if (isset ($data['tid'])) {
            $_report['targetopentid'] = $data['tid'];
            $_blog = Model_Blog::getBlogByOpentid ($data['tid']);
            $_report['blogcontent'] = $_blog['content'];
        }
        $_report['reason'] = $data['reason'];
        $_report['time'] = Core_Fun::time ();
        $_report['type'] = intval ($data['type']);
        return $obj->add ($_report);
    }

    /**
     * 修改
     * @param array $data
     * @return bool
     */
    public function editReport ($data)
    {
        return $this->update ($data);
    }

    /**
     * 删除
     * @param array $ids
     * @return bool
     */
    public function deleteReport ($ids)
    {
        return $this->remove ($ids);
    }

    /**
     * 把举报标记为已处理
     * @param array $ids
     */
    public function makeChecked ($ids)
    {
        $_idstr = Core_Comm_Util::array2string ($ids);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `state` = ' . self::STATE_CHECKED . ' WHERE `id` IN (' . $_idstr . ')';
        $this->query ($sql);
    }

}