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
class Model_Ranking extends Core_Model
{
    /**
     * 排行榜组前缀
     * @var: (string)
     */
    const RANKING_PREFIX = 'component.ranking';

    /**
     * 数组中对应的索引
     * $var: (string)
     */
    const ARRAY_INDEX = 'people';

    /**
     * 保存排行榜设置
     * 调用默认配置模块, 保存信息于base_config表中. 
     * 
     * @param: (array) $peopleArray, 排行榜信息.
     * @return: null.
     */
    public function saveRanking(array $peopleArray = array())
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存.
        Core_Config::get(null , self::RANKING_PREFIX);
        Core_Config::add($peopleArray, self::RANKING_PREFIX);
        Core_Config::update(self::RANKING_PREFIX);
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
        $list = Core_Config::get(self::ARRAY_INDEX, self::RANKING_PREFIX);
        $list = preg_split('/[\n\r]+/s', $list);

        $listCount = count($list);
        if (0 == $listCount)
        {
            return '';
        }
        elseif ($listCount > $amount)
        {
            $list = array_slice($list, 0, (int) $amount);
        }

        if ($list && ($list = Model_User_Util::getInfos($list)))
        {
            $sortingArray = array();
            foreach ($list as $key => $value)
            {
                $sortingArray[] = $value;
            }

            /**
             * 冒泡处理排名顺序.
             *
             * @由于是关联数据, 且排序数字处理2维, 无法直接排序. 
             */
            $count = count($sortingArray) - 1;
            for ($i=0; $i<$count; $i++)
            {
                for ($j=$i; $j<$count; $j++)
                {
                    if ($sortingArray[$i]['fansnum'] < $sortingArray[$j+1]['fansnum'])
                    {
                        $tmpStack = $sortingArray[$i];
                        $sortingArray[$i] = $sortingArray[$j+1];
                        $sortingArray[$j+1] = $tmpStack;
                    }
                }
            }
            return $sortingArray;
        }
        return false;
    }
}
