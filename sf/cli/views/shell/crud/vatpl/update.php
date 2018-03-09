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



<div class="box">
	<div class="content">
		{include file="left_menu.html"}
		<div class="main">
			<div class="part3">
				<div class="title">
					<div class="left"><span><?php echo $modelClass; ?></span></div>
					<div class="right"></div>
				</div>
				<div class="title2">
						<div class="left"><span>属性设置</span></div>
						<div class="right"></div>
				</div>
				<div class="cont1">
				<form id="mainForm" action="{$phpSelf}?r=<?php echo $controllerId ?>/update&id={$model.<?php echo $ID ?>}" style="margin:0px;padding:0px;" method="POST">
					<div class="left">
							<?php foreach($columns as $column): ?>
							
							<div class="a01"><?php echo $column; ?></div>
							<div class="a02"><input name="<?php echo $modelClass; ?>[<?php echo $column; ?>]" id="<?php echo $column; ?>" type="text" style="width:290px;" value="{$model.<?php echo $column; ?>}" /></div>
							<?php endforeach; ?>
							
							<div class="a01">大图</div>
							<div class="a02"><input name="" type="file" /></div>
							<div class="a01">中图</div>
							<div class="a02"><input name="" type="file" /></div>
							<div class="a01">小图</div>
							<div class="a02"><input name="" type="file" /></div>
						</div>
						<div class="right">
							<div class="big">
								<div class="a01">大图浏览</div>
								<div class="a02"><img src="../images/logo_r2.jpg" width="160" height="60" /></div>
							</div>
							<div class="middle">
								<div class="a01">中图浏览</div>
								<div class="a02"><img src="../images/logo_r2.jpg" width="120" height="45" /></div>
							</div>
							<div class="small">
								<div class="a01">小图浏览</div>
								<div class="a02"><img src="../images/logo_r2.jpg" width="60" height="23" /></div>
							</div>
						</div>
					</div>
					{*根据需要增加
					<div class="title2">
						<div class="left"><span>所属业务包</span></div>
						<div class="right"></div>
					</div>
					<div class="cont2">
						<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" >业务包类型1</td>
    <td width="150" ><a href="#!">编辑</a></td>
  </tr>
  <tr>
    <td>业务包类型1</td>
    <td><a href="#!">编辑</a></td>
  </tr>
  <tr>
    <td>业务包类型1</td>
    <td><a href="#!">编辑</a></td>
  </tr>
</table>

					</div>
					<div class="title2">
						<div class="left"><span>交叉销售设置</span></div>
						<div class="right"></div>
					</div>
					<div class="cont3">
						<div class="a01"><select>
						  <option>商务类</option>
						  <option>商务类2</option>
						</select></div>
						<div class="a02"><input type="text" value="关键字" style="width:90px;" /></div>
						<div class="a03"><input type="button" value="&nbsp;&nbsp;确定&nbsp;&nbsp;" />
						</div>
					</div>
					
					<div class="cont4">
						<div class="top">调整位置：<a href="#!"><img src="images/adv/icon_up.gif" width="12" height="15" /></a><a href="#!"><img src="images/adv/icon_down.gif" width="12" height="15" /></a></div>
						<div class="left">
							<ul>
								<li><input name="" type="checkbox" value="" />手机报</li>
							</ul>
						</div>
						<div class="middle">
							<div class="a01"><span class="button"><a href="#1">增加 &gt;&gt;</a></span></div>
							<div class="a02"><span class="button"><a href="#1">&lt;&lt; 删除</a></span></div>
						</div>
						<div class="right">
							<ul>
								<li><input name="" type="checkbox" value="" />手机报</li>
							</ul>
						</div>
					</div>
					*}
					<div class="cont5"><span><a href="#!">预览页面效果</a></span></div>
					<div class="cont6">
						<span class="button"><a href="javascript:void($('#mainForm').submit())">&nbsp;&nbsp;&nbsp;确定&nbsp;&nbsp;&nbsp;</a></span>
						<span class="button"><a href="javascript:history.go(-1)">&nbsp;&nbsp;&nbsp;取消&nbsp;&nbsp;&nbsp;</a></span>
					</div>
		  </div>
		</div>
	</div>
</div>

{include file="footer.html"}