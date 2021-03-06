<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php defined('haipinlegou') or exit('Access Invalid!');?>

<link href="<?php echo TEMPLATES_PATH;?>/css/base.css" rel="stylesheet" type="text/css"/>

<link href="<?php echo TEMPLATES_PATH;?>/css/member_store.css" rel="stylesheet" type="text/css"/>

<style type="text/css">

body {

	background-color: #FFF;

	background-image: none;

}

</style>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-1.7.2.min.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.printarea.js" charset="utf-8"></script>

<title><?php echo $lang['member_printorder_print'];?>--<?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></title>

</head>



<body>

<?php if (!empty($output['ordergoods_list'])){?>

<div class="print-layout">

  <div class="print-btn" id="printbtn" title="<?php echo $lang['member_printorder_print_tip'];?>"><i></i><a href="javascript:void(0);"><?php echo $lang['member_printorder_print'];?></a></div>

  <div class="a5-size"></div>

  <dl class="a5-tip">

    <dt>

      <h1>A5</h1>

      <em>Size: 210mm x 148mm</em></dt>

    <dd><?php echo $lang['member_printorder_print_tip_A5'];?></dd>

  </dl>

  <div class="a4-size"></div>

  <dl class="a4-tip">

    <dt>

      <h1>A4</h1>

      <em>Size: 210mm x 297mm</em></dt>

    <dd><?php echo $lang['member_printorder_print_tip_A4'];?></dd>

  </dl>

  <div class="print-page">

    <div id="printarea">

      <?php foreach ($output['ordergoods_list'] as $item_k =>$item_v){?>

      <div class="orderprint">

        <div class="top">
       
       
       	<?php if($output['order_info']['store_id']!=20){?>
       
          <?php if (empty($output['store_info']['store_label'])){?>

          <div class="full-title"><?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></div>

          <?php }else {?>

          <div class="logo" ><img src="<?php echo $output['store_info']['store_label']; ?>"/></div>

          <div class="logo-title"><?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></div>

          <?php }?>
          
           <?php }else{?>  
              <div class="full-title">
			  <?php
			  $newstr='';
              foreach($item_v as $val){
			  			
						preg_match("/服务地点:.*?&nbsp;/is", $val[6], $matches);
						$str=$matches[0];
			  			$newstr.=$newstr?"和".str_replace("服务地点:", "", $str):str_replace("服务地点:", "", $str);
			  }
			 echo $newstr;
			  
			  
			  ?><?php echo $lang['member_printorder_title'];?></div>
           <?php }?>

        </div>

        <table class="buyer-info">
				<?php if($output['order_info']['store_id']!=20){?>
          <tr>

            <td class="w200"><?php echo $lang['member_printorder_truename'].$lang['nc_colon']; ?><?php echo $output['order_info']['true_name'];?></td>

            <td class="w300"><?php echo $lang['member_printorder_tel_phone'].$lang['nc_colon']; ?><?php echo $output['order_info']['tel_phone'];?></td>

            <td><?php echo $lang['member_printorder_mob_phone'].$lang['nc_colon']; ?><?php echo $output['order_info']['mob_phone'];?></td>

          </tr>

          <tr>

            <td><?php echo $lang['member_printorder_area'].$lang['nc_colon']; ?><?php echo $output['order_info']['area_info'];?></td>

            <td><?php echo $lang['member_printorder_address'].$lang['nc_colon']; ?><?php echo $output['order_info']['address'];?></td>

            <td><?php echo $lang['member_printorder_zip_code'].$lang['nc_colon']; ?><?php echo $output['order_info']['zip_code'];?></td>

          </tr>
		 <?php }?>
          <tr>
         

            <td><?php echo $lang['member_printorder_orderno'].$lang['nc_colon'];?><?php echo $output['order_info']['order_sn'];?></td>

            <td><?php echo $lang['member_printorder_orderadddate'].$lang['nc_colon'];?><?php echo @date('Y-m-d',$output['order_info']['add_time']);?></td>

            <td><?php if ($output['order_info']['order_state'] >20 ){?>

              <span><?php echo $lang['member_printorder_shippingcode'].$lang['nc_colon']; ?><?php echo $output['order_info']['shipping_code'];?></span>

              <?php }?></td>
              <?php if($output['order_info']['store_id']==20){?>
            <td><?php echo $lang['member_printorder_pnum'].$lang['nc_colon'];;?><?php echo $output['pnumlist'][0]['punm'];?></td>
			<?php }?>
          </tr>

        </table>

        <table class="order-info">

          <thead>

            <tr>

              <th class="w40"><?php echo $lang['member_printorder_serialnumber'];?></th>

              <th class="tl"><?php echo $lang['member_printorder_goodsname'];?></th>

              <th class="w150 tl"><?php echo $lang['member_printorder_specification'];?></th>

              <th class="w70 tl"><?php echo $lang['member_printorder_goodsprice'];?>(<?php echo $lang['currency_zh'];?>)</th>

              <th class="w50"><?php echo $lang['member_printorder_goodsnum'];?></th>

              <th class="w70 tl"><?php echo $lang['member_printorder_subtotal'];?>(<?php echo $lang['currency_zh'];?>)</th>

            </tr>

          </thead>

          <tbody>

            <?php foreach ($item_v as $k=>$v){?>
            <tr>

              <td><?php echo $k;?></td>

              <td class="tl"><?php echo $v['goods_name'];?></td>

              <td class="tl"><?php echo $v['6'];?></td>

              <td class="tl"><?php echo $lang['currency'].$v['goods_price'];?></td>

              <td><?php echo $v['goods_num'];?></td>

              <td class="tl"><?php echo $lang['currency'].$v['goods_allprice'];?></td>

            </tr>

            <?php }?>

            <tr>

              <th></th>

              <th colspan="3" class="tl"><?php echo $lang['member_printorder_amountto'];?></th>

              <th><?php echo $output['goods_allnum'];?></th>

              <th class="tl"><?php echo $lang['currency'].$output['goods_totleprice'];?></th>

            </tr>

          </tbody>

          <tfoot>

            <tr>

              <th colspan="10"><span><?php echo $lang['member_printorder_totle'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['goods_totleprice'];?></span><span><?php echo $lang['member_printorder_freight'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['order_info']['shipping_fee'];?></span><span><?php echo $lang['member_printorder_privilege'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['order_info']['discount'];?></span><span><?php echo $lang['member_printorder_orderamount'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['order_info']['order_amount'];?></span><span><?php echo $lang['member_printorder_shop'].$lang['nc_colon'];?><?php echo $output['store_info']['store_name'];?></span><span><?php echo $lang['member_printorder_shopowner'].$lang['nc_colon'];?><?php echo $output['store_info']['member_name'];?></span>

                <?php if (!empty($output['store_info']['store_tel'])){?>

                <span><?php echo $lang['member_printorder_shoptelephone'].$lang['nc_colon'];?><?php echo $output['store_info']['store_tel'];?></span>

                <?php }?>

                <?php if (!empty($output['store_info']['store_qq'])){?>

                <span>QQ：<?php echo $output['store_info']['store_qq'];?></span>

                <?php }elseif (!empty($output['store_info']['store_ww'])){?>

                <span><?php echo $lang['member_printorder_shopww'].$lang['nc_colon'];?><?php echo $output['store_info']['store_ww'];?></span>

                <?php }?></th>

            </tr>

          </tfoot>

        </table>
        
           <?php if ($output['store_info']['store_id']==20){?>
       
        <table>
        	<tr>
            	<td>保养类别</td>
                <td>明细</td>
                 <td>数量</td>
                
                <td>原厂型号</td>
                <td>替换品牌</td>
                <td>替换型号</td>
               
            </tr>
             <?php foreach($detaillist as $val){?>
             <tr>
            	<td><?php echo $val['type']?></td>
                <td><?php echo $val['detail']?></td>
                <td><?php echo ($val['d_num']*$val['og_goods_num'])?></td>
                <td><?php echo $val['d_omodel']?></td>
                <td><?php echo $val['d_rbrand']?></td>
                <td><?php echo $val['d_rmodel']?></td>
               
            </tr>
              <?php }?>
        </table>
        
       

        <?php if (empty($output['store_info']['store_stamp'])){?>

        <div class="explain">

        	<?php echo $output['store_info']['store_printdesc'];?>

        </div>

        <?php }else {?>

        <div class="explain">

        	<?php echo $output['store_info']['store_printdesc'];?>

        </div>

        <div class="seal"><img src="<?php echo $output['store_info']['store_stamp'];?>" onload="javascript:DrawImage(this,120,120);"/></div>

        <?php }?>

        <div class="tc page"><?php echo $lang['member_printorder_pagetext_1']; ?><?php echo $item_k;?><?php echo $lang['member_printorder_pagetext_2']; ?>/<?php echo $lang['member_printorder_pagetext_3']; ?><?php echo count($output['ordergoods_list']);?><?php echo $lang['member_printorder_pagetext_2']; ?></div>

      </div>

      <?php }?>

    </div>

    <?php }?>
<?php }?>
  </div>

</div>

</body>

<script>

$(function(){

	$("#printbtn").click(function(){

	$("#printarea").printArea();

	});

});



$('#printbtn').poshytip({

	className: 'tip-yellowsimple',

	showTimeout: 1,

	alignTo: 'target',

	alignX: 'center',

	alignY: 'bottom',

	offsetY: 5,

	allowTipHover: false

});

</script>

</html>