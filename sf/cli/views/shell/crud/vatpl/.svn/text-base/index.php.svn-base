<?php
/**
 * This is the template for generating the index view for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $modelClass: the model class name
 * - $columns: a list of column schema objects
 */
?>

{include file="header.html"}

{* 如果不需要导出功能，可删除代码
{literal}
<script language="javascript" type="text/javascript">

function ExportLog()
{   
	var frm = document.search_form;
	frm.action = 'admin.php?r=<?php echo $controllerId ?>/Export';
	frm.submit();
}

</script>
{/literal}
*}

<div class="box">
	<div class="content">
		{include file="left_menu.html"}
		<div class="main">
			<div class="part1">
				<div class="title">
					<div class="left"><span><?php echo $modelClass; ?>管理</span></div>
					<div class="right"></div>
				</div>
				<div class="cont">
				<form id="search_form" name="search_form" method="post" action="{$phpSelf}?r=<?php echo $controllerId ?>" style="margin:0px;padding:0px;">
					<div class="a02"><?php echo $ID ?>：<input type="text" style="width: 180px;" id="<?php echo $ID ?>" name="<?php echo $ID ?>" value="{$condition.<?php echo $ID ?>}" /></div>
		            {* 方便自定义搜索条件
					<div class="a01"><select name="send_type">{html_options options=$allSendType selected=$condition.send_type}</select></div>
					<div class="a02">开始时间：<input type="text" style="width: 180px;" id="startDate" name="startDate" value="{$condition.startDate}" class="Wdate" onfocus="WdatePicker({literal}{readOnly:true}{/literal})" /></div>
                    <div class="a02">结束时间：<input type="text" style="width: 180px;" id="endDate" name="endDate" class="Wdate" value="{$condition.endDate}" onfocus="WdatePicker({literal}{readOnly:true}{/literal})" /></div>           
                     *}
					<div class="a03"><input type="submit" value="搜索业务"></div>
					<!-- <div class="a03"><input type="button" value="导出记录" onclick="ExportLog();"></div>  -->
				</form>
				</div>
			</div>
			<div class="d_line"></div>
			<div class="part2">
				<div class="cont2">
				<div style="color:#FF5000">查询结果({$page.total})</div>
				<table cellspacing="0" cellpadding="0" border="0">
				  <tbody>
				  <tr>
				  	<?php foreach($columns as $column): ?>
				    <th><?php echo $column; ?></th>
				    <?php endforeach; ?>
				    <th>操作</th>
				  </tr>
				  
				  {section name=i loop=$modelList}
				  <tr class="{cycle values='row0,row1'}">
				    <?php foreach($columns as $column): ?>
                	<td  align="center">{$modelList[i].<?php echo $column; ?>}</td>
					<?php endforeach; ?>
					<td>
						<a href="{$phpSelf}?r=<?php echo $controllerId ?>/update&id={$modelList[i].<?php echo $ID ?>}">修改</a>
						<a href="{$phpSelf}?r=<?php echo $controllerId ?>/delete&id={$modelList[i].<?php echo $ID ?>}" onclick="return confirm('你确定要删除这条记录吗？');">删除</a>
					</td>
				  </tr>
				  {/section}
				   
				</tbody>
			</table>
			</div>
			{include file="page_info.html"}

		  </div>
		</div>
	</div>
</div>

{include file="footer.html"}