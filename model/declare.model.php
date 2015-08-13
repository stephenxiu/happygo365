<?php

defined('haipinlegou') or exit('Access Invalid!');
class declareModel{
	
	public function getAdminList($condition,$obj_page){
		$condition_str = $this->_condition($condition);
		$param = array(
					'table'=>'declare',
					'field'=>'*',
					'where'=>$condition_str
				);
		$result = Db::select($param);
		return $result;
	}
	
	
	public function _condition($condition){
		$condition_str = '';
		
		if ($condition['admin_id'] != ''){
			$condition_str .= " and admin_id = '". $condition['admin_id'] ."'";
		}
		if ($condition['admin_name'] != ''){
			$condition_str .= " and admin_name = '". $condition['admin_name'] ."'";
		}
		if ($condition['admin_password'] != ''){
			$condition_str .= " and admin_password = '". $condition['admin_password'] ."'";
		}
		
		return $condition_str;
	}
	
	
	public function getOneAdmin($admin_id){
		if (intval($admin_id) > 0){
			$param = array();
			$param['table'] = 'declare';
			$param['field'] = 'admin_id';
			$param['value'] = intval($admin_id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function infoAdmin($param, $field = '*') {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->_condition($param);
		$param	= array();
		$param['table']	= 'declare';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$admin_info	= Db::select($param);
		return $admin_info[0];
	}
	
	
	public function addAdmin($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('declare',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function updateAdmin($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " admin_id = '". $param['admin_id'] ."'";
			$result = Db::update('declare',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function delAdmin($id){
		if (intval($id) > 0){
			$where = " admin_id = '". intval($id) ."'";
			$result = Db::delete('declare',$where);
			return $result;
		}else {
			return false;
		}
	}
}