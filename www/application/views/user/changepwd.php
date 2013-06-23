<?php

?>
<div>
	<form id="pwd_form" name="pwd_form" method="post">
	<table>
		<tr>
			<td>旧密码:</td>
			<td><input type="password" name="old" id="old">&nbsp;&nbsp;<span></span></td>
		</tr>
		<tr>
			<td>新密码:</td>
			<td><input type="password" name="new" id="new">&nbsp;&nbsp;<span></span></td>
		</tr>
		<tr>
			<td>新密码确认:</td>
			<td><input type="password" name="rnew" id="rnew">&nbsp;&nbsp;<span></span></td>
		</tr>
		<tr>
			<td></td>
			<td><a style="cursor:pointer" id="pwd_submit" >提交</a>&nbsp;&nbsp;<a href="<?php echo site_url('user');?>">个人中心</a></td>
		</tr>	
	</table>
	<input type="hidden" value="1" id="ajax" name="ajax" />
	<input type="hidden" value="<?php echo $username;?>" id="username" name="username" />
	</form>
</div>