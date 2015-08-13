<?php

defined('haipinlegou') or exit('Access Invalid!');

class documentControl extends BaseHomeControl {
	public function indexOp(){
		Language::read('home_document_index');
		$lang	= Language::getLangContent();
		if($_GET['code'] == ''){
			showMessage($lang['miss_argument'],'','html','error');
		}
		$model_doc	= Model('document');
		$doc	= $model_doc->getOneByCode($_GET['code']);
		Tpl::output('doc',$doc);
		
		$nav_link = array(
			array(
				'title'=>$lang['homepage'],
				'link'=>'index.php'
			),
			array(
				'title'=>$doc['doc_title']
			)
		);
		Tpl::output('nav_link_list',$nav_link);
		Tpl::showpage('document.index');
	}
}