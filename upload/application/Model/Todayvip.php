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
class Model_Todayvip extends Core_Model
{
    /**
     * 今日名人组前缀.
     * @var: (string)
     */
    const VIP_RECOMMEND_PREFIX = 'component.viprecommend';

    /**
     * 数组中对应的索引.
     * @var: (string)
     */
    const ARRAY_INDEX = 'people';


    /**
     * 组件统一数据导出接口.
     * 
     * <今日名人>Model数据来源于<推荐名人>, 
     * 此Model仅提供基于今日名人组件需求的数据.(随机取出一个账号)
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
    public function __toData($amount = 1)
    {
        $list = Core_Config::get(self::ARRAY_INDEX, self::VIP_RECOMMEND_PREFIX);
        $list = preg_split('/[\n\r]+/s', $list);

        $listCount = count($list);
        if (0 == $listCount)
        {
            return '';
        }

        /**
         * 随机只取出一个元素.
         */
        if ($todayvip = Model_User_Util::getInfos($list))
        {
            shuffle($todayvip);
            return array_slice($todayvip, 0, 1);
        }
        return false;
    }
}
