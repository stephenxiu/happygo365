<?phpdefined('haipinlegou') or exit('Access Invalid!');class cartModel {		public function checkCart($param, $fields = '*') {		$cart_info	= array();		$condition_str	= $this->getCondition($param);		$array			= array();		$array['table']	= 'cart';		$array['where']	= $condition_str;		$array['field']	= $fields;		$cart_info		= Db::select($array);		return $cart_info;	}		public function addCart($param) {		if (is_array($param)){			$tmp = array();			foreach ($param as $k => $v){				$tmp[$k] = $v;			}			$result = Db::insert('cart',$tmp);			return $result;		}else {			return false;		}	}		public function updateCart($param,$condition) {		$array			= array();		$condition_str		= $this->getCondition($condition);		$result	= Db::update('cart',$param,$condition_str);		return $result;	}		public function listCart($store_id='') {		if ($_SESSION['member_id'] == '') {			return ;		}		$where	= '';		if($store_id != '') {                            $where	= " and cart.store_id= '$store_id' AND checked=1";		}		$cart_list	= array();		$array		= array();		$array['table'] = 'cart,store';		$array['field'] = 'cart.*,store_name';		$array['join_type']= 'LEFT JOIN';		$array['join_on']= array('cart.store_id=store.store_id');		$array['where'] = " where cart.member_id='{$_SESSION['member_id']}'".$where;		$cart_list	= Db::select($array);		return $cart_list;	}				public function dropCart($cart_id) {		if(intval($cart_id) != 0) {			$where = " cart_id = '".intval($cart_id)."' and member_id= '{$_SESSION['member_id']}'";			$result = Db::delete('cart',$where);			return $result;		} else {			return false;		}	}				public function dropCartByCondition($condition_arr) {		$condition_str = $this->getCondition($condition_arr);		$result = Db::delete('cart',$condition_str);		return $result;	}				public function countCart($condition_arr) {		$condition_str = $this->getCondition($condition_arr);		$array		= array();		$array['table'] = 'cart';		$array['field'] = 'count(*) as countnum';		$array['where'] = $condition_str;		$cart_goods = Db::select($array);		return $cart_goods[0]['countnum'];	}		private function getCondition($condition_array){		$condition_sql = '';		if($condition_array['cart_spec_id'] != '') {			$condition_sql	.= " and spec_id= '{$condition_array['cart_spec_id']}'";		}		if($condition_array['cart_member_id'] != '') {			$condition_sql	.= " and member_id= '{$condition_array['cart_member_id']}'";		}		if($condition_array['spec_store_id'] != '') {			$condition_sql	.= " and store_id= '{$condition_array['spec_store_id']}'";		}		return $condition_sql;	}	public function tax($name,$goods_price)	{		switch ($name)		{		case '男女服装':		  return $goods_price = $goods_price*0.2;		  break;  		case '鞋包配饰':		  return $goods_price = $goods_price*0.1;		  break;				case '美容美妆':		  return $goods_price = $goods_price*0.5;		  break;				case '运动户外':		   return $goods_price = $goods_price*0.1;		  break;				case '数码家电':		   return $goods_price = $goods_price*0.2;		  break;				case '生活日用':		  return $goods_price = $goods_price*0.1;		  break;				case '食品保健':		  return $goods_price = $goods_price*0.1;		  break;				case '母婴用品':		   return $goods_price = $goods_price*0.2;		  break;				case '文化娱乐':		   return $goods_price = $goods_price*0.1;		  break;				case '话费网游':		   return $goods_price = $goods_price*0.1;		  break;				case '票务旅游':		   return $goods_price = $goods_price*(1+0.1);		  break;		}	}	public function tax_tiff($name)	{		switch ($name)		{		case '男女服装':		  return 0.2;		  break;  		case '鞋包配饰':		  return 0.1;		  break;				case '美容美妆':		  return 0.5;		  break;				case '运动户外':		   return 0.1;		  break;				case '数码家电':		   return 0.2;		  break;				case '生活日用':		  return 0.1;		  break;				case '食品保健':		  return 0.1;		  break;				case '母婴用品':		   return 0.2;		  break;				case '文化娱乐':		   return 0.1;		  break;				case '话费网游':		   return 0.1;		  break;				case '票务旅游':		   return 0.1;		  break;		}	}			public function fetchBySpecId($specId){		$arr['table'] = 'cart';                $arr['field'] =  array('spec_id','member_id');		$arr['value'] =array($specId,$_SESSION['member_id']);		return Db::getRow($arr, '*');	}    public function getCartGoods($param)    {        if(empty($param['goods_id'])){            $param['goods_id']=0;        }        $array		= array();        $array['table'] = 'cart';        $array['field'] = '*';        $array['where'] = " store_id={$param['spec_store_id']} and member_id={$param['cart_member_id']} and goods_id in({$param['goods_id']})";        $cart_goods = Db::select($array);        return $cart_goods;    }}