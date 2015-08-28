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
class Model_Allsaying extends Core_Model
{
    //大家都在说组前缀
    const ALL_SAYING_PREFIX = 'component.allsaying';

    //数组中对应的索引
    const ARRAY_INDEX = 'people';

    /**
     * 保存大家都在设置
     * 
     * @desc: 调用默认配置模块, 保存信息于base_config表中. 
     */
    public function savePeople(array $peopleArray = array())
    {
        Core_Config::get(null, self::ALL_SAYING_PREFIX);
        Core_Config::add($peopleArray, self::ALL_SAYING_PREFIX);
        Core_Config::update(self::ALL_SAYING_PREFIX);
    }
    
    public function __toData()
    {
        return Core_Config::get(self::ARRAY_INDEX, self::ALL_SAYING_PREFIX);
    }
}
