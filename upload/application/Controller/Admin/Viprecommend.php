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
class Controller_Admin_Viprecommend extends Core_Controller_Action
{
    //大家都在说Model
    private $_viprecommendModel = null;

    //表格验证的错误信息，临时存放在cookie中.
    private $_errorMessage  = array();

    //网站Base Url
    private $_baseUrl = '/';

    //是否验证微博账号合法性. 
    private $_isValateAccount = true;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->_viprecommendModel = new Model_Viprecommend();    //实例化『大家都在说』Model.

        $this->assign('baseUrl', $this->_baseUrl);
    }

    /**
     * 大家都在说 - 首页.
     */
    public function indexAction()
    {
        $action = $this->getParam('action');
        if ('post' == $action)    //处理提单修改的表单请求
        {
            $config = $this->getParam('config');
            if (isset($config['component.viprecommend']))
            {
                $accountList = $config['component.viprecommend'];
                //验证表单提及的合法性. 
                $validatedAccount = $this->_validateAccount($accountList);
                if (!$validatedAccount)
                {
                    $this->setCookie('errorMessage', serialize($this->_errorMessage));
                    $this->setCookie('accounts', $accountList['people']);
                    $this->showmsg('设置列表出错，请检查您的设置是否正确。', '/admin/viprecommend/index/!');
                }

                //保存大家都在说设置.
                $this->_viprecommendModel->savePeople($validatedAccount);
                $this->setCookie('errorMessage', false);
                $this->setCookie('accounts', false);
                $this->showmsg('您的列表设置成功，正在返回..', '/admin/viprecommend/index');
            }
        }
        else
        {
            //优化用户体验, 以上次提交的数据填充表单(带!标识)
            $inputParams = $this->getParams();
            if (array_key_exists('!', $inputParams) && $this->getCookie('accounts'))
            {
                $this->assign('errorMessage', unserialize($this->getCookie('errorMessage')));
                $this->assign('accounts', $this->getCookie('accounts'));
            }        
        }
        $this->display('admin/viprecommend_index.tpl');
    }

    /**
     * 检验提交的设置表单内容.
     * 
     * @return: (mixed), 检查不通过则返回false, 同时保存错误信息于$this->_errorMessage, 
     *                   否则返回干净化的传入形参. 
     */
    private function _validateAccount($accountList = array())
    {
        //验证账号设置不能为空.
        //根据产品需求, 该设置当不保存时, 则默认『全网用户』.
        if (isset($accountList['people']) && empty($accountList['people']))
        {
            //$this->_errorMessage[] = '您设置的账号列表不能为空';
            return $accountList;
        }

        //过滤尾头的;和空格
        $accounts =& $accountList['people'];    //用引用以便函数返回. 
        $accounts = strip_tags(trim($accounts, " \n\r"));
        $accounts = str_replace(' ', '', $accounts);

        //验证账号是否合法. (该验证若不需要修改成员属性$_isValateAccount为false.)
        if (true === $this->_isValateAccount)
        {
            $pattern = '/^[a-zA-Z][\w\-\_]{5,19}$/i';
            $lists   = preg_split('/[\n\r]+/s', $accounts);
            $lists   = array_unique($lists);  //去重.
            foreach ($lists as $key => $value)
            {
                if (!preg_match($pattern, $value))
                {
                    $this->_errorMessage[] = '请检查您填入的账号是否有误，合法的微博账号应该为: "以字母开头; 6-20位字母、数字、下线线或减号。"';
                    break;
                }
            }
            $accounts = join("\n", $lists);
        }
        return $this->_errorMessage ? false : $accountList;
    }
}
