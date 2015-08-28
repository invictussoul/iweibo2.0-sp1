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
class Model_Banner extends Core_Model
{
    /**
     * Database table name.
     * @var (string)
     */
    protected $_tableName = 'component_banner';

    /**
     * Database table columns.
     * @var (array), table fields.
     */
    protected $_fields = array('id', 'name', 'url', 'picture', 'description', 'start_time', 'end_time');

    public function getBannerList()
    {
        return $this->getList('`id` ASC', '`id`, `name`, `url`, `picture`, `description`, `start_time`, `end_time`');
    }

    /**
     * 根据话题ID, 获取对应的广告设置内容.
     * 
     * @param: (int) $bannerId, 推荐D的广告ID.
     * @return: (mixed), 返回ID对应的广告设置内容, 或为false.
     */
    public function getBannerById($bannerId)
    {
        $bannerId = (int) $bannerId;
        return Core_Db::fetchOne(
            "SELECT `id`, `name`, `url`, `picture`, `description`, `start_time`, `end_time`
             FROM `##__component_banner`
             WHERE `id` = {$bannerId} LIMIT 1"
        );
    }

    /**
     * 添加广告设置
     *
     * @param: (array) $bannerData, 推荐的广告内容. 
     * @return: link to Core_Model::add().
     */
    public function addBanner(array $bannerData = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->add($bannerData);
    }

    /**
     * 更新广告设置.
     * 
     * @param: (int) $bannerId, 推荐的广告ID.
     * @param: (array) $bannerData, 推荐的广告内容.
     * @return: link to Core_Model::update().
     */
    public function updateBanner($bannerId, array $bannerData)
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        $this->_id = (int) $bannerId;
        return $this->update($bannerData);
    }

    /**
     * 删除广告
     * 
     * @param: (array) $bannerIds, 删除的广告ID.
     * @return: link to Core_Model::remove().
     */
    public function deleteBanner(array $bannerIds = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->remove($bannerIds);
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
        $now = time();
        $amount = max((int) $amount, 1);
        $sql = 'SELECT `name`, `url`, `picture`, `description`
                FROM `' . $this->getTableName() . "`
                WHERE `start_time` < {$now} AND {$now} < `end_time` ORDER BY `id` DESC LIMIT {$amount}";

        if ($list = $this->getAll($sql))
        {
            foreach ($list as $key => $value)
            {
                $list[$key] = Core_Fun::array2json($value);
            }
            return join(',', (array) $list);
        }
        return '';
    }
}
