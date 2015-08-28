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
class Controller_Admin_Brand extends Core_Controller_Action
{
    //品牌Model.
    private $_brandModel = null;

    //表格验证的错误信息，临时存放在cookie中.
    private $_errorMessage  = array();

    //网站Base Url
    private $_baseUrl = '/';

    public function __construct($params)
    {
        parent::__construct($params);
        $this->_brandModel = new Model_Brand();

        $this->assign('baseUrl', $this->_baseUrl);
    }

    /**
     * 品牌组件 - 首页
     */
    public function indexAction()
    {
        $brandList = $this->_brandModel->getBrandList();

        $this->assign('brandList', $brandList);
        $this->display('admin/brand_index.tpl');
    }
    
    /**
     * 品牌组件 - 添加页面
     */
    public function addAction()
    {
        $action = $this->getParam('action');
        if ($action && ('post' == $action))
        {
            $brandData = array(
                'name'        => strip_tags(trim($this->getParam('name'))),
                'picture'     => strip_tags(trim($this->getParam('picture'))),
                'description' => strip_tags(trim($this->getParam('description'))),
                'link'        => strip_tags(trim($this->getParam('link')))
            );

            $validatedBrandData = $this->_validateBrand($brandData);
            if (!$validatedBrandData) {
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('brand', serialize($brandData));
                $this->showmsg('添加品牌失败，请检查您的内容。', '/admin/brand/add/!');  //在链接后面加个!作为标识, 以便下个请求保存数据.
            }

            $brandId = $this->_brandModel->addBrand($validatedBrandData);
            if ($brandId > 0) {
                $this->setCookie('brand', false);
                $this->setCookie('errorMessage', false);
                $this->showmsg('添加品牌成功，正在返回..', '/admin/brand/index');
            }
        }
        else
        {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('brand'))
            {
                $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('brand', unserialize($this->getCookie('brand')));
            }
            $this->assign('_actionName', 'add');
            $this->display('admin/brand_add.tpl');
        }
    }

    /**
     * 品牌组件 - 编辑页面
     */
    public function editAction()
    {
        $brandId = (int) $this->getParam('id');    //获取编辑的品牌ID.
        if ($brandId <= 0)
        {
            $this->showmsg(null, '/admin/brand/index', 0);
        }

        $action = $this->getParam('action');    //检查是否为提交修改的请求, 抑或默认的编辑显示页.
        if ($action && ('post' == $action))
        {
            $brandData = array(
                'id'          => $brandId,
                'name'        => strip_tags(trim($this->getParam('name'))),
                'picture'     => strip_tags(trim($this->getParam('picture'))),
                'description' => strip_tags(trim($this->getParam('description'))),
                'link'        => strip_tags(trim($this->getParam('link')))
            );

            $validatedBrandData = $this->_validateBrand($brandData);    //验证提交的表单内容.
            if (!$validatedBrandData)
            {
                //验证失败, 保存相关内容到cookie中. 
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('brand', serialize($brandData));
                $this->showmsg('修改品牌失败，请检查您的内容。', '/admin/brand/edit/id/{$brandId}/!');
            }

            //保存修改的品牌内容. 
            $updateResult = $this->_brandModel->updateBrand($brandId, $validatedBrandData);
            if ($updateResult)
            {
                $this->setCookie('brand', false);
                $this->showmsg('修改品牌成功，正在返回..', '/admin/brand/index');
            }
        } else {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('brand'))
            {
                $this->getCookie('errorMessage') && $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('brand', unserialize($this->getCookie('brand')));
            } else {
                $brand = $this->_brandModel->getBrandById($brandId);
                if (!$brand)
                {
                    $this->showmsg('您查看的品牌信息不存在。', '/admin/brand/index');
                }
                $this->assign('brand', $brand);
            }
        }
        $this->display('admin/brand_edit.tpl');
    }
    

    public function deleteAction()
    {
        $brandIds = (array) $this->getParam('delete');
        if ($this->_brandModel->deleteBrand($brandIds))
        {
            $this->showmsg('已删除所选品牌，正在返回..');
        }
        $this->showmsg(null, '/admin/brand/index', 0);
    }


    /**
     * 验证前台传入的表单内容.
     * 
     * @return: (mixed), 如果任一情况不符合, 刚返回false.
     *                   否则返回验证后的数组, 开始和结束时间会被转化为时间戳.
     */
    private function _validateBrand(array $brandData = array())
    {
        //验证品牌名字
        if (!isset($brandData['name']) || empty($brandData['name']))
        {
            $this->_errorMessage[] = '品牌名字不能为空!';
        }

        //验证品牌图片链接
        if (!isset($brandData['picture']) || empty($brandData['picture']))
        {
            $this->_errorMessage[] = '品牌图片链接不能为空!';
        }

        //验证品牌链接
        if (!isset($brandData['link']) || empty($brandData['link']))
        {
            $this->_errorMessage[] = '品牌链接不能为空!';
        }

        return $this->_errorMessage ? false : $brandData;
    }
}
