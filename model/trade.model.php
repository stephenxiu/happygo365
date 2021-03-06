<?php

defined('haipinlegou') or exit('Access Invalid!');
class tradeModel extends Model{
	public function __construct(){
		parent::__construct();
	}
	public function order_list($condition = array(), $page=10, $field = '*'){
		$table = 'order,order_address,payment,store';
        $field='*,order_address.area_info as area_info_order';
		$on = 'order.order_id=order_address.order_id,order.payment_id=payment.payment_id,order.store_id=store.store_id';
		$order = 'order.add_time desc';
		$list = $this->table($table)->field($field)->on($on)->join('left,left')->where($condition)->page($page)->order($order)->select();
		return $list;
	}
        //按时间筛选列表
        public function order_liste_time_screen ($condition = array()){
            $table = 'order'; 
            $order = 'add_time desc';
            $condition_sql= "add_time>='".$condition['add_time_from']."' and '".$condition['add_time_to']."'>=add_time and seller_id='".$condition['seller_id']."' and order_state in('".$condition['order_state']."')";
            $list = $this->table($table)->where($condition_sql)->order($order)->select();
            return $list;
        }
	
	public function getMaxDay($day_type = 'all'){
		$max_data = array(
			'order_cancel' => 7,
			'cod_cancel' => 7,
			'order_confirm' => 20,
			'refund_confirm' => 7
			);
		if ($day_type == 'all') return $max_data;
		if (intval($max_data[$day_type]) < 1) $max_data[$day_type] = 1;
		return $max_data[$day_type];
	}
	
	public function updateOrderPay($member_id=0,$store_id=0) {
		$order_cancel = $this->getMaxDay('order_cancel');
		$day = time()-$order_cancel*60*60*24;
		$order_confirm = $this->getMaxDay('order_confirm');
		$shipping_day = time()-$order_confirm*60*60*24;
		$cod_cancel = $this->getMaxDay('cod_cancel');
		$cod_day = time()-$cod_cancel*60*60*24;
		$condition_sql = ' ((order_state=10 and add_time<'.$day.') or (order_state=30 and shipping_time<'.$shipping_day.
														') or (order_state=50 and payment_time<'.$cod_day.')) and refund_state=0';
		if ($member_id > 0){
			$condition_sql = " (buyer_id = '".$member_id."' or seller_id = '".$member_id."') and ".$condition_sql;
		}
		if ($store_id > 0){
			$condition_sql = " store_id = '".$store_id."' and ".$condition_sql;
		}
		$field = 'order_id,buyer_id,seller_id,store_id,add_time,payment_time,order_state';
		$order_list = $this->table('order')->field($field)->where($condition_sql)->select();
		Language::read('model_lang_index');
		if(!empty($order_list) && is_array($order_list)) {
			foreach($order_list as $k => $v){
				$order_id = $v['order_id'];
				$order_state = $v['order_state'];
				$log_array = array();
				$log_array['order_state'] = Language::get('order_state_canceled');
				$log_array['change_state'] = Language::get('order_state_null');
				$log_array['operator'] = Language::get('order_state_operator');
				$log_array['log_time'] = time();
				$log_array['order_id'] = $order_id;
				
				switch ($order_state) {
			    case 10:
			    	$order_time = $v['add_time'];
			    	
						if (intval($order_time) < $day){
							$state_info = Language::get('order_max_day').$order_cancel.Language::get('order_max_day_cancel');
							$log_array['state_info'] = $state_info;
							$this->updateOrderCancel($order_id,$log_array);
						}
			    	break;
			    case 30:
			    	$order_time = $v['shipping_time'];
			    	
						$log_array['order_state'] = Language::get('order_state_completed');
						$log_array['change_state'] = Language::get('order_state_to_be_evaluated');
						if (intval($order_time) < $shipping_day){
							$state_info = Language::get('order_max_day').$order_confirm.Language::get('order_max_day_confirm');
							$log_array['state_info'] = $state_info;
							$this->updateOrderFinnsh($order_id,$log_array);
						}
			    	break;
			    case 50:
			    	$order_time = $v['payment_time'];
			    	
						if (intval($order_time) < $cod_day){
							$state_info = Language::get('order_max_day').$cod_cancel.Language::get('order_max_day_cod');
							$log_array['state_info'] = $state_info;
							$this->updateOrderCancel($order_id,$log_array);
						}
			    	break;
				}
			}
			return true;
		}
		return false;
	}
	
	public function updateOrderCancel($order_id,$log_array) {
		$goods_list = $this->table('order_goods')->field('order_id,goods_num,spec_id,goods_id')->where(array('order_id'=>$order_id))->select();
		
		if(!empty($goods_list) && is_array($goods_list)) {
			foreach($goods_list as $k => $v){
				$goods_id = $v['goods_id'];
				$spec_id = $v['spec_id'];
				$goods_num = $v['goods_num'];
				$data = array();
				$data['spec_goods_storage'] = array('exp','spec_goods_storage+'.$goods_num);
				$data['spec_salenum'] = array('exp','spec_salenum-'.$goods_num);
				$this->table('goods_spec')->where(array('spec_id'=>$spec_id))->update($data);
				$this->table('goods')->where(array('goods_id'=>$goods_id))->setDec('salenum',$goods_num);
			}
    	$order_array = array();
    	$order_array['order_state'] = '0';
    	$state = $this->table('order')->where(array('order_id'=>$order_id))->update($order_array);
			if($state) $state = $this->table('order_log')->insert($log_array);
			return $state;
		}
		return false;
	}
	
	public function updateRefund($member_id=0,$store_id=0) {
		$refund_confirm = $this->getMaxDay('refund_confirm');
		$day = time()-$refund_confirm*60*60*24;
		$condition_sql = " refund_state=1 and refund_paymentcode='predeposit' and add_time<".$day;
		if ($member_id > 0){
			$condition_sql = " (buyer_id = '".$member_id."' or seller_id = '".$member_id."') and ".$condition_sql;
		}
		if ($store_id > 0){
			$condition_sql = " store_id = '".$store_id."' and ".$condition_sql;
		}
		$field = 'log_id,order_id,buyer_id,seller_id,store_id,add_time,refund_state,order_refund';
		$refund_list = $this->table('refund_log')->field($field)->where($condition_sql)->select();
		Language::read('model_lang_index');
		
		if(!empty($refund_list) && is_array($refund_list)) {
			foreach($refund_list as $k => $v){
				$refund_array = array();
				$refund_array['log_id'] = $v['log_id'];
				$refund_array['order_id'] = $v['order_id'];
				$refund_array['order_refund'] = $v['order_refund'];
				$refund_array['refund_state'] = '2';
				$refund_array['refund_message'] = Language::get('order_max_day').$refund_confirm.Language::get('order_day_refund');
				$log_array = array();
				$log_array['order_id'] = $v['order_id'];
				$log_array['operator'] = Language::get('order_state_operator');
				$log_array['order_state'] = Language::get('order_state_completed');
				$log_array['change_state'] = Language::get('order_state_to_be_evaluated');
				$log_array['state_info'] = Language::get('order_max_day').$refund_confirm.Language::get('order_max_day_refund');
				$log_array['log_time'] = time();
				$this->updateOrderRefund($refund_array,$log_array);
			}
			return true;
		}
	}
	
	public function updateComplainRefund($refund_array) {
		$log_id = $refund_array["log_id"];
		Language::read('model_lang_index');
		if ($log_id > 0){
			$refund_state = $refund_array["refund_state"];
			$order_id = $refund_array['order_id'];
			switch ($refund_state) {
		    case 2:
		    	
		    	$log_array = array();
					$log_array['order_id'] = $order_id;
					$log_array['operator'] = Language::get('order_state_operator');
					$log_array['order_state'] = Language::get('order_state_completed');
					$log_array['change_state'] = Language::get('order_state_to_be_evaluated');
					$log_array['state_info'] = Language::get('order_admin_refund_yes');
					$log_array['operator'] = Language::get('order_admin_operator');
					$log_array['log_time'] = time();
		    	$this->updateOrderRefund($refund_array,$log_array);
		    	break;
		    case 3:
					$state = true;
					$field = 'order_id,buyer_id,buyer_name,seller_id,store_id,store_name,order_sn,order_amount,order_state';
					$order = $this->table('order')->field($field)->where(array('order_id'=>$order_id))->find();
					$order_state = $order['order_state'];
					if($order_state == 20) {
						$log_array = array();
						$log_array['order_id'] = $order_id;
						$log_array['operator'] = Language::get('order_state_operator');
						$log_array['order_state'] = Language::get('order_state_shipped');
						$log_array['change_state'] = Language::get('order_state_be_receiving');
						$log_array['state_info'] = Language::get('order_admin_refund_shipped');
						$log_array['log_time'] = time();
						$state = $this->table('order_log')->insert($log_array);
						
						$order_array['daddress_id'] = '0';
						$order_array['deliver_explain'] = Language::get('order_admin_refund_shipped');
						$order_array['shipping_express_id'] = '0';
						$order_array['shipping_code'] = '';
						$order_array['shipping_time'] = time();
						$order_array['order_state'] = 30;
					}
					$order_array['refund_state'] = '0';
					if($state) $state = $this->table('order')->where(array('order_id'=>$order_id))->update($order_array);
					$log_array = array();
					$log_array['order_id'] = $order_id;
					$log_array['operator'] = Language::get('order_state_operator');
					$log_array['order_state'] = Language::get('order_state_completed');
					$log_array['change_state'] = Language::get('order_state_to_be_evaluated');
					$log_array['state_info'] = Language::get('order_admin_refund_completed');
					$log_array['operator'] = Language::get('order_admin_operator');
					$log_array['log_time'] = time();
					if($state) $state = $this->updateOrderFinnsh($order_id,$log_array);
		    	break;
			}
			if($state) $state = $this->table('refund_log')->where(array('log_id'=>$log_id))->update($refund_array);
			return $state;
		}
		return false;
	}
	
	public function updateOrderRefund($refund_array,$order_log_array = array()) {
		$log_id = $refund_array["log_id"];
		Language::read('model_lang_index');
		if ($log_id > 0){
			$refund_state = $refund_array["refund_state"];
			$order_id = $refund_array['order_id'];
			$field = 'order_id,buyer_id,buyer_name,seller_id,store_id,store_name,order_sn,order_amount,order_state';
			$order = $this->table('order')->field($field)->where(array('order_id'=>$order_id))->find();
			switch ($refund_state) {
		    case 2:
		    	
					$order_state = $order['order_state'];
					$order_amount = $order['order_amount'];
					$order_refund = floatval($refund_array["order_refund"]);
					$order_array = array();
					$state = true;
					if($order_state == 20) {
						$log_array = array();
						$log_array['order_id'] = $order_id;
						$log_array['operator'] = Language::get('order_state_operator');
						$log_array['order_state'] = Language::get('order_state_shipped');
						$log_array['change_state'] = Language::get('order_state_be_receiving');
						$log_array['state_info'] = Language::get('order_refund_yes');
						$log_array['log_time'] = time();
						$state = $this->table('order_log')->insert($log_array);
						
						$order_array['daddress_id'] = '0';
						$order_array['deliver_explain'] = Language::get('order_refund_yes');
						$order_array['shipping_express_id'] = '0';
						$order_array['shipping_code'] = '';
						$order_array['shipping_time'] = time();
						$order_array['order_state'] = 30;
					}
					$order_array['order_amount'] = ncPriceFormat($order_amount-$order_refund);
					$order_array['refund_amount'] = ncPriceFormat($order_refund);
					$order_array['refund_state'] = ($order_amount-$order_refund)?1:2;
					if($state) $state = $this->table('order')->where(array('order_id'=>$order_id))->update($order_array);
					$buyer	= $this->table('member')->field('member_id,freeze_predeposit')->where(array('member_id'=>$order['buyer_id']))->find();
					$predeposit_model = Model('predeposit');
					$log_arr = array();	
					$log_arr['memberid'] = $order['buyer_id'];
					$log_arr['membername'] = $order['buyer_name'];
					$log_arr['logtype'] = '1';
					$log_arr['price'] = -$order_refund;
					$log_arr['desc'] = Language::get('order_sn').$order['order_sn'].Language::get('order_refund_freeze_predeposit');
					if (floatval($buyer['freeze_predeposit']) >= floatval($order['order_amount'])) $state = $predeposit_model->savePredepositLog('order',$log_arr);//确认买家预存款冻结金额足够扣除订单金额
					
					$log_arr = array();
					$log_arr['memberid'] = $order['buyer_id'];
					$log_arr['membername'] = $order['buyer_name'];
					$log_arr['logtype'] = '0';
					$log_arr['price'] = $order_refund;
					$log_arr['desc'] = Language::get('order_sn').$order['order_sn'].Language::get('order_refund_add_predeposit');
					if($state) $state = $predeposit_model->savePredepositLog('order',$log_arr);
					if(empty($order_log_array)) {
						$order_log_array = array();
						$order_log_array['order_id'] = $order_id;
						$order_log_array['operator'] = Language::get('order_state_operator');
						$order_log_array['order_state'] = Language::get('order_state_completed');
						$order_log_array['change_state'] = Language::get('order_state_to_be_evaluated');
						$order_log_array['state_info'] = Language::get('order_refund_completed');
						$order_log_array['log_time'] = time();
					}
					if($state) $state = $this->updateOrderFinnsh($order_id,$order_log_array);
		    	break;
		    case 3:
		    
		    	$subject = $this->table('complain_subject')->where(array('complain_subject_id'=>1))->find();
		    	$order_goods_list= $this->table('order_goods')->where(array('order_id'=>$order_id))->select();
		    	$state = true;
		    	$complain_array = array();
		    	$complain_array['order_id'] = $order['order_id'];
          $complain_array['accuser_id'] = $order['buyer_id'];
          $complain_array['accuser_name'] = $order['buyer_name'];
          $complain_array['accused_id'] = $order['seller_id'];
          $complain_array['accused_name'] = $order['store_name'];
          $complain_array['complain_subject_id'] = $subject['complain_subject_id'];
          $complain_array['complain_subject_content'] = $subject['complain_subject_content'];
          
	        $complain_array['order_goods_count'] = count($order_goods_list);
	        $complain_array['complain_goods_count'] = count($order_goods_list);
          $complain_array['complain_content'] = Language::get('order_refund_complain');
          $complain_array['complain_type'] = '1';
	        $complain_array['complain_datetime'] = time();
	        $complain_array['complain_state'] = '20';
	        $complain_array['complain_active'] = '2';
          $complain_array['complain_pic1'] = '';
          $complain_array['complain_pic2'] = '';
          $complain_array['complain_pic3'] = '';
          $complain_id = $this->table('complain')->insert($complain_array);
		    	if(is_array($order_goods_list) && !empty($order_goods_list)) {
		    		$goods_list = array();
		    		foreach ($order_goods_list as $key => $val) {
		    			$goods_array = array();
	            $goods_array['complain_id'] = $complain_id;
	            $goods_array['goods_id'] = $val['goods_id'];
	            $goods_array['goods_name'] = $val['goods_name'];
	            $goods_array['spec_id'] = $val['spec_id'];
	            $goods_array['spec_info'] = $val['spec_info'];
	            $goods_array['goods_price'] = $val['goods_price'];
	            $goods_array['goods_num'] = $val['goods_num'];
	            $goods_array['goods_image'] = $val['goods_image'];
	            $goods_array['evaluation'] = '2';
	            $goods_array['comment'] = '';
	            $goods_array['complain_message'] = '';
	            $goods_list[] = $goods_array;
		    		}
		    		if($complain_id > 0) $state = $this->table('complain_goods')->insertAll($goods_list);
		    	}
		    	break;
			}
			if($state) $state = $this->table('refund_log')->where(array('log_id'=>$log_id))->update($refund_array);
			return $state;
		}
		return false;
	}
	
	public function updateOrderFinnsh($order_id,$log_array = array()) {
		$field = 'order_id,buyer_id,buyer_name,seller_id,store_id,order_sn,order_amount,payment_code,order_state';
		$order = $this->table('order')->field($field)->where(array('order_id'=>$order_id))->find();
		$payment_code = $order["payment_code"];
		if($order['order_state'] == 30) {
			if(empty($log_array)) {
				$log_array['order_id'] = $order_id;
				$log_array['operator'] = Language::get('order_state_operator');
				$log_array['order_state'] = Language::get('order_state_completed');
				$log_array['change_state'] = Language::get('order_state_to_be_evaluated');
				$log_array['state_info'] = Language::get('order_completed');
				$log_array['log_time'] = time();
			}
			$seller	= $this->table('member')->field('member_id,member_name')->where(array('member_id'=>$order['seller_id']))->find();
			
			if ($payment_code == 'predeposit'){
				$predeposit_model = Model('predeposit');
				$buyer	= $this->table('member')->field('member_id,freeze_predeposit')->where(array('member_id'=>$order['buyer_id']))->find();
				
				$log_arr = array();	
				$log_arr['memberid'] = $order['buyer_id'];
				$log_arr['membername'] = $order['buyer_name'];
				$log_arr['logtype'] = '1';
				$log_arr['price'] = -$order['order_amount'];
				$log_arr['desc'] = Language::get('order_sn').$order['order_sn'].Language::get('order_completed_freeze_predeposit');
				if (floatval($buyer['freeze_predeposit']) >= floatval($order['order_amount'])) $state = $predeposit_model->savePredepositLog('order',$log_arr);//确认买家预存款冻结金额足够扣除订单金额
				
				$log_arr = array();
				$log_arr['memberid'] = $seller['member_id'];
				$log_arr['membername'] = $seller['member_name'];
				$log_arr['logtype'] = '0';
				$log_arr['price'] = $order['order_amount'];
				$log_arr['desc'] = Language::get('order_sn').$order['order_sn'].Language::get('order_completed_add_predeposit');
				if($state) $state = $predeposit_model->savePredepositLog('order',$log_arr);
			} elseif (C('payment') == 0){
				$predeposit_model = Model('predeposit');
				
				$log_arr = array();
				$log_arr['memberid'] = $seller['member_id'];
				$log_arr['membername'] = $seller['member_name'];
				$log_arr['logtype'] = '0';
				$log_arr['price'] = $order['order_amount'];
				$log_arr['desc'] = Language::get('order_sn').$order['order_sn'].Language::get('order_completed_add_predeposit');
				$state = $predeposit_model->savePredepositLog('income',$log_arr);
			}
			
			$order_array = array();
			$order_array['finnshed_time'] = time();
			$order_array['order_state'] = 40;
			if($state) $state = $this->table('order')->where(array('order_id'=>$order_id))->update($order_array);
			if($state) $state = $this->table('order_log')->insert($log_array);
			return $state;
		} else {
			return false;
		}
	}
	
	
	public function updateOfflineRefund($refund_array) {
		$log_id = $refund_array["log_id"];
		Language::read('model_lang_index');
		if ($log_id > 0){
			$state = true;
			$order_id = $refund_array['order_id'];
			$field = 'order_id,buyer_id,buyer_name,seller_id,store_id,store_name,order_sn,order_amount,order_state';
			$order = $this->table('order')->field($field)->where(array('order_id'=>$order_id))->find();
			if($refund_array['buyer_confirm'] == 2) {
				$order_state = $order['order_state'];
				$order_amount = $order['order_amount'];
				$order_refund = floatval($refund_array["order_refund"]);
				$order_array = array();
				
				if($order_state == 20) {
					$log_array = array();
					$log_array['order_id'] = $order_id;
					$log_array['operator'] = Language::get('order_state_operator');
					$log_array['order_state'] = Language::get('order_state_shipped');
					$log_array['change_state'] = Language::get('order_state_be_receiving');
					$log_array['state_info'] = Language::get('order_refund_yes');
					$log_array['log_time'] = time();
					$state = $this->table('order_log')->insert($log_array);
					
					$order_array['daddress_id'] = '0';
					$order_array['deliver_explain'] = Language::get('order_refund_yes');
					$order_array['shipping_express_id'] = '0';
					$order_array['shipping_code'] = '';
					$order_array['shipping_time'] = time();
					$order_array['order_state'] = 30;
				}
				$order_array['order_amount'] = ncPriceFormat($order_amount-$order_refund);
				$order_array['refund_amount'] = ncPriceFormat($order_refund);
				$order_array['refund_state'] = ($order_amount-$order_refund)?1:2;
				if($state) $state = $this->table('order')->where(array('order_id'=>$order_id))->update($order_array);
				$order_log_array = array();
				$order_log_array['order_id'] = $order_id;
				$order_log_array['operator'] = Language::get('order_state_operator');
				$order_log_array['order_state'] = Language::get('order_state_completed');
				$order_log_array['change_state'] = Language::get('order_state_to_be_evaluated');
				$order_log_array['state_info'] = Language::get('order_refund_buyer_confirm');
				$order_log_array['log_time'] = time();
				if($state) $state = $this->table('order_log')->insert($order_log_array);
				
				$order_array = array();
				$order_array['finnshed_time'] = time();
				$order_array['order_state'] = 40;
				if($state) $state = $this->table('order')->where(array('order_id'=>$order_id))->update($order_array);
			}
			if($state) $state = $this->table('refund_log')->where(array('log_id'=>$log_id))->update($refund_array);
			return $state;
		}
		return false;
	}
	
}
?>