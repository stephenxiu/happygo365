<?phpdefined('haipinlegou') or exit('Access Invalid!');class advModel{		public function ap_add($param){		if (empty($param)){			return false;		}		if (is_array($param)){			$tmp = array();			foreach ($param as $k => $v){				$tmp[$k] = $v;			}						$result = Db::insert('adv_position',$tmp);									return $result;		}else {			return false;		}	}  	public function adv_add($param){		if (empty($param)){			return false;		}		if (is_array($param)){			$tmp = array();			foreach ($param as $k => $v){				$tmp[$k] = $v;			}					$result = Db::insert('adv',$tmp);			return $result;		}else {			return false;		}	}  	public function adv_click_add($param){		if (empty($param)){			return false;		}		if (is_array($param)){			$tmp = array();			foreach ($param as $k => $v){				$tmp[$k] = $v;			}						$result = Db::insert('adv_click',$tmp);			return $result;		}else {			return false;		}	}		public function adv_del($adv_id){		$where  = "where adv_id = '$adv_id'";		$result = Db::delete("adv",$where);		return $result;	}		public function ap_del($ap_id){		$where  = "where ap_id = '$ap_id'";		$result = Db::delete("adv_position",$where);		return $result;	}		public function getApList($condition=array(), $page='', $orderby=''){		$param	= array();		$param['table']	= 'adv_position';		$param['where']	= $this->getCondition($condition);	    if($orderby == ''){			$param['order'] = 'ap_id desc';		}else{			$param['order'] = $orderby;		}		return Db::select($param,$page);	}		public function getList($condition=array(), $page='', $limit='', $orderby=''){		$param	= array();		$param['table']	= 'adv';		$param['field'] = $condition['field']?$condition['field']:'*';		$param['where']	= $this->getCondition($condition);		if($orderby == ''){			$param['order'] = 'slide_sort, adv_id desc';		}else{			$param['order'] = $orderby;		}		$param['limit'] = $limit;		return Db::select($param,$page);	}		public function getClickList($condition = array()){		$param = array();		$param['table'] = 'adv_click';		$param['where'] = $this->getCondition($condition);		return Db::select($param);	}		public function getOneById($id){		$param	= array();		$param['table']	= 'adv';		$param['field']	= 'adv_id';		$param['value']	= $id;		return Db::getRow($param);	}		public function getLastDateById($id){		$param	= array();		$param['table']	= 'adv';		$param['where']	= " and ap_id ='".intval($id)."'";        $param['order'] = 'adv_end_date desc';		$result = Db::select($param);        return $result[0]['adv_end_date'];	}		public function update($param){		return Db::update('adv',$param,"adv_id='{$param['adv_id']}'");	}   	public function ap_update($param){		return Db::update('adv_position',$param,"ap_id='{$param['ap_id']}'");	}   	public function getOneClickById($id,$year,$month){		$param	= array();		$param['table']	= 'adv_click';		$param['field']['0'] = 'adv_id';		$param['value']['0'] = $id;		$param['field']['1'] = 'click_year';		$param['value']['1'] = $year;		$param['field']['2'] = 'click_month';		$param['value']['2'] = $month;		return Db::getRow($param);	}		public function getclickinfo($condition = array()){		$param	= array();		$param['table']	= 'adv_click';		$param['where']	= $this->getCondition($condition);		return Db::select($param);	}		public function adv_click_update($param){		return Db::update('adv_click',$param,"adv_id='{$param['adv_id']}' and click_year='{$param['click_year']}' and click_month='{$param['click_month']}'");	}		public function getcnmonth($num){		Language::read('model_lang_index');		$lang	= Language::getLangContent();		switch($num){			case '1':				$cnmonth = $lang['month_jan'];				break;			case '2':				$cnmonth = $lang['month_feb'];				break;			case '3':				$cnmonth = $lang['month_mar'];				break;			case '4':				$cnmonth = $lang['month_apr'];				break;			case '5':				$cnmonth = $lang['month_may'];				break;			case '6':				$cnmonth = $lang['month_jun'];				break;			case '7':				$cnmonth = $lang['month_jul'];				break;			case '8':				$cnmonth = $lang['month_aug'];				break;			case '9':				$cnmonth = $lang['month_sep'];				break;			case '10':				$cnmonth = $lang['month_oct'];				break;			case '11':				$cnmonth = $lang['month_nov'];				break;			case '12':				$cnmonth = $lang['month_dec'];				break;		}		return $cnmonth;	}		public function makexml($position,$click_info){		$dom = new DOMDocument('1.0');		$root= $dom->createElement("AdvClick");		$dom->appendChild($root);				foreach ($click_info as $k=>$v){			$click_result[$v['click_month']] = $v['click_month'];		}		$click_month = array_count_values($click_result);		for($i=1;$i<13;$i++){			$cnmonth = $this->getcnmonth($i);			$item  = $dom->createElement("Data");             $root->appendChild($item); 			$month = $dom->createAttribute("month");             $item->appendChild($month);            $monthValue = $dom->createTextNode("$cnmonth");             $month->appendChild($monthValue);            $num = $dom->createAttribute("num");             $item->appendChild($num);            if($click_month[$i] == 1){            	foreach ($click_info as $ck=>$cv){            		if($cv['click_month'] == $i){            			$click_num = $cv['click_num'];            		}            	}            $numValue = $dom->createTextNode($click_num);             }else{            $numValue = $dom->createTextNode("0");             }            $num->appendChild($numValue);		} 		$xmlfile = BasePath.DS.$position.'/clickswf/adv_click.xml';		if($dom->save($xmlfile)){			return true;		}	}		private function getCondition($condition = array()){		$return	= '';		$time   = time();		if($condition['adv_type'] != ''){			$return	.= " and adv_type='".$condition['adv_type']."'";		}		if($condition['adv_code'] != ''){			$return	.= " and adv_code='".$condition['adv_code']."'";		}		if($condition['no_adv_type'] != ''){			$return	.= " and adv_type!='".$condition['no_adv_type']."'";		}		if ($condition['adv_state'] != '') {			$return .= " and adv_state='".$condition['adv_state']."'";		}	    if ($condition['ap_id'] != '') {			$return .= " and ap_id='".$condition['ap_id']."'";		}	    if ($condition['adv_id'] != '') {			$return .= " and adv_id='".$condition['adv_id']."'";		}		if ($condition['adv_end_date'] == 'over'){			$return .= " and adv_end_date<'".$time."'";		}	    if ($condition['adv_end_date'] == 'notover'){			$return .= " and adv_end_date>'".$time."'";		}	    if ($condition['ap_name'] != ''){			$return .= " and ap_name like '%".$condition['ap_name']."%'";		}	    if ($condition['adv_title'] != ''){			$return .= " and adv_title like '%".$condition['adv_title']."%'";		}	    if ($condition['add_time_from'] != ''){			$return .= " and adv_start_date > '{$condition['add_time_from']}'";		}	    if ($condition['add_time_to'] != ''){			$return .= " and adv_end_date < '{$condition['add_time_to']}'";		}		if ($condition['member_name'] != ''){			$return .= " and member_name ='".$condition['member_name']."'";		}		if($condition['click_year'] != ''){			$return .= " and click_year ='".$condition['click_year']."'";		}	    if($condition['is_allow'] != ''){			$return .= " and is_allow = '".$condition['is_allow']."' ";		}	    if($condition['buy_style'] != ''){			$return .= " and buy_style = '".$condition['buy_style']."' ";		}	    if($condition['adv_start_date'] == 'nowshow'){			$return .= " and adv_start_date <'".$time."'";		}	    if($condition['member_id'] != ''){			$return .= " and member_id = '".$condition['member_id']."'";		}	    if($condition['is_use'] != ''){			$return .= " and is_use = '".$condition['is_use']."' ";		}	    if ($condition['adv_buy_id'] != '') {			$return .= " and ap_id not in (".$condition['adv_buy_id'].")";		}		return $return;	}	public function cls(){		delCacheFile('adv');	    $adv_info = $this->getList();	    $ap_array = array();		foreach ($adv_info as $k=>$v){			$ap_array[$v['ap_id']][] = $v;			if($v['adv_end_date'] > $time && $v['is_allow'] == '1'){				$this->makeAdvCache($v);			}		}	    $ap_info  = $this->getApList();		foreach ($ap_info as $k=>$v){			$this->makeApCache($v,$ap_array[$v['ap_id']]);		}	}		public function makeAdvCache($adv){		$lang	= Language::getLangContent();		$tmp .= "<?php \r\n";		$tmp .= "defined('haipinlegou') or exit('Access Invalid!'); \r\n";		if (is_numeric($adv) && $adv > 0){			$condition['adv_id'] = $adv;			$adv_info = $this->getList($condition);			$adv = $adv_info['0'];		}		foreach ($adv as $k=>$v){			if(is_numeric($k)){				continue;			}			$content = addslashes($v);			$content = str_replace('$','\$',$content);			$tmp .= '$'.$k." = \"".$content."\"; \r\n";		}		$cache_file = BasePath.'/cache/adv/adv_'.$adv['adv_id'].'.cache.php';		if(!file_exists(BasePath.'/cache/adv')){			mkdir(BasePath.'/cache/adv/','0777',true);		}		file_put_contents($cache_file,$tmp);		return true;	}  	public function makeApCache($ap,$adv_info = array()){		$lang	= Language::getLangContent();				if (is_numeric($ap) && $ap > 0){			$condition['ap_id'] = $ap;			$adv_info = $this->getList($condition,'','','slide_sort, adv_id desc');			$ap_info  = $this->getApList($condition);			$ap  = $ap_info['0'];		}		$tmp .= "<?php \r\n";		$tmp .= "defined('haipinlegou') or exit('Access Invalid!'); \r\n";		$tmp .= '$data = array('."\r\n";		if(!empty($adv_info) && is_array($adv_info)){			foreach ($adv_info as $k=>$v){				$tmp .= "array('".$v['adv_id']."','".$v['is_allow']."','".$v['adv_start_date']."','".$v['adv_end_date']."'), \r\n";			}		}		$tmp .= ");\r\n";		if (is_array($ap)){			foreach ($ap as $k=>$v){			    if(is_numeric($k)){					continue;				}				$tmp .= '$'.$k." = '".$v."'; \r\n";			}			$ap = $ap['ap_id'];		}		$cache_file = BasePath.'/cache/adv/ap_'.$ap.'.cache.php';		file_put_contents($cache_file,$tmp);;		return true;	}	public function deladcache($id,$type){		$filename = BasePath.DS.'cache'.DS.'adv'.DS.$type.'_'.$id.'.cache.php';		if(!file_exists($filename))return false;		if(@!unlink($filename))return false;		return true;	}}