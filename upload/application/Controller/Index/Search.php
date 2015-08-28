<?php

/**
 * iweibo2.0
 * 
 * 搜索模块控制器
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Search.php 2011-05-22
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Search extends Core_Controller_TAction
{

    private $pageSize = 15;            //每页15条

    //构造函数

    public function preDispatch()
    {
        parent::preDispatch();
        
        $this->searchKey = trim($this->getParam('k'));
        
        $actionName = $this->getActionName();
        $this->assign('headsearch', $actionName); //更改头部模板 地址
        if(empty($this->searchKey) && 'tag'!=$actionName)
        {
            $this->assign('data', array('title'=>'综合搜索'));
            $this->display('search/null.tpl');
            exit;
        }

        //个人认证设置
        $this->localAuth = Core_Config::get('localauth', 'certification.info');
        $this->openAuth = Core_Config::get('platformauth', 'certification.info');

        $authtype = array('local' => $this->localAuth, 'platform' => $this->openAuth);
        $this->assign('authtype', $authtype);


        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(10, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(10, 'right'));
    }

    /**
     * 综合搜索
     *
     */
    public function allAction()
    {
        $msglist = $data = array();
        //模版数据
        $data = array("title" => "综合搜索结果", "searchkey" => htmlspecialchars($this->searchKey));
        if (!empty($this->searchKey))
        {
            $pageNum = Core_Comm_Validator::getNumArg($this->getParam('pagenum'), 1, PHP_INT_MAX, 1);
            $p = array(
                "k" => $this->searchKey            //搜索关键字
                , "n" => 3                    //每页显示信息的条数
                , "p" => $pageNum            //页码,从1开始
                , "type" => 0                //0 用户  1 微博  2话题 
            );

            //搜索用户
            $userRet = Core_Open_Api::getClient()->getSearch($p);

            if(!empty($userRet['data']['info']))
            {
                foreach($userRet['data']['info'] AS &$v)
                {
                      $uInfo = Model_User_Util::getLocalInfo($v['name']);
                      !empty($uInfo['nick']) && $v['nick'] = $uInfo['nick'];//有本地昵称就覆盖
                       if($this->localAuth && !empty($uInfo['localauth']) || $this->openAuth && !empty($v['isvip']))
                      {
                         $v['is_auth'] = true;
                       }
                }
            }


            //搜索微博
            $p["n"] = $this->pageSize;
            $p["type"] = 1;
            $tRet = Core_Open_Api::getClient()->getSearch($p);

            //分页信息
            $addKey = $this->searchKey ? '/k/' . Core_Fun::iurlencode($this->searchKey) : '';
            $preUrl = '/search/all' . $addKey;
            $pageInfo = $this->setPageInfo($preUrl, $tRet["data"]["totalnum"], $pageNum);
            $this->assign('pageinfo', $pageInfo);

            //模版数据
            $data["addkey"] = $addKey;
            $data["unum"] = $userRet["data"]["totalnum"];
            $data["u"] = $userRet["data"]["info"];
            if (is_array($data["u"]))
            {
                foreach ($data["u"] as &$u)
                {
                    $u = Core_Lib_Base::formatU($u, 50);
                    $u['name_light'] = Core_Lib_Base::highlight($u['name'], $this->searchKey);
                    $u['nick_light'] = Core_Lib_Base::highlight($u['nick'], $this->searchKey);
                }
            }
            else
            {
                $data["u"] = NULL;
                $data["unum"] = 0;
            }
            $data["tnum"] = $tRet["data"]["totalnum"];
            $msglist = $tRet["data"]["info"];
            if (is_array($msglist))
            {
                Core_Lib_Base::formatTArr($msglist, 50, 160, $this->searchKey, true);
            }
        }
        $this->assign('msglist', $msglist);
        $this->assign('data', $data);
        $this->assign('searchkey', $data['searchkey']); //更改头部搜索词
        $page = empty($data["unum"])&&empty($data["tnum"]) ? 'null': 'all';
        $this->display('search/'.$page.'.tpl');        
    }

    /**
     * tag搜索
     *
     */
    public function tagAction()
    {
        $msglist = array();
        $data = array("title" => "用户搜索结果", "searchkey" => htmlspecialchars($this->searchKey)); //模版数据
        if (!empty($this->searchKey))
        {
            $pageNum = Core_Comm_Validator::getNumArg($this->getParam('pagenum'), 1, PHP_INT_MAX, 1);
            $p = array(
                "k" => $this->searchKey        //搜索关键字
                , "n" => $this->pageSize    //每页显示信息的条数
                , "p" => $pageNum            //页码,从1开始
                , "type" => 3                //0 用户  1 微博  2话题 3tag
            );
            

            //系统设置标签来源
            $sysTagSrc = Model_Tag::getTagSrc();
            $this->assign('syssrc', $sysTagSrc);

            //用户要求tag来源
            $userTagSrc = $this->getParam('src');
            $userTagSrc = ($sysTagSrc==1)?1:(($userTagSrc==1)?1:0); //系统在平台化的情况下，可以用户选择tag消息来源
            $this->assign('usersrc', $userTagSrc);
            
            try{
                //$userTagSrc==0 搜索用户平台
                if(!$userTagSrc)
                {
                  $localTag = true;//需要获取用户本地tag
                  $userRet = Model_User_TagLocal::searchTag($p);
                }
                else
                {
                  $userRet = Model_User_TagOpen::searchTag($p);
                  $localTag = false;
                }

            }catch(Core_Api_Exception $e){
            }
            
           
            empty($userRet["data"]["totalnum"]) && $userRet["data"]["totalnum"]=0;
            empty($userRet["data"]["info"]) && $userRet["data"]["info"]=array();
            
            //分页信息
            $addKey = $this->searchKey ? '/k/' . Core_Fun::iurlencode(($this->searchKey)) : '';
            $preUrl = '/search/tag/src/'.$userTagSrc. $addKey;
            $pageInfo = $this->setPageInfo($preUrl, $userRet["data"]["totalnum"], $pageNum);
            $this->assign('pageinfo', $pageInfo);

            //模版数据
            $data["addkey"] = $addKey;
            $data["unum"] = $userRet["data"]["totalnum"];
            $data["u"] = $userRet["data"]["info"];

            if (is_array($data["u"]))
            {

                foreach ($data["u"] as &$u)
                {
                    $u['head'] = Core_Lib_Base::formatHead( $u['head'] , 50);
                    $u['tags_light'] = '';
                    if ($u['tag'])
                    {
                        $tag = $u['tag'];
                        foreach ($tag AS $tagv)
                        {
                            $u['tags_light'] .= '<a href="/search/tag/src/'.$userTagSrc.'/k/' . $tagv['name'] . '">' . Core_Lib_Base::highlight($tagv['name'], $this->searchKey) . '</a> | ';
                        }
                    }
                    $u['tags_light'] && $u['tags_light'] = substr($u['tags_light'], 0, -2);
                }
                             
            }
            else
            {
                $data["u"] = NULL;
                $data["unum"] = 0;
            }
        }
        $this->assign('data', $data);
        $this->assign('searchkey', $data['searchkey']); //更改头部搜索词
        $this->display('search/tag.tpl');
    }

    /**
     * 用户搜索
     *
     */
    public function userAction()
    {
        $msglist = array();
        $data = array("title" => "用户搜索结果", "searchkey" => htmlspecialchars($this->searchKey)); //模版数据
        if (!empty($this->searchKey))
        {
            $pageNum = Core_Comm_Validator::getNumArg($this->getParam('pagenum'), 1, PHP_INT_MAX, 1);

            $p = array(
                "k" => $this->searchKey        //搜索关键字
                , "n" => $this->pageSize    //每页显示信息的条数
                , "p" => $pageNum            //页码,从1开始
                , "type" => 0                //0 用户  1 微博  2话题 
            );

            //搜索用户
            $userRet = Core_Open_Api::getClient()->getSearch($p);

            if(!empty($userRet['data']['info']))
            {
                foreach($userRet['data']['info'] AS &$v)
                {
                      $uInfo = Model_User_Util::getLocalInfo($v['name']);
                      !empty($uInfo['nick']) && $v['nick'] = $uInfo['nick'];//有本地昵称就覆盖
                       if($this->localAuth && !empty($uInfo['localauth']) || $this->openAuth && !empty($v['isvip']))
                      {
                         $v['is_auth'] = true;
                       }
                }
            }


            //分页信息
            $addKey = $this->searchKey ? '/k/' . Core_Fun::iurlencode(($this->searchKey)) : '';
            $preUrl = '/search/user' . $addKey;
            $pageInfo = $this->setPageInfo($preUrl, $userRet["data"]["totalnum"], $pageNum);
            $this->assign('pageinfo', $pageInfo);

            //模版数据
            $data["addkey"] = $addKey;
            $data["unum"] = $userRet["data"]["totalnum"];
            $data["u"] = $userRet["data"]["info"];
            if (is_array($data["u"]))
            {
                foreach ($data["u"] as &$u)
                {
                    $u = Core_Lib_Base::formatU($u, 50);
                    $u['name_light'] = Core_Lib_Base::highlight($u['name'], $this->searchKey);
                    $u['nick_light'] = Core_Lib_Base::highlight($u['nick'], $this->searchKey);
                }
            }
            else
            {
                $data["u"] = NULL;
                $data["unum"] = 0;
            }
        }
        $this->assign('data', $data);

        $this->assign('searchkey', $data['searchkey']); //更改头部搜索词
        
        $page = empty($data["unum"])? 'null': 'user';
        $this->display('search/'.$page.'.tpl');                
    }

    /**
     * 微博广播搜索
     *
     */
    public function tAction()
    {

        $msglist = array();
        $data = array("title" => "广播搜索结果", "searchkey" => htmlspecialchars($this->searchKey)); //模版数据
        if (!empty($this->searchKey))
        {
            $pageNum = Core_Comm_Validator::getNumArg($this->getParam('pagenum'), 1, PHP_INT_MAX, 1);
            $p = array(
                "k" => $this->searchKey        //搜索关键字
                , "n" => $this->pageSize    //每页显示信息的条数
                , "p" => $pageNum            //页码,从1开始
                , "type" => 1                //0 用户  1 微博  2话题 
            );


            //搜索广播
            $tRet = Core_Open_Api::getClient()->getSearch($p);

            //分页信息
            $addKey = $this->searchKey ? '/k/' . Core_Fun::iurlencode(($this->searchKey)) : '';
            $preUrl = '/search/t' . $addKey;
            $pageInfo = $this->setPageInfo($preUrl, $tRet["data"]["totalnum"], $pageNum);
            $this->assign('pageinfo', $pageInfo);

            //模版数据
            $data["tnum"] = $tRet["data"]["totalnum"];
            $data["addkey"] = $addKey;

            $msglist = $tRet["data"]["info"];
            if (is_array($msglist))
            {
                Core_Lib_Base::formatTArr($msglist, 50, 160, $this->searchKey, true);
            }
        }

        $this->assign('msglist', $msglist);
        $this->assign('data', $data);
        $this->assign('searchkey', $data['searchkey']); //更改头部搜索词

        $page = empty($data["tnum"])? 'null': 't';
        $this->display('search/'.$page.'.tpl');             
    }

    /**
     * 设置上一页下一页信息
     *
     */
    private function setPageInfo($preUrl, $totalNum, $curPageNum)
    {
        //上一页下一页
        $frontUrl = $nextUrl = "";
        $totalnum = intval($totalNum);
        $allPageNum = intval($totalnum / $this->pageSize);
        if ($totalnum % $this->pageSize > 0)
        {
            $allPageNum++;
        }
        if ($curPageNum < $allPageNum)
        {
            $nextPageNum = $curPageNum + 1;
            $nextUrl = Core_Fun::getParaUrl($preUrl, array("pagenum" => $curPageNum + 1));
        }
        if ($curPageNum > 1)
        {
            $frontUrl = Core_Fun::getParaUrl($preUrl, array("pagenum" => $curPageNum - 1));
        }
        return array("fronturl" => $frontUrl, "nexturl" => $nextUrl);
    }

}

?>
