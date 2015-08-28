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
class Controller_Admin_Banner extends Core_Controller_Action
{
    //广告Model.
    private $_bannerModel = null;

    //表格验证的错误信息，临时存放在cookie中.
    private $_errorMessage  = array();

    //网站Base Url
    private $_baseUrl = '/';

    public function __construct($params)
    {
        parent::__construct($params);
        $this->_bannerModel = new Model_Banner();

        $this->assign('baseUrl', $this->_baseUrl);
    }

    /**
     * 广告组件 - 首页
     */
    public function indexAction()
    {
        $bannerList = $this->_bannerModel->getBannerList();

        $this->assign('bannerList', $bannerList);
        $this->display('admin/banner_index.tpl');
    }
    
    /**
     * 广告组件 - 添加页面
     */
    public function addAction()
    {
        $action = $this->getParam('action');
        if ($action && ('post' == $action))
        {
            $bannerData = array(
                'name'        => strip_tags(trim($this->getParam('name'))),
                'picture'     => strip_tags(trim($this->getParam('picture'))),
                'url'         => strip_tags(trim($this->getParam('url'))),
                'description' => strip_tags(trim($this->getParam('description'))),
                'start_time'  => strip_tags(trim($this->getParam('start_time'))),
                'end_time'    => strip_tags(trim($this->getParam('end_time')))
            );

            $validatedBannerData = $this->_validateBanner($bannerData);
            if (!$validatedBannerData) {
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('banner', serialize($bannerData));
                $this->showmsg('添加广告失败，请检查您的内容。', '/admin/banner/add/!');  //在链接后面加个!作为标识, 以便下个请求保存数据.
            }

            $bannerId = $this->_bannerModel->addBanner($validatedBannerData);
            if ($bannerId > 0) {
                $this->setCookie('banner', false);
                $this->setCookie('errorMessage', false);
                $this->showmsg('添加广告成功，正在返回..', '/admin/banner/index');
            }
        }
        else
        {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('banner'))
            {
                $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('banner', unserialize($this->getCookie('banner')));
            }
            $this->assign('_actionName', 'add');
        }
        $this->display('admin/banner_add.tpl');
    }

    /**
     * 广告组件 - 编辑页面
     */
    public function editAction()
    {
        $bannerId = (int) $this->getParam('id');    //获取编辑的广告ID.
        if ($bannerId <= 0)
        {
            $this->showmsg(null, '/admin/banner/index', 0);
        }

        $action = $this->getParam('action');    //检查是否为提交修改的请求, 抑或默认的编辑显示页.
        if ($action && ('post' == $action))
        {
            $bannerData = array(
                'id'          => $bannerId,
                'name'        => strip_tags(trim($this->getParam('name'))),
                'url'         => strip_tags(trim($this->getParam('url'))),
                'picture'     => strip_tags(trim($this->getParam('picture'))),
                'description' => strip_tags(trim($this->getParam('description'))),
                'start_time'  => strip_tags(trim($this->getParam('start_time'))),
                'end_time'    => strip_tags(trim($this->getParam('end_time')))
            );

            $validatedBannerData = $this->_validateBanner($bannerData);    //验证提交的表单内容.
            if (!$validatedBannerData)
            {
                //验证失败, 保存相关内容到cookie中. 
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('banner', serialize($bannerData));
                $this->showmsg('修改广告失败，请检查您的内容。', "/admin/banner/edit/id/{$bannerId}/!");
            }

            //保存修改的广告内容. 
            $updateResult = $this->_bannerModel->updateBanner($bannerId, $validatedBannerData);
            if ($updateResult)
            {
                $this->setCookie('banner', false);
                $this->showmsg('修改广告成功，正在返回..', '/admin/banner/index');
            }
        } else {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('banner'))
            {
                $this->getCookie('errorMessage') && $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('banner', unserialize($this->getCookie('banner')));
            } else {
                $banner = $this->_bannerModel->getBannerById($bannerId);
                if (!$banner)
                {
                    $this->showmsg('您查看的广告信息不存在。', '/admin/banner/index');
                }
                $this->assign('banner', $banner);
            }
        }
        $this->display('admin/banner_edit.tpl');
    }
    

    /**
     * 删除广告
     */
    public function deleteAction()
    {
        $bannerIds = (array) $this->getParam('delete');
        if ($this->_bannerModel->deleteBanner($bannerIds))
        {
            $this->showmsg('已删除所选广告，正在返回..');
        }
        $this->showmsg(null, '/admin/banner/index', 0);
    }


    /**
     * 验证前台传入的表单内容.
     * 
     * @return: (mixed), 如果任一情况不符合, 刚返回false.
     *                   否则返回验证后的数组, 开始和结束时间会被转化为时间戳.
     */
    private function _validateBanner(array $bannerData = array())
    {
        //验证广告名字
        if (!isset($bannerData['name']) || empty($bannerData['name']))
        {
            $this->_errorMessage[] = '广告名字不能为空!';
        }

        //验证广告链接
        if (!isset($bannerData['url']) || empty($bannerData['url']))
        {
            $this->_errorMessage[] = '广告链接不能为空!';
        }

        //验证广告图片链接
        if (!isset($bannerData['picture']) || empty($bannerData['picture']))
        {
            $this->_errorMessage[] = '广告图片链接不能为空!';
        }

        //验证广告开时间时间格式.
        if (!preg_match('/[1-2]\d{3}\-[0-1]?\d\-[0-3]?\d/', $bannerData['start_time']))
        {
            $this->_errorMessage[] = '广告开始时间格式有误!';
        }
        $bannerData['start_time'] = strtotime($bannerData['start_time']);

        //验证广告结束时间格式.
        if (!preg_match('/[1-2]\d{3}\-[0-1]?\d\-[0-3]?\d/', $bannerData['end_time']))
        {
            $this->_errorMessage[] = '广告结束时间格式有误!';
        }
        $bannerData['end_time'] = strtotime($bannerData['end_time']);

        //验证广告结束时间不能早于开始时间. 
        if ($bannerData['start_time'] > $bannerData['end_time'])
        {
            $this->_errorMessage[] = '广告结束时间不能早于开始时间!';
        }
        return $this->_errorMessage ? false : $bannerData;
    }
}
