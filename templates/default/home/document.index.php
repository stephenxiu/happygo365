<?php defined('haipinlegou') or exit('Access Invalid!');?><style type="text/css">.default{font-size:15px;}.default p{line-height:20px;}.default strong{font-size:18px;/*color:red;*/font-weight:bold;}.default li{font-size:15px;line-height:30px;}</style><div class="article_con" style="margin:0 auto 20px;width:732px">    <?php include template('home/cur_local');?>	<div>		<h1 style="color:red;font-size:20px;font-weight:bold;text-align:center;margin-bottom:20px"><?php echo $output['doc']['doc_title'];?></h1>		<h2 style="text-align:center;font-size:15px;font-weight:bold;margin-bottom:15px"><?php echo date('Y-m-d H:i',$output['doc']['doc_time']);?></h2>		<div class="default">			<p><?php echo nl2br($output['doc']['doc_content']);?></p>		</div>	</div></div>