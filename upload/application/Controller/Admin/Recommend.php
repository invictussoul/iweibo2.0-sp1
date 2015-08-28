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
class Controller_Admin_Recommend extends Core_Controller_Action
{
    /**
     * Recommend Model.
     */
    private $_recommendModel = null;

    private $_errorMessage   = array();

    //网站Base Url
    private $_baseUrl = '/';

    public function __construct($params)
    {
        parent::__construct($params);
        $this->_recommendModel = new Model_Recommend();

        $this->assign('baseUrl', $this->_baseUrl);
    }

    /**
     * 推荐收听 - 首页
     */
    public function indexAction()
    {
        $recommendList = $this->_recommendModel->getRecommendList();

        $this->assign('recommendList', $recommendList);
        $this->display('admin/recommend_index.tpl');
    }
    
    /**
     * 推荐收听 - 添加页面
     */
    public function addAction()
    {
        $action = $this->getParam('action');
        if ($action && ('post' == $action))
        {
            $recommenderData = array(
                'account'     => strip_tags(trim($this->getParam('account'))),
                'description' => strip_tags(trim($this->getParam('description')))
            );

            $validatedRecommenderData = $this->_validateRecommender($recommenderData);
            if (!$validatedRecommenderData)
            {
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('recommender', serialize($recommenderData));
                $this->showmsg('添加推荐人失败，请检查您的内容。', '/admin/recommend/add/!');  //在链接后面加个!作为标识, 以便下个请求保存数据.
            }

            //保存推荐收听. 
            $recommenderId = $this->_recommendModel->addRecommender($validatedRecommenderData);
            if ($recommenderId > 0)
            {
                $this->setCookie('recommender', false);
                $this->showmsg('添加推荐人成功，正在返回..', '/admin/recommend/index');
            }
        }
        else
        {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('recommender'))
            {
                $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('recommender', unserialize($this->getCookie('recommender')));
            }
        }
        $this->display('admin/recommend_add.tpl');
    }

    /**
     * 推荐收听 - 编辑页面
     */
    public function editAction()
    {
        $recommenderId = (int) $this->getParam('id');    //获取编辑的推荐人ID.
        if ($recommenderId <= 0)
        {
            $this->showmsg(null, '/admin/recommend/index', 0);
        }

        $action = $this->getParam('action');    //检查是否为提交修改的请求, 抑或默认的编辑显示页.
        if ($action && ('post' == $action))
        {
            $recommenderData = array(
                'id'          => $recommenderId,
                'account'     => strip_tags(trim($this->getParam('account'))),
                'description' => strip_tags(trim($this->getParam('description'))),
            );
            
            $validatedRecommenderData = $this->_validateRecommender($recommenderData);
            if (!$validatedRecommenderData)
            {
                $this->setCookie('errorMessage', serialize($this->_errorMessage));
                $this->setCookie('recommender', serialize($recommenderData));
                $this->showmsg('添加推荐人失败，请检查您的内容。', '/admin/recommend/add/!');  //在链接后面加个!作为标识, 以便下个请求保存数据.
            }

            //保存修改的推荐人内容. 
            $updateResult = $this->_recommendModel->updateRecommender($recommenderId, $validatedRecommenderData);
            if ($updateResult)
            {
                $this->setCookie('recommender', false);
                $this->showmsg('修改推荐人成功，正在返回..', '/admin/recommend/index');
            }
        }
        else
        {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('recommender'))
            {
                $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('recommender', unserialize($this->getCookie('recommender')));
            }
            else
            {
                $recommender = $this->_recommendModel->getRecommenderById($recommenderId);
                if (!$recommender)
                {
                    $this->showmsg('您查看的推荐人信息不存在。', '/admin/recommend/index');
                }
                $this->assign('recommender', $recommender);
            }
        }
        $this->display('admin/recommend_edit.tpl');
    }
    

    /**
     * 删除推荐收听
     */
    public function deleteAction()
    {
        $recommendIds = (array) $this->getParam('delete');
        if ($this->_recommendModel->deleteRecommender($recommendIds))
        {
            $this->showmsg('已删除所选推荐收听，正在返回..');
        }
        $this->showmsg(null, '/admin/recommend/index', 0);
    }


    /**
     * 检验提交的设置表单内容.
     * 
     * @return: (mixed), 检查不通过则返回false, 同时保存错误信息于$this->_errorMessage, 
     *                   否则返回干净化的传入形参. 
     */
    private function _validateRecommender(array $recommender = array())
    {
        //验证账号是否合法. 
        $pattern = '/^[a-zA-Z][\w\-\_]{5,19}$/i';
        if (!preg_match($pattern, $recommender['account']))
        {
            $this->_errorMessage[] = '请检查您填入的账号是否有误，合法的微博账号应该为: "以字母开头; 6-20位字母、数字、下线线或减号。"';
        }
        return $this->_errorMessage ? false : $recommender;
    }
}
