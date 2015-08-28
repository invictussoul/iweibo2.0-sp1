<?php
/**
 * iWeibo
 *
 * LICENSE
 *
 * @homepage: http://open.t.qq.com/apps/iweibo 
 * @category: iWeibo
 * @package: 
 * @copyright:
 * @license 
 * @version: 2.0
 * @author: Bluexchen<Bluexchen@tencent.com>
 */
class Model_Hottopic extends Core_Model
{
    /**
     * Database table name.
     * @var (string)
     */
    protected $_tableName = 'component_hottopic';

    /**
     * Database table columns.
     * @var (array), table fields.
     */
    protected $_fields = array('id', 'name', 'description', 'picture', 'picture2');
    

    /**
     * 获取话题列表. 
     *
     * @return: (array), 返回话题列表
     */
    public function getHotTopics()
    {
        return $this->getList('`id` ASC', '`id`, `name`, `description`, `picture`, `picture2`');
    }

    /**
     * 根据话题ID, 获取对应的话题设置内容.
     * 
     * @return: (mixed), 返回ID对应的话题, 或为false.
     */
    public function getHotTopicById($hotTopicId)
    {
        $hotTopicId = (int) $hotTopicId;
        return $this->fetchOne(
            "SELECT `id`, `name`, `description`, `picture`, `picture2`
             FROM `##__component_hottopic`
             WHERE `id` = {$hotTopicId} LIMIT 1"
        );
    }
    
    /**
     * 根据话题名字, 获取对应的话题设置内容. 
     *
     * @param: (string) $hotTopicName, 话题的名字. 
     * @return: (mixed) 返回对应的话题内容, 或为false.
     */
    public function getHotTopicByName($hotTopicName)
    {
        return Core_Db::fetchOne(
            'SELECT `id`, `name`, `description`, `picture`, `picture2`
             FROM `##__component_hottopic`
             WHERE `name` = \'' . Core_Db::sqlescape($hotTopicName) . '\' LIMIT 1'
        );
    }

    /**
     * 添加热点话题
     * 
     * @param: (array) $hotTopicData, 话题的内容. 
     * @return: link to Core_Mode::add().
     */
    public function addHotTopic(array $hotTopicData = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->add($hotTopicData);
    }

    /**
     * 更新热点话题
     * 
     * @param: (int) $hotTopicId, 更新的话题ID.
     * @param: (array) $hotTopicData, 话题的内容. 
     * @return: link to Core_Model::update().
     */
    public function updateHotTopic($hotTopicId, array $hotTopicData)
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        $this->_id = (int) $hotTopicId;
        return $this->update($hotTopicData);
    }

    /**
     * 删除热点话题
     *
     * @param: (array) $hotTopicIds, 删除的话题ID.
     * @return: link to Core_Model::remove().
     */
    public function deleteHotTopic(array $hotTopicIds = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->remove($hotTopicIds);
    }

    /**
     * 组件统一数据导出接口.
     *
     * 本方法组件后台管理"约定统一"方法命名, 本方法同时也存在于其他组件中, 
     * 所有的组件Model均约定该命名, 方便组件数据统一获取.
     * (对于部分需要获取用户信息, 如推荐名人, 推荐组件, 本方法内部同时会调用api
     * 获取相关信息.)
     * 
     * @param: (int) $amount, 获取本组件数据的数量. 
     *　　　　(对应于后台管理->界面->组件管理中, 编辑具体组件时的"显示条数". 
     * @reutrn: (mixed), array形式返回组件数据, 否则返回false.
     */
    public function __toData($amount = 10)
    {
        $amount = max((int) $amount, 1);
        $sql = 'SELECT `id`, `name`, `description`, `picture`, `picture2`
                FROM `' . $this->getTableName() . "`
                WHERE 1 ORDER BY `id` DESC LIMIT {$amount}";
        $list = $this->getAll($sql);

        return $list ? $list : '';
    }
}
