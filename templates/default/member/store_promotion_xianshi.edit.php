<?php defined('haipinlegou') or exit('Access Invalid!');?>
<style>
    .xianshi_input {width: 60px;height: 20px}
</style>


<div class="wrap">

    <div class="tabmenu  trade-remind">

        <?php include template('member/member_submenu');?>

        <?php if($output['unpublish']) { ?>

            <a class="ncu-btn3" style="margin:12px auto 0" href="index.php?act=store_promotion_xianshi&op=choose_goods&xianshi_id=<?php echo $output['xianshi_info']['xianshi_id'];?>"><?php echo $lang['goods_add'];?></a>

        <?php } ?>

    </div>

    <table class="ncu-table-style">

        <tbody>

        <tr>

            <td class="w90 tr"><strong><?php echo $lang['xianshi_name'].$lang['nc_colon'];?></strong></td>

            <td class="w120 tl"><?php echo $output['xianshi_info']['xianshi_name'];?></td>

            <td class="w90 tr"><strong><?php echo $lang['start_time'].$lang['nc_colon'];?></strong></td>

            <td class="w120 tl"><?php echo date('Y-m-d',$output['xianshi_info']['start_time']);?></td>

            <td class="w90 tr"><strong><?php echo $lang['end_time'].$lang['nc_colon'];?></strong></td>

            <td class="w120 tl"><?php echo date('Y-m-d',$output['xianshi_info']['end_time']);?></td>

            <td class="w90 tr"><strong><?php echo $lang['nc_state'].$lang['nc_colon'];?></strong></td>

            <td class="tl"><?php echo $output['state_list'][$output['xianshi_info']['state']];?></td>

        </tr>

        <tr>

            <td class="tr"><strong><?php echo $lang['text_default'].$lang['xianshi_discount'].$lang['nc_colon'];?></strong></td>

            <td class="tl"><?php echo $output['xianshi_info']['discount'].$lang['nc_xianshi_flag'];?></td>

            <td class="tr"><strong><?php echo $lang['xianshi_quota_goods_limit'].$lang['nc_colon'];?></strong></td>

            <td class="tl"><?php echo $output['xianshi_info']['goods_limit'];?></td>

            <td class="tr"><strong><?php echo $lang['xianshi_goods_selected'].$lang['nc_colon'];?></strong></td>

            <td class="tl"><?php echo $output['xianshi_goods_count'];?></td>

            <td></td>

            <td></td>

        </tr>

        <tr> </tr>

        <tfoot>

        <tr>

            <td colspan="20"></td>

        </tr>

        </tfoot>

    </table>

    <div class="ncm-notes">

        <h3><?php echo $lang['nc_explain'];?><?php echo $lang['nc_colon'];?></h3>

        <ul>

            <li><?php echo $lang['xianshi_manage_goods_explain1'];?></li>

            <li><?php echo $lang['xianshi_manage_goods_explain2'];?></li>

        </ul>

    </div>
<form name="" method="post" action="index.php?act=store_promotion_xianshi&op=xianshi_goods_save">
    <table class="ncu-table-style">

        <thead>

        <tr>

            <th class="w10"></th>

            <th class="w70"></th>

            <th class="tl"><?php echo $lang['goods_name'];?></th>

            <th class="w90"><?php echo $lang['goods_store_price'];?></th>
            <th class="w90">限时折扣价</th>

            <th class="w120"><?php echo $lang['xianshi_discount'];?></th>

            <th class="w90"><?php echo $lang['nc_state'];?></th>

            <th class="w90"><?php echo $lang['nc_handle'];?></th>

        </tr>

        </thead>

        <?php if(!empty($output['list']) && is_array($output['list'])){?>

        <tbody>

        <?php foreach($output['list'] as $key=>$val){?>

            <tr class="bd-line">

                <td>&nbsp;</td>

                <td><div class="goods-pic-small"><span class="thumb size60"><i></i><a href="index.php?act=goods&goods_id=<?php echo $val['goods_id']; ?>" target="_blank"><img src="<?php echo cthumb($val['goods_image'],'tiny',$_SESSION['store_id']);?>" onload="javascript:DrawImage(this,60,60);" /></a></span></div></td>

                <td class="tl"><dl class="goods-name">

                        <dt><a href="index.php?act=goods&goods_id=<?php echo $val['goods_id']; ?>" target="_blank"><?php echo $val['goods_name']; ?></a></dt>

                        <td class="goods-price"><input class="xianshi_input" type="text" name="goods_store_price" value="<?php echo $val['goods_store_price'];?>" readonly="readonly"/></td>
                        <td class="goods-price">
                            <input class="xianshi_input" type="text" name="xianshi_price" value="<?php echo $val['xianshi_price']; ?>"/>
                            <input type="hidden" name="xianshi_goods_id" value="<?php echo $val['xianshi_goods_id']; ?>" />
                        </td>
                        <?php if($output['unpublish']) { ?>

                            <td class="ncm-promotion-xianshi-discount"><input class="xianshi_input" type="text" name="xianshi_discount" value="<?php echo empty($val['xianshi_discount'])?ncPriceFormat($val['discount']):ncPriceFormat($val['xianshi_discount']);?>"/><?php echo $lang['nc_xianshi_flag'];?></td>

                        <?php } else { ?>

                            <td><input class="xianshi_input" type="text" name="xianshi_discount" value="<?php echo empty($val['xianshi_discount'])?ncPriceFormat($val['discount']):ncPriceFormat($val['xianshi_discount']);?>"/><?php echo $lang['nc_xianshi_flag'];?></td>

                        <?php } ?>

                        <td><span><?php echo $output['xianshi_goods_state_list'][$val['state']];?></span></td>

                        <td>
                                    <?php if(intval($val['state']) !== intval($output['goods_state_cancel'])) { ?>
                                        <input type="submit" name="submit" value="保存" />
                                    <?php } ?>
                        </td>

            </tr>

        <?php }?>

        <?php }else{?>

            <tr>

                <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['xianshi_goods_none'];?></span></td>

            </tr>

        <?php }?>

        </tbody>

        <tfoot>

        <?php if(!empty($output['list']) && is_array($output['list'])){?>

            <tr>

                <td colspan="20"><div class="pagination"></div></td>

            </tr>

        <?php }?>

        </tfoot>

    </table>
</form>
    <div class="ncu-form-style tc mb30">

        <?php if($output['unpublish']) { ?>

            <input type="submit" class="submit" id="submit_publish" value="<?php echo $lang['xianshi_publish'];?>" onclick="javascript:if(confirm('<?php echo $lang['ensure_publish'];?>'))window.location='index.php?act=store_promotion_xianshi&op=xianshi_publish&xianshi_id=<?php echo $output['xianshi_info']['xianshi_id'];?>'">

        <?php } ?>

        <input type="submit" class="submit" id="submit_back" value="<?php echo $lang['nc_back'].$lang['xianshi_index'];?>" onclick="window.location='index.php?act=store_promotion_xianshi&op=xianshi_list'">

    </div>

</div>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.edit.js" charset="utf-8"></script>

