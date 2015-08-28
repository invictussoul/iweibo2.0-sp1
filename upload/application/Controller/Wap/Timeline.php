<?php
/**
 * iweibo2.0
 * 
 * wap时间线父类控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_Timeline.php 2011-05-30 15:32:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Timeline extends Core_Controller_WapAction
{

    /**
     * 填充tbody
     * 
     */
    protected function getBody($type, $name = '')
    {
        //获取填充tbody
        $this->getMsg($type, $name);
    }

    /**
     * 从平台获取消息
     * 
     * @param string $type
     * @param string $name
     * @return string
     */
    protected function getMsg($type, $name = '')
    {
        //Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
        $f = Core_Comm_Validator::getNumArg($this->getParam("f"), 0, 2, 0);
        //每次请求记录的条数（1-20条）
        $num = Core_Comm_Validator::getNumArg($this->getParam("num"), 1, 30, 10);
        //本页起始时间（第一页 0，继续：根据返回记录时间决定）
        $t = Core_Comm_Validator::getNumArg($this->getParam("t"), 0, PHP_INT_MAX, 0);
        //当前页最后一条记录，用用精确翻页用
        $l = Core_Comm_Validator::getTidArg($this->getParam("lid"), "0");
        //type 拉取类型, 1 原创广播  2 转载 8 对话  16 空回 32  提及 64 评论
        //如需拉取多个类型请|上(1|2) 得到3，type=3即可,填零表示拉取所有类型
        $utype = Core_Comm_Validator::getNumArg($this->getParam("utype"), 0, 64, 0);
        //Contenttype:内容过滤 填零表示所有类型 1-带文本 2-带链接 4图片 8-带视频 0x10-带音频
        $ctype = Core_Comm_Validator::getNumArg($this->getParam("ctype"), 0, 16, 0);
        //初始化open client
        $client = Core_Open_Api::getClient();
        switch($type)
        {
            case 'mine': //获取我的广播                
                //type:0 提及我的, other 我广播的
                $p = array("type" => 1, "f" => $f, "n" => $num, "t" => $t, "l" => $l, 
                "utype" => $utype, "ctype" => $ctype);
                $openmsg = $client->getMyTweet($p);
                break;
            case 'at': //获取提到我的
                //type:0 提及我的, other 我广播的
                $p = array("type" => 0, "f" => $f, "n" => $num, "t" => $t, "l" => $l, 
                "utype" => $utype, "ctype" => $ctype);
                $openmsg = $client->getMyTweet($p);
                Core_Lib_Base::clearNewMsgInfo(6);
                break;
            case 'favor':
                //获取我的收藏
                $p = array("type" => 0, "f" => $f, "n" => $num, "t" => $t, "l" => 0);
                $openmsg = $client->getFav($p);
                //添加收藏标志
                if(is_array($openmsg["data"]["info"]))
                {
                    foreach($openmsg["data"]["info"] as &$t)
                    {
                        $t["isfav"] = true;
                    }
                }
                break;
            case 'guest': //客人
                $p = array("name" => $name, "f" => $f, "n" => $num, "t" => $t, "l" => $l, 
                "utype" => $utype, "ctype" => $ctype);
                $openmsg = $client->getTimeline($p);
                break;
            case 'public': //广播大厅
                $pos = Core_Comm_Validator::getNumArg($this->getParam("pos"), 0, PHP_INT_MAX, 0);
                $p = array("p" => $pos, "f" => $f, "n" => $num);
                $openmsg = $client->getPublic($p);
                break;
            case 'city': //同城广播
                $pos = Core_Comm_Validator::getNumArg($this->getParam("pos"), 0, PHP_INT_MAX, 0);
                $p = array("p" => $pos, "f" => $f, "n" => $num, "city" => $this->userInfo['city_code'], 
                "province" => $this->userInfo['province_code'], "country" => $this->userInfo['country_code']);
                $openmsg = $client->getArea($p);
                break;
            case 'index': //我的主页                
                $p = array("f" => $f, "n" => $num, "t" => $t, "l" => $l, "utype" => $utype, 
                "ctype" => $ctype);
                //本地关系链
                $openmsg = Model_Friend::singleton()->getTimeline($p, $this->userInfo['name']);
                break;
        }
        
        $msglist = $openmsg['data']['info'];
        
        $msglist and Core_Lib_Base::formatTArr($msglist);
        
        $hasnext = $openmsg["data"]["hasnext"];
        
        $this->assign('msglist', $msglist);
        $this->assign('hasnext', $hasnext);
    }
}