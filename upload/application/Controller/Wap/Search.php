<?php

/**
 * iweibo2.0
 * 
 * wap搜索控制器
 *
 * @author echoyang
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_Search.php 997 2011/6/2 echoyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Search extends Core_Controller_WapAction
{

    private $pageSize = 10;            //每页10条

    //构造函数

    public function preDispatch()
    {
        //个人认证设置
        $this->localAuth = Core_Config::get('localauth', 'certification.info');
        $this->openAuth = Core_Config::get('platformauth', 'certification.info');

        $authtype = array('local' => $this->localAuth, 'platform' => $this->openAuth);
        $this->assign('authtype', $authtype);
    }

    /**
     * 搜索主页
     * 
     */

    public function indexAction()
    {
        //获取话题推荐
        $model = new Model_Hottopic;
        $hotlist = $model->__toData();
        $this->assign('hotlist', $hotlist);

        //获取名人推荐
        $model = new Model_Viprecommend;
        $hotUser = $model->__toData();
        $this->assign('hotuser', $hotUser);

        $this->display('wap/search.tpl');
    }

    /**
     * 搜索广播
     * 
     */
    public function tAction()
    {
        $searchKey = Core_Fun::iurldecode(trim($this->getParam('k')));
        $msglist = array();
        $data = array("title" => "广播搜索结果", "searchkey" => htmlspecialchars($searchKey)); //模版数据
        if (!empty($searchKey))
        {
            $pageNum = Core_Comm_Validator::getNumArg($this->getParam('pagenum'), 1, PHP_INT_MAX, 1);
            $p = array(
                "k" => $searchKey        //搜索关键字
                , "n" => $this->pageSize    //每页显示信息的条数
                , "p" => $pageNum            //页码,从1开始
                , "type" => 1                //0 用户  1 微博  2话题 
            );


            //搜索广播
            $tRet = Core_Open_Api::getClient()->getSearch($p);

            //分页信息
            $addKey = $searchKey ? '/k/' . Core_Fun::iurlencode(($searchKey)) : '';
            $preUrl = '/wap/search/t' . $addKey;
            $pageInfo = $this->setPageInfo($preUrl, $tRet["data"]["totalnum"], $pageNum);
            $this->assign('pageinfo', $pageInfo);

            //模版数据
            $data["tnum"] = $tRet["data"]["totalnum"];
            $data["addkey"] = $addKey;

            $msglist = $tRet["data"]["info"];
            if (is_array($msglist))
            {
                Core_Lib_Base::formatTArr($msglist, 50, 160, $searchKey, true);
            }
        }

        if(empty($pageNum) || $pageNum<2)
        {
            $backurl = Core_Fun::getUrlroot().$preUrl;
            $this->assign('backurl', $backurl);
        }

        $this->assign('msglist', $msglist);
        $this->assign('data', $data);
        $this->assign('searchkey', $data['searchkey']); //更改头部搜索词
        $this->assign('headsearch', 't'); //更改头部模板 地址
        $this->display('wap/search_t.tpl');
    }

    /**
     * 搜索用户
     * 
     */
    public function uAction()
    {
        $searchKey = Core_Fun::iurldecode(trim($this->getParam('k')));
        $msglist = array();
        $data = array("title" => "用户搜索结果", "searchkey" => htmlspecialchars($searchKey)); //模版数据
        if (!empty($searchKey))
        {
            $pageNum = Core_Comm_Validator::getNumArg($this->getParam('pagenum'), 1, PHP_INT_MAX, 1);

            $p = array(
                "k" => $searchKey        //搜索关键字
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
            $addKey = $searchKey ? '/k/' . Core_Fun::iurlencode(($searchKey)) : '';
            $preUrl = '/wap/search/u' . $addKey;
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
                    $u['name_light'] = Core_Lib_Base::highlight($u['name'], $searchKey);
                    $u['nick_light'] = Core_Lib_Base::highlight($u['nick'], $searchKey);
                }
            }
            else
            {
                $data["u"] = NULL;
                $data["unum"] = 0;
            }
        }

        //回调地址
        $pathroot = Core_Fun::getPathroot();
        $backUrl = empty($searchKey) ? '' : '/backurl/' . Core_Fun::iurlencode($pathroot . 'wap/search/u/k/' . $searchKey . '/pagenum/' . $pageNum);
        $this->assign('backurl', $backUrl);

        $this->assign('data', $data);
        $this->assign('searchkey', $data['searchkey']); //更改头部搜索词
        $this->assign('headsearch', 'user'); //更改头部模板 地址
        $this->display('wap/search_u.tpl');
    }

    /**
     * 搜索用户
     * 
     */
    public function tagAction()
    {
        $searchKey = Core_Fun::iurldecode(trim($this->getParam('k')));
        $msglist = array();
        $data = array("title" => "用户搜索结果", "searchkey" => htmlspecialchars($searchKey)); //模版数据
        if (!empty($searchKey))
        {
            $pageNum = Core_Comm_Validator::getNumArg($this->getParam('pagenum'), 1, PHP_INT_MAX, 1);

            $p = array(
                "k" => $searchKey        //搜索关键字
                , "n" => $this->pageSize    //每页显示信息的条数
                , "p" => $pageNum            //页码,从1开始
                , "type" => 3                //0 用户  1 微博  2话题 3tag
            );
            try{
                //搜索
                $tagObj = Model_Tag::singleton();
                $userRet = $tagObj->searchTag($p);
                
                //如果是本地化标签 切本地化标签结果内容为空时候。返回云端标签搜索结果
                $localTag = true;//强制获取本地化标签开关
                if(!Model_Tag::getTagSrc() && empty($userRet["data"]["info"]))
                {
                    $userRet = Model_User_TagOpen::searchTag($p);
                    $localTag = false;
                }                
            }catch(Core_Api_Exception $e){
            }

            //分页信息
            $addKey = $searchKey ? '/k/' . Core_Fun::iurlencode(($searchKey)) : '';
            $preUrl = '/wap/search/tag' . $addKey;
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
                        foreach ($u['tag'] as $v)
                        {
                            $u['tags_light'] .= '<a href="/wap/search/tag/k/' . $v['name'] . '">' . Core_Lib_Base::highlight($v['name'], $searchKey) . '</a> | ';
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

        //回调地址
        $pathroot = Core_Fun::getPathroot();
        $backUrl = empty($searchKey) ? '' : '/backurl/' . Core_Fun::iurlencode($pathroot . 'wap/search/tag/k/' . $searchKey . '/pagenum/' . $pageNum);
        $this->assign('backurl', $backUrl);

        $this->assign('data', $data);
        $this->assign('searchkey', $data['searchkey']); //更改头部搜索词
        $this->assign('headsearch', 'tag'); //更改头部模板 地址
        $this->display('wap/search_tag.tpl');
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