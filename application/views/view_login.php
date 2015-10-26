

<div id=login_form style=''>

<form action="" method="post" >
<table class="login" style=''>
	<tr class="header">
		<th colspan="2" style="border-bottom: 1px solid #7ACEF4; ">Login please</th>
	</tr>
	<tr>
		<td>Login:</td>
		<td align=right><input type="text" name="login" style='width: 200px; margin-right: 5px;'></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td align=right><input type="password" name="password" style='width: 200px; margin-right: 5px;'></td>
	</tr>
	<th colspan="2" style="text-align: right">      
	   <input type="submit" value="Login" name="btn"	style="width: 70px; height: 30px; margin-right: 5px;">
  </th>
</table>

</form>


<?php extract($data); ?>
<?php if($login_status=="access_granted") {  ?>
<p style="color:green; margin-left:5px;">successful.</p>
<?php } elseif($login_status=="access_denied") {  ?>
<p style="color:red; margin-left:5px;">wrong login/pasword</p>
<?php } ?>
</div>


<script>
    $(document).ready(function(){
        //alert($(document).height() );
        $('#login_form').css('top',($(document).height()-$('#login_form').height())/3 );
        $('#login_form').css('left',($(document).width()-$('#login_form').width())/2 );
    });

</script>