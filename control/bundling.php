<?php

defined('haipinlegou') or exit('Access Invalid!');
class bundlingControl extends BaseStoreControl{
	public function __construct() {
		parent::__construct();
		Language::read('bundling');
	}
	public function indexOp() {
		$store_id	= intval($_GET['id']);
		$bl_id		= intval($_GET['bundling_id']);
		$store_info = $this->getStoreInfo($store_id);
		
		$model = Model();
		$bundling_info	= $model->table('p_bundling')->find($bl_id);
		$bundling_info['bl_img_more']	= empty($bundling_info['bl_img_more'])?'':explode(',', $bundling_info['bl_img_more']);
		
		
		$b_goods_list	= $model->table('p_bundling_goods,goods')
								->field('p_bundling_goods.bl_goods_id, p_bundling_goods.goods_name, goods.goods_id, goods.spec_id, goods.goods_image, goods.goods_store_price, goods.store_id, goods.goods_spec, goods.spec_name, goods.goods_col_img')
								->join('inner')->on('p_bundling_goods.goods_id=goods.goods_id')
								->where('bl_id='.$bl_id.' and goods_show=1')->select();
		$b_goods_array = array();
		$goods_id_array = array();
		$cost_price		= 0;
		if(!empty($b_goods_list) && is_array($b_goods_list)){
			foreach ($b_goods_list as $val){
				$goods_id_array[]	= $val['goods_id'];
				$b_goods_array[$val['goods_id']]['bl_goods_id']			= $val['bl_goods_id'];
				$b_goods_array[$val['goods_id']]['goods_name']			= $val['goods_name'];
				$b_goods_array[$val['goods_id']]['goods_id']			= $val['goods_id'];
				$b_goods_array[$val['goods_id']]['goods_image']			= $val['goods_image'];
				$b_goods_array[$val['goods_id']]['goods_store_price']	= $val['goods_store_price'];
				$b_goods_array[$val['goods_id']]['store_id']			= $val['store_id'];
				$b_goods_array[$val['goods_id']]['spec_id']				= $val['spec_id'];
				$b_goods_array[$val['goods_id']]['goods_spec']			= unserialize($val['goods_spec']);
				$b_goods_array[$val['goods_id']]['spec_name']			= unserialize($val['spec_name']);
				$b_goods_array[$val['goods_id']]['goods_col_img']		= unserialize($val['goods_col_img']);
				$cost_price	+= intval($val['goods_store_price']);
			}
			$bundling_info['bl_cost_price'] = ncPriceFormat($cost_price);
			
			$spec_array = $model->table('goods_spec')->where('goods_id in ('.implode(',', $goods_id_array).')')->order('goods_id')->select();
			if(!empty($spec_array) && is_array($spec_array)){
				foreach ($spec_array as $key=>$val){
					$s_array	= unserialize($val['spec_goods_spec']);
					$val['spec_goods_spec']	= '';
					if(!empty($s_array) && is_array($s_array)){
						foreach ($s_array as $k=>$v){
							$val['spec_goods_spec'] .= "'".$k."',";
						}
					}
					$val['spec_goods_spec']	= rtrim($val['spec_goods_spec'],',');
					if ($val['spec_id'] == $b_goods_array[$val['goods_id']]['spec_id']){
						$b_goods_array[$val['goods_id']]['spec_goods_storage'] = $val['spec_goods_storage'];
					}
					$spec_array[$key]	= $val;
				}
			}
			Tpl::output('spec_array',$spec_array);
			Tpl::output('spec_count',count($spec_array));
		}
		Tpl::output('bundling_info', $bundling_info);
		Tpl::output('b_goods_array', $b_goods_array);
		
		$other_bundling = $model->table('p_bundling')->where('bl_id <>'.$bl_id .' and bl_state = 1')->order(rand(1,5))->limit(4)->select();
		if(!empty($other_bundling) && is_array($other_bundling)){
			foreach ($other_bundling as $key=>$val){
				$img_array = explode(',', $val['bl_img_more']);
				$other_bundling[$key]['bl_img_more'] = array_shift($img_array);
			}
		}
		
		$this->output_mansong($store_id);
		
		$seo_param = array();
		$seo_param['name'] = Language::get('bundling_bundling').'-'.$bundling_info['bl_name'];
		$seo_param['key']  = '';
		$seo_param['description'] = '';
		Model('seo')->type('product')->param($seo_param)->show();
		$article = $this->_article();
		Tpl::output('page', 'bundling');
		Tpl::output('other_bundling', $other_bundling);
		Tpl::showpage('bundling');
	}
   
    private function output_mansong($store_id) {
      
        $model = Model();
        $mansong_list = $model->table('p_mansong')->where('store_id='.$store_id.' and state=2 and start_time <'.time().' and end_time >'.time())->order('mansong_id desc')->limit(1)->select();
        $mansong = $mansong_list[0];
        $mansong_flag = FALSE;
        if(!empty($mansong)) {
            $mansong_rule = $model->table('p_mansong_rule')->where('mansong_id='.$mansong['mansong_id'])->select();
            if(!empty($mansong_rule)) {
                $mansong_flag = TRUE;
                Tpl::output('mansong_info',$mansong);
                Tpl::output('mansong_rule',$mansong_rule);
            }
        }
        Tpl::output('mansong_flag',$mansong_flag);
    }
}