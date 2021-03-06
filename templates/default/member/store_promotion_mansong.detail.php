<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
  <table class="ncu-table-style" style="border:1px solid #e6e6e6;margin:20px 0;height:60px;line-height:60px;">
    <tbody>
      <tr>
        <td class="w90 tr" style="padding:0 20px 0 10px;"><strong style="font-size:14px;font-weight:bold;"><?php echo $lang['mansong_name'].$lang['nc_colon'];?></strong><?php echo $output['mansong_info']['mansong_name'];?></td>        <!--
        <td class="w120 tl"><?php echo $output['mansong_info']['mansong_name'];?></td>        -->
        <td class="w90 tr" style="padding:0 20px 0 10px;"><strong style="font-size:14px;font-weight:bold;"><?php echo $lang['start_time'].$lang['nc_colon'];?></strong><?php echo date('Y-m-d',$output['mansong_info']['start_time']);?></td>        <!--
        <td class="w120 tl"><?php echo date('Y-m-d',$output['mansong_info']['start_time']);?></td>        -->
        <td class="w90 tr" style="padding:0 20px 0 10px;"><strong style="font-size:14px;font-weight:bold;"><?php echo $lang['end_time'].$lang['nc_colon'];?></strong><?php echo date('Y-m-d',$output['mansong_info']['end_time']);?></td>        <!--
        <td class="w120 tl"><?php echo date('Y-m-d',$output['mansong_info']['end_time']);?></td>        -->
        <td class="w90 tr" style="padding:0 20px 0 10px;"><strong style="font-size:14px;font-weight:bold;"><?php echo $lang['nc_state'].$lang['nc_colon'];?></strong><?php echo $output['state_list'][$output['mansong_info']['state']];?></td>        <!--
        <td class="tl"><?php echo $output['state_list'][$output['mansong_info']['state']];?></td>        -->
      </tr>
    </tbody><!--
    <tfoot>
      <tr>
        <td colspan="20"></td>
      </tr>
    </tfoot>-->
  </table>
  <table class="ncu-table-style" style="border:1px solid #e6e6e6">
    <thead style="height:40px;line-height:40px;font-size:15px;font-weight:bold;">
      <tr>
        <th><?php echo $lang['text_level'];?></th>
        <th><?php echo $lang['level_price'];?></th>
        <th><?php echo $lang['shipping_free'];?></th>
        <th><?php echo $lang['level_discount'];?></th>
        <th class="w90"><?php echo $lang['gift_name'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list']) && is_array($output['list'])){?>
      <?php foreach($output['list'] as $key=>$val){?>
      <tr class="bd-line" style="text-align:center;">
        <td><?php echo $val['level'];?></td>
        <td><?php echo $val['price'];?></td>
        <td><?php echo empty($val['shipping_free'])?$lang['nc_no']:$lang['nc_yes'];?></td>
        <td><?php echo empty($val['discount'])?$lang['text_not_join']:$val['discount'];?></td>
        <td><?php if(empty($val['gift_name'])) { ?>
          <span><?php echo $lang['text_not_join'];?></span>
          <?php } else { ?>
          <?php if(!empty($val['gift_link'])) { ?>
          <a href="<?php echo $val['gift_link'];?>" target="_blank"><?php echo $val['gift_name'];?></a>
          <?php } else { ?>
          <span><?php echo $val['gift_name'];?></span>
          <?php } ?>
          <?php } ?></td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr>
        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      <?php }?>
    <tbody>
    <tfoot>
      <?php if(!empty($output['list']) && is_array($output['list'])){?>
      <tr>
        <th colspan="16"> <div class="pagination"> <?php echo $output['show_page']; ?> </div>
        </th>
      </tr>
      <?php }?>
    </tfoot>
  </table>
</div>
