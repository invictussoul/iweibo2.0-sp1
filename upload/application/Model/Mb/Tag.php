<?php

/**
 * 标签操作类
 *
 * @author lvfeng
 */
class Model_Mb_Tag
{

    //标签表对象
    protected $tagTableObj;
    //标签表可操作字段
    protected $tagTableSafeColumu = array ('tagname', 'usenum', 'visible','color');
	//数据容器
    protected static $_data = array();
    
    /**
     * 构造函数
     */
    public function __construct ()
    {
        $this->tagTableObj = Table_Mb_Tag::getInstance();
    }

    /**
     * 获取标签
     *
     * @param array $tagInfo
     * @return int $id
     */
    public function addTag ($tagInfo)
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存
        return $this->tagTableObj->add ($tagInfo, $this->tagTableSafeColumu);
    }

    /**
     * 根据编号编辑标签信息
     *
     * @param array $tagInfo
     * @return boolean
     */
    public function editTagInfo ($tagInfo)
    {
    	//触发清除组件缓存
        Model_Componentprocessunit::cleanupAllComponentCache();  
        //本地化的时候
        if(!Model_Tag::getTagSrc())
        {
            if ($tagInfo['visible'] == 1) 
                Model_User_TagLocal::unLockTag ($tagInfo['tagname']); //解锁
            else
                Model_User_TagLocal::lockTag ($tagInfo['tagname']);   //锁定
        }
        return $this->tagTableObj->update ($tagInfo, $this->tagTableSafeColumu);
    }

    /**
     * 根据编号删除标签
     *
     * @param int|array $id
     * @param 对user_tag用户标签表的操作 optype==0,解锁；optype==1,删除
     * @return int boolean
     */
    public function deleteTagById ($id, $opType=1)
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存
        $_tag = $this->getTagInfoById ($id);

        if(!Model_Tag::getTagSrc())//本地化的时候
        {
            if($opType)
                Model_User_TagLocal::delTagByName ($_tag['tagname']);//删除
            else
                Model_User_TagLocal::unLockTag ($_tag['tagname']);//解锁
        }
        return $this->tagTableObj->remove ($id);
    }

    /**
     * 根据编号取得标签信息
     *
     * @param int $id
     * @return array
     */
    public function getTagInfoById ($id)
    {
    	if(!isset(self::$_data['id.'.$id]))
    		self::$_data['id.'.$id] = $this->tagTableObj->queryOne ('*', array (array ('id', $id)));
    	return self::$_data['id.'.$id];
    }

    /**
     * 根据编号取得标签信息
     *
     * @param int $id
     * @return array
     */
    public static function updateTagUsenum ($name, $op)
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存
        $obj = new self();
        $_tag = $obj->getTagInfoByName ($name);
        if (null == $_tag) 
        {
            $sql = 'INSERT INTO `##__mb_tag` SET `usenum` = 1 , `tagname` = \'' . Core_Db::sqlescape ($name) . '\'';
        } 
        else 
        {
            if ($op == 1 || $op == -1) 
            {
                $num = $_tag['usenum'] + $op;
                if ($num <= 0)
                    $sql = 'DELETE FROM `##__mb_tag` WHERE `tagname`= \'' . Core_Db::sqlescape ($name) . '\'';
                else
                    $sql = 'UPDATE `##__mb_tag` SET `usenum`= ' . $num . ' WHERE `tagname` = \'' . Core_Db::sqlescape ($name) . '\'';
            }
        }
        Core_Db::query ($sql);
    }

    /**
     * 根据标签名称取得标签信息
     *
     * @param string $tagname
     * @return array
     */
    public function getTagInfoByName ($tagName)
    {
    	if(!isset(self::$_data['name.'.$tagName]))
    		self::$_data['name.'.$tagName] = $this->tagTableObj->queryOne ('*', array (array ('tagname', $tagName)));
    	return self::$_data['name.'.$tagName];
    }
    
    /**
     * 取得标签列表
     *
     * @param array $whereArr
     * @param array $orderByArr
     * @param array $limitArr
     * @return array
     */
    public function getTagList ($whereArr=array (), $orderByArr=array (), $limitArr=array ())
    {
        return $this->tagTableObj->queryAll ('*', $whereArr, $orderByArr, $limitArr);
    }

    /**
     * 获取排名前amount个的标签列表
     * @param int $amount 
     * @return array
     */
    public function __toData ($amount)
    {
        $whereArr = array ();
        $whereArr[] = array ('usenum', 1, '>=');
        $whereArr[] = array ('visible', 1, '=');
        $orderByArr = 'usenum desc';
        $limitArr = array ($amount, 0);
        $tags = $this->getTagList ($whereArr, $orderByArr, $limitArr);
        $return = array();
        foreach ($tags as $t)
        {
            if(strlen($t['color']) < 1)
                $t['color'] = $this->getRandColor ();
            $return[] = $t;
        }
        return $return;
    }

    /**
     * 随机产生一个标签颜色
     * @return string
     */
    private function getRandColor(){
        $default_color = array('000000','333333','666666');
        return $default_color[rand (1, 1000) % count ($default_color)];
    }

    /**
     * 获得标签数量
     *
     * @param array $whereArr
     * @return int
     */
    public function getTagCount ($whereArr=array ())
    {
        return $this->tagTableObj->queryCount ($whereArr);
    }
}
