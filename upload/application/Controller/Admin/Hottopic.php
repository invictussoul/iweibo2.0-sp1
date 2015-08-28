<?php
/**
 * iWeibo
 *
 * LICENSE
 *
 * @homepage: http://open.t.qq.com/apps/iweibo 
 * @category: iWeibo
 * @package: Backend admin controller
 * @copyright:
 * @license 
 * @version: 2.0
 * @author: Bluexchen<Bluexchen@tencent.com>
 */
class Controller_Admin_Hottopic extends Core_Controller_Action
{
    //话题Model
    private $_hotTopicModel = null;

    //表格验证的错误信息，临时存放在cookie中.
    private $_errorMessage  = array();

    //网站Base Url
    private $_baseUrl = '/';

    public function __construct($params)
    {
        parent::__construct($params);
        $this->_hotTopicModel = new Model_Hottopic();

        $this->assign('baseUrl', $this->_baseUrl);
    }

    /**
     * 话题组件 - 首页
     */
    public function indexAction()
    {
        $hotTopic = $this->_hotTopicModel->getHotTopics();

        $this->assign('_actionName', 'index');
        $this->assign('hotTopics', $hotTopic);
        $this->display('admin/hottopic_index.tpl');
    }

    /**
     * 话题组件 - 添加页面
     */
    public function addAction()
    {
        $action = $this->getParam('action');
        if ($action && ('post' == $action))
        {
            $topicData = array(
                'name'        => strip_tags(trim($this->getParam('name'))),
                'description' => strip_tags(trim($this->getParam('description'))),
                'picture'     => strip_tags(trim($this->getParam('picture'))),
                'picture2'    => strip_tags(trim($this->getParam('picture2')))
            );

            $validateTopicData = $this->_validateHotTopic($topicData);
            if (!$validateTopicData)
            {
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('hotTopic', serialize($topicData));
                $this->showmsg('添加热点话题失败，请检查您的内容。', '/admin/hottopic/add/!');    //在链接后面加个!作为标识, 以便下个请求保存数据.
            }

            $topicId = $this->_hotTopicModel->addHotTopic($validateTopicData);
            if ($topicId > 0)
            {
                $this->setCookie('hotTopic', false);
                $this->showmsg('添加热点话题成功，正在返回..', '/admin/hottopic/index');
            }
        }
        else
        {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('hotTopic'))
            {
                $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('hotTopic', unserialize($this->getCookie('hotTopic')));
            }
        }
        $this->display('admin/hottopic_add.tpl');
    }

    /**
     * 话题组件 - 编辑页面
     */
    public function editAction()
    {
        $hotTopicId = (int) $this->getParam('id');    //获取编辑的广告ID.
        if ($hotTopicId <= 0)
        {
            $this->showmsg(null, '/admin/hottopic/index');
        }

        $action = $this->getParam('action');    //检查是否为提交修改的请求, 抑或默认的编辑显示页.
        if ($action && ('post' == $action))
        {
            $topicData = array(
                'id'          => $hotTopicId,
                'name'        => strip_tags(trim($this->getParam('name'))),
                'description' => strip_tags(trim($this->getParam('description'))),
                'picture'     => strip_tags(trim($this->getParam('picture'))),
                'picture2'    => strip_tags(trim($this->getParam('picture2')))
            );

            $validateTopicData = $this->_validateHotTopic($topicData);
            if (!$validateTopicData)
            {
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('hotTopic', serialize($topicData));
                $this->showmsg('添加热点话题失败，请检查您的内容。', '/admin/hottopic/edit/id/{$hotTopicId}/!');    //在链接后面加个!作为标识, 以便下个请求保存数据.
            }

            //保存修改的话题内容. 
            $topicId = $this->_hotTopicModel->updateHotTopic($hotTopicId, $validateTopicData);
            if ($topicId > 0)
            {
                $this->showmsg('修改热点话题成功，正在返回..', '/admin/hottopic/index');
            }
        }
        else
        {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('hotTopic'))
            {
                $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('hotTopic', unserialize($this->getCookie('hotTopic')));
            }
            else
            {
                $hotTopic = $this->_hotTopicModel->getHotTopicById($hotTopicId);
                if (!$hotTopic)
                {
                    $this->showmsg('您查看的话题ID不存在。', '/admin/hottopic/index');
                }
                $this->assign('hotTopic', $hotTopic);
            }            
        }
        $this->display('admin/hottopic_edit.tpl');
    }

    /**
     * 删除话题
     */
    public function deleteAction()
    {
        $hotTopicIds = (array) $this->getParam('delete');
        if (empty($hotTopicIds))
        {
            $this->showmsg('请选择您要删除的话题，正在返回..');
        }
        if ($this->_hotTopicModel->deleteHotTopic($hotTopicIds))
        {
            $this->showmsg('已删除所选话题，正在返回..');
        }
        $this->showmsg(null, '/admin/nottopic/index', 0);
    }

    /**
     * 检验提交的设置表单内容.
     * 
     * @return: (mixed), 检查不通过则返回false, 同时保存错误信息于$this->_errorMessage, 
     *                   否则返回干净化的传入形参. 
     */
    private function _validateHotTopic(array $hotTopic = array())
    {
        //验证话题名字.
        if (!isset($hotTopic['name']) || empty($hotTopic['name']))
        {
            $this->_errorMessage[] = '话题名字不能为空!';
        }

        //验证图片链接地址
        if (!isset($hotTopic['picture']) || empty($hotTopic['picture']))
        {
            $this->_errorMessage[] = '图片链接1不能为空!';
        }

        //验证图片链接地址
        if (!isset($hotTopic['picture2']) || empty($hotTopic['picture2']))
        {
            $this->_errorMessage[] = '图片链接2不能为空!';
        }
        return $this->_errorMessage ? false : $hotTopic;
    }
}
