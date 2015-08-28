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
class Model_Recommend extends Core_Model
{
    /**
     * Database table name.
     * @var (string)
     */
    protected $_tableName = 'component_recommend';

    /**
     * Database table columns.
     * @var (array), table fields.
     */
    protected $_fields = array('id', 'account', 'description');

    public function getRecommendList()
    {
        return $this->getList('`id` ASC', '`id`, `account`, `description`');
    }

    /**
     * 根据话题ID, 获取对应的推荐收听设置内容.
     * 
     * @param: (int) $recommenderId, 推荐人的ID.
     * @return: (mixed), 返回ID对应的推荐收听设置内容, 或为false.
     */
    public function getRecommenderById($recommenderId)
    {
        $recommenderId = (int) $recommenderId;
        return $this->fetchOne(
            "SELECT `id`, `account`, `description`
             FROM `{$this->_tableName}`
             WHERE `id` = {$recommenderId} LIMIT 1"
        );
    }
    
    /**
     * 添加推荐收听
     *
     * @param: (array) $recommenderData, 添加的推荐用户信息.
     * @return: link to Core_Model::add().
     */
    public function addRecommender(array $recommenderData = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->add($recommenderData);
    }
    
    /**
     * 更新推荐收听.
     *
     * @param: (int) $recommenderId, 待更新的推荐用户ID.
     * @param: (array) $recommenderData, 推荐用户的信息.
     * @return: link to Core_Model:update().
     */
    public function updateRecommender($recommenderId, array $recommenderData)
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        $this->_id = (int) $recommenderId;
        return $this->update($recommenderData);
    }

    /**
     * 删除推荐收听.
     * 
     * @param: (array) $recommenderIds, 包括推荐用户ID的数组.
     * @return: link to Core_Model::remove().
     */
    public function deleteRecommender(array $recommenderIds = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->remove($recommenderIds);
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

        $sql = 'SELECT `account`, `description`
                FROM `' . $this->getTableName() . "`
                WHERE 1 ORDER BY `id` DESC LIMIT {$amount}";
        $list = $this->getAll($sql);
        if ($list)
        {
            $accounts = array();
            foreach ($list as $key => $value)
            {
                $account = $accounts[] = strtolower($value['account']) ;
                $list[$account] = $value;
            }
            $data = Model_User_Util::getInfos($accounts);
            foreach ($data as $key => $value)
            {
                //由于本地用户名与云端账号大小写可能不一致, 临时赋一个交集状态值.
                $commonKey = strtolower($key);
                if (isset($list[$commonKey]['description']))
                {
                    $data[$key]['introduction'] = $list[$commonKey]['description'];
                }
            }   
            return $data;
        }
        return false;
    }
}
