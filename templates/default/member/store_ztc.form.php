<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div id="seller_groupbuy_form" class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
  <div class="ncu-form-style">
    <form method="post" id='ztc_form' action="index.php?act=store_ztc&op=ztc_add">
      <input type="hidden" name="form_submit" value="ok"/>
      <dl>
        <dt><?php echo $lang['store_ztc_applytype'].$lang['nc_colon']; ?></dt>
        <dd>
          <input type="radio" name="ztc_type" id="ztc_type_0" checked="checked" value='0' onclick="ztctype_change();"/>
          &nbsp;<?php echo $lang['store_ztc_add_applytype_new'];?>&nbsp;&nbsp;
          <input type="radio" name="ztc_type" id="ztc_type_1" value='1' onclick="ztctype_change();"/>
          &nbsp;<?php echo $lang['store_ztc_add_applytype_recharge'];?>&nbsp;&nbsp; 
         
        </dd>
      </dl>
      <dl>
        <dt class="required"><em class="pngFix"></em><?php echo $lang['store_ztc_add_choose_goods'].$lang['nc_colon'];?><!-- 选择商品 --></dt>
        <dd>
          <input class="w400 text" gs_id="gselect_div" gs_name="goods_name" gs_callback="gs_callback" gs_title="<?php echo $lang['store_ztc_add_choose_goods'];?>" gs_width="480" gs_op="getselectgoods" nc_type="ztcgselector" type="text" name="ztc_gname" id="ztc_gname"/>
          <input type="hidden" id="goods_id" name="goods_id"/>
        </dd>
      </dl>
      <dl>
        <dt class="required"><em class="pngFix"></em><?php echo $lang['store_ztc_add_usegold'].$lang['nc_colon']; ?></dt>
        <dd>
          <div style="float:left;">
            <input class="w6 text" type="text" name="ztc_goldnum" id="ztc_goldnum" />
          </div>
          &nbsp;&nbsp;<?php echo $lang['store_ztc_add_havegold_text']; ?> 
          &nbsp;<font style="font-weight:bold;"><?php echo $output['member_array']['member_goldnum'];?></font>&nbsp;<?php echo $lang['store_ztc_goldunit']; ?>
          <input type="hidden" name="goldnumall" id="goldnumall" value="<?php echo $output['member_array']['member_goldnum'];?>" />
        </dd>
      </dl>
      <dl id="starttime_div">
        <dt class="required"><em class="pngFix"></em><?php echo $lang['store_ztc_starttime'].$lang['nc_colon']; ?></dt>
        <dd>
          <input name="ztc_stime" id="ztc_stime" type="text" class="text"/>
          <input name="ztc_nowdate" id="ztc_nowdate" type="hidden" value="<?php echo $output['nowdate'];?>"/>
        </dd>
      </dl>
      <dl>
        <dt id="group_intro"> <?php echo $lang['store_ztc_add_remark'].$lang['nc_colon']; ?></dt>
        <dd>
          <textarea class="w400" name="ztc_remark" rows="3" id="ztc_remark"></textarea>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['store_ztc_add_paygold'].$lang['nc_colon']; ?></dt>
        <dd>
          <input type="radio" name="ztc_paystate" id="ztc_paystate_0" checked="checked" value='0'/>
          &nbsp;<?php echo $lang['store_ztc_add_paygold_later']; ?>&nbsp;&nbsp;
          <input type="radio" name="ztc_paystate" id="ztc_paystate_1" value='1'/>
          &nbsp;<?php echo $lang['store_ztc_add_paygold_now'];?>
        </dd>
      </dl>
      <dl>
        <dt>&nbsp;</dt>
        <dd>
          <input type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>" />
        </dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script>
function ztctype_change(){
	if($("[name=ztc_type]:checked").val() == 1){
		
		$("#starttime_div").hide();
		$("#ztc_stime").val('');
	}else{
		$("#starttime_div").show();
	}
	$("#goods_id").val('');
	$("#ztc_gname").val('');
}
$(function(){
	
	ztctype_change();

	$('#ztc_stime').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('*[nc_type="ztcgselector"]').click(function(){
	    var id = $(this).attr('gs_id');
	    var callback = $(this).attr('gs_callback');
	    var op = $(this).attr('gs_op');
	    var title = $(this).attr('gs_title') ? $(this).attr('gs_title') : '';
	    var width = $(this).attr('gs_width');
	    var t = $("[name=ztc_type]:checked").val();
	    ajax_form(id, title, SITE_URL + '/index.php?act=store_ztc&op=' + op + '&dialog=1&title=' + title + '&id=' + id + '&callback=' + callback +'&t= '+ t, width);
	    return false;
	});

	jQuery.validator.addMethod("greater", function(value, element, param) {
		var comparetext = $.trim($(param).val());
		var t = $("[name=ztc_type]:checked").val();
		if(t == '0'){
			if(value == ''){return false;}else{
				if(value && comparetext){return comparetext < value;}else{return true;}
			}
		}else{ return true;}
	}, "<?php echo $lang['store_ztc_add_starttime_error'];?>");
	
	$('#ztc_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
    	submitHandler:function(form){
    		ajaxpost('ztc_form', '', '', 'onerror'); 
    	},
        rules : {
            goods_id      : {
                required     : true,
                min    : 1
            },
            ztc_goldnum :{
                required   :true,
                min    :1,
                max    :<?php echo $output['member_array']['member_goldnum'];?>
            },
            ztc_remark : {
                maxlength   : 100
            },
            ztc_stime : {
            	greater   : "#ztc_nowdate"
            }
        },
        messages : {
            goods_id      : {
                required:  '<?php echo $lang['store_ztc_add_search_goodserror'];?>',
                min   : '<?php echo $lang['store_ztc_add_search_goodserror'];?>'
            },
            ztc_goldnum		: {
            	required   :'<?php echo $lang['store_ztc_add_goldnum_nullerror'];?>',
                min    :'<?php echo $lang['store_ztc_add_goldnum_minerror'];?>',
                max    :'<?php echo $lang['store_ztc_add_goldnum_maxerror'];?>'
            },
            ztc_remark       : {
            	maxlength    : '<?php echo $lang['store_ztc_add_remarkerror'];?>'
            }
        }
    });
}); 
</script>