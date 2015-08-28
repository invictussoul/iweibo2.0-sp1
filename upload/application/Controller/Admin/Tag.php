<?php
/**
 * 标签管理
 *
 * @author lvfeng
 */
class Controller_Admin_Tag extends Core_Controller_Action
{
	/**
	 * 标签管理
	 */
	public function manageAction()
	{
		//queryString加密码
		$securecode = 'admintagmanage';
		$tagModel = new Model_Mb_Tag();
		if(trim($this->getParam('action'))=='manage')
		{
			$deleteids = is_array($this->getParam('deleteids')) ? $this->getParam('deleteids') : array();
			foreach ($deleteids as $id)
			{
				$tagModel->deleteTagById(intval($id));
			}
			
			$tagids = is_array($this->getParam('tagid')) ? $this->getParam('tagid') : array();
			foreach ($tagids as $tagid)
			{
				$tagid = intval($tagid);
				$status = intval($this->getParam('status_'.$tagid));
				$tagname = trim($this->getParam('tagname_'.$tagid));
				$tagcolor = trim($this->getParam('tagcolor_'.$tagid));
				!empty($status) && $tagModel->editTagInfo(array('id'=>$tagid, 'visible'=>$status,'tagname'=>$tagname,'color'=>$tagcolor));
			}
			
			$this->showmsg('标签列表更新成功');
		}
		//从queryString取得数据
		$conditionstr = trim($this->getParam('conditions'));
		$code = trim($this->getParam('code'));
		if(!empty($code) && strlen($conditionstr) > 0 && md5($conditionstr.$securecode)==$code) 
		{
			$tmpconditions = explode('||', $conditionstr);
			$conditions['tagname'] = trim($tmpconditions[0]);
			$conditions['usenumstart'] = strlen(trim($tmpconditions[1])) > 0 ? intval(trim($tmpconditions[1])) : '';
			$conditions['usenumend'] = strlen(trim($tmpconditions[2])) > 0 ? intval(trim($tmpconditions[2])) : '';
			$conditions['status'] = intval($tmpconditions[3]);
		}
		else
		{
			$conditions['tagname'] = trim($this->getParam('tagname'));
			$conditions['usenumstart'] = strlen(trim($this->getParam('usenumstart'))) > 0 ? intval($this->getParam('usenumstart')) : '';
			$conditions['usenumend'] = strlen(trim($this->getParam('usenumend'))) > 0 ? intval($this->getParam('usenumend')) : '';
			$conditions['status'] = intval($this->getParam('status'));
		}
		$whereArr = array();
		!empty($conditions['tagname']) && $whereArr[] = array('tagname', $conditions['tagname'], 'LIKE');
		strlen($conditions['usenumstart']) > 0 && $whereArr[] = array('usenum', $conditions['usenumstart'], '>=');
		strlen($conditions['usenumend']) > 0 && $whereArr[] = array('usenum', $conditions['usenumend'], '<=');
		!empty($conditions['status']) && $whereArr[] = array('visible', $conditions['status']);
		//标签数量
		$tagCount = $tagModel->getTagCount($whereArr);
		$this->assign('tagscount', $tagCount);
		//分页
		$perpage = Core_Config::get ('page_size', 'basic', 20);
		$curpage = $this->getParam('page') ? intval($this->getParam('page')) : 1;
		$conditionstr = implode('||', $conditions);
		$md5str = md5($conditionstr.$securecode);
		$mpurl = '/admin/tag/manage/conditions/'.$conditionstr.'/code/'.$md5str.'/';
		$multipage = $this->multipage($tagCount, $perpage, $curpage, $mpurl);
		$this->assign('multipage', $multipage);
		//标签列表
		$tagList = $tagModel->getTagList($whereArr, 'usenum desc', array($perpage, $perpage*($curpage-1)));
		$this->assign('tags', $tagList);
		//查询条件
		$this->assign('conditions', $conditions);		
		$this->display('admin/tag_manage.tpl');
	}
}