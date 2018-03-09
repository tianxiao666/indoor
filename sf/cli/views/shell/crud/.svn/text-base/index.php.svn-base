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

<link href="css/adminstyle.css" rel="stylesheet" type="text/css" />

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

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainFrameTopline">
    <tr>
        <td width="200" align="left" class="nameChannel">&#187; <?php echo $modelClass; ?>列表</td>
        <td align="right">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>	
					<td><img src="images/taboff_l.gif" border="0" /></td>
					<td background="images/taboff_bg.gif"><a href="{$phpSelf}?r=<?php echo $controllerId ?>" class="taboff"><img src="images/icon/l_system.gif" border="0" align="absmiddle" /><?php echo $modelClass; ?>列表</a></td>
					<td><img src="images/taboff_r.gif" border="0" /></td>
				</tr>
			</table>
		</td>
    </tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainFrame">
    <tr>
        <td align="left" style="padding:20px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="50%" align="left" class="nameFunction"><img src="images/icon/m_search.gif" border="0" align="absmiddle" /><?php echo $modelClass; ?>查询</td>
					<td width="50%" align="right">&nbsp;</td>
				</tr>
			</table>
			<table  border="0" cellspacing="0" cellpadding="0" class="formFrame">
                <tr>
                    <td align="left" style="padding:10px;">
						<form id="search_form" name="search_form" method="post" action="{$phpSelf}?r=<?php echo $controllerId ?>" style="margin:0px;padding:0px;">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr >
								<td class="formTitle" align="left"><?php echo $ID ?>：</td>
                                <td><input type="text" id="<?php echo $ID ?>" name="<?php echo $ID ?>" value="{$condition.<?php echo $ID ?>}"></td>
                                
                                {* 方便自定义搜索条件
                                <td  class="formTitle" align="left">类型：</td>
                                <td><select name="send_type">{html_options options=$allSendType selected=$condition.send_type}</select></td>
                                
								<td  class="formTitle" align="left">开始时间：</td>
                                <td><input type="text" id="startDate" name="startDate" value="{$condition.startDate}" class="Wdate" onfocus="WdatePicker({literal}{readOnly:true}{/literal})" /></td>
                                
                                <td height="25"  align="left" class="formTitle">结束时间：</td>
                                <td><input type="text" id="endDate" name="endDate" class="Wdate" value="{$condition.endDate}" onfocus="WdatePicker({literal}{readOnly:true}{/literal})" /></td>
                                *}
							</tr>
						</table>
					    <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="60" height="25" valign="bottom"><input type="submit" value="查询记录" style="font-size:12px;width:60px;height:20px;" /></td>
                                <td width="15"></td>
                                <!--<td width="60" valign="bottom"><input type="button" value="导出记录" style="font-size:12px;width:60px;height:20px;" onclick="ExportLog();" /></td>-->
                            </tr>
                        </table>
						</form>
					</td>
                </tr>
            </table>
			<br />
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="50%" align="left" class="nameFunction"><img src="images/icon/m_articles.gif" border="0" align="absmiddle" /> 查询结果({$page.total})</td>
					<td width="50%" align="right"><a href="{$phpSelf}?r=<?php echo $controllerId ?>/create">新增</a></td>
				</tr>
			</table>
		    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="datalist">
		    	
                <tr>
                	<?php foreach($columns as $column): ?>
                	<th align="center"><?php echo $column; ?></th>
					<?php endforeach; ?>
					<th align="center">操作</th>
                </tr>
				{section name=i loop=$modelList}
                <tr class="{cycle values='row0,row1'}">
                	<?php foreach($columns as $column): ?>
                	<td  align="center">{$modelList[i].<?php echo $column; ?>}</td>
					<?php endforeach; ?>
					<td  align="center">
						<a href="{$phpSelf}?r=<?php echo $controllerId ?>/update&id={$modelList[i].<?php echo $ID ?>}">修改</a>
						<a href="{$phpSelf}?r=<?php echo $controllerId ?>/delete&id={$modelList[i].<?php echo $ID ?>}" onclick="return confirm('你确定要删除这条记录吗？');">删除</a>
					</td>
                </tr>
                {/section}
            </table>
 
            {include file="page_info.html"}
		</td>
    </tr>
</table>

{include file="footer.html"}