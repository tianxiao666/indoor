<?php
/**
 * This is the template for generating the create view for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $modelClass: the model class name
 * - $columns: a list of column schema objects
 */
?>

{include file="header.html"}

<link href="css/adminstyle.css" rel="stylesheet" type="text/css" />

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainFrameTopline">
    <tr>
        <td width="200" align="left" class="nameChannel">&#187; 修改<?php echo $modelClass; ?></td>
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

<form action="{$phpSelf}?r=<?php echo $controllerId ?>/update&id={$model.<?php echo $ID ?>}" method="POST" id="<?php echo $modelClass; ?>">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainFrame">
    <tr>
        <td align="left" style="padding:20px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="50%" align="left" class="nameFunction"><img src="images/icon/m_articles.gif" border="0" align="absmiddle" /> 修改<?php echo $modelClass; ?></td>
					<td width="50%" align="right">&nbsp;</td>
				</tr>
			</table>
		    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="datalist">
	    		<?php foreach($columns as $column): ?>
	    		<tr>
	    			<td><?php echo $column; ?>:</td>
	    			<td>
	    				<input type="text" name="<?php echo $modelClass; ?>[<?php echo $column; ?>]" id="<?php echo $column; ?>" value="{$model.<?php echo $column; ?>}">
	    			</td>
	    		</tr>
				<?php endforeach; ?>
	    		<tr>
	    			<td align="center" colspan="2">
	    				<input type="submit" value="修改" >
	    				<input type="button" value="返回列表页" onclick="location.href='{$phpSelf}?r=<?php echo $controllerId ?>';" name="goback">
	    			</td>
	    		</tr>
            </table>
		</td>
    </tr>
</table>
</form>	 

<script language="javascript">{$validatJs}</script>
{include file="footer.html"}