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
class Model_Brand extends Core_Model
{
    /**
     * Database table name.
     * @var (string)
     */
    protected $_tableName = 'component_brand';

    /**
     * Database table columns.
     * @var (array), table fields.
     */
    protected $_fields = array('id', 'name', 'description', 'picture', 'link');

    public function getBrandList()
    {
        return $this->getList('`id` ASC', '`id`, `name`, `description`, `picture`, `link`');
    }

    /**
     * 根据品牌ID, 获取对应的品牌设置内容.
     * 
     * @param: (int) $brandId, 品牌对应的ID.
     * @return: (mixed), 返回ID对应的品牌设置内容, 或为false.
     */
    public function getBrandById($brandId)
    {
        $brandId = (int) $brandId;
        return Core_Db::fetchOne(
            "SELECT `id`, `name`, `description`, `picture`, `link`
             FROM `##__component_brand`
             WHERE `id` = {$brandId} LIMIT 1"
        );
    }

    /**
     * 添加品牌
     *
     * @param: (array) $brandData, 推荐品牌的信息. 
     * @return: (mixed) link to Core_Mode::add().
     */
    public function addBrand(array $brandData = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->add($brandData);
    }

    /**
     * 更新品牌
     * 
     * @param: (int) $brandId, 推荐品牌ID.
     * @rapram: (array) $brandData, 推荐的品牌信息.
     * @return: (mixed) link to Core_Model::update().
     */
    public function updateBrand($brandId, array $brandData)
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        $this->_id = (int) $brandId;
        return $this->update($brandData);
    }

    /**
     * 删除品牌
     *
     * @param: (array) $brandIds, 删除的品牌ID.
     * @return: link to Core_Model::remove().
     */
    public function deleteBrand(array $brandIds = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        return $this->remove($brandIds);
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
        $sql = 'SELECT `id`, `name`, `description`, `picture`, `link`
                FROM `' . $this->getTableName() . "`
                WHERE 1 ORDER BY `id` DESC LIMIT {$amount}";

        $list = $this->getAll($sql);
        if ($list)
        {
            $accounts = array();
            foreach ($list as $key => $value)
            {
                $list[$value['name']] = $value;
                unset($list[$key]);
                $accounts[] = $value['name'];
            }
            $accounts = Model_User_Util::getInfos($accounts); 
            foreach ($accounts as $key => $value)
            {
                if (isset($list[$key]))
                {
                    $accounts[$key] = array_merge((array) $accounts[$key], (array) $list[$key]);
                }
            }
            return $accounts;
        }
        return '';
    }
}
