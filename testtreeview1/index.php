<?php
  session_start();
	
if (isset($_SESSION['admin']))
{

header("Location: /menuitems.php");
}
elseif (isset($_POST['login']))
{
	var_dump($_POST);
	require "class._database.php";
	$db=new _database();
	$conn = $db->connect();
	$usrnm="";
	if ($result = mysqli_query($conn, "Select userdetailid from userdetail where fname ='".$_POST["username"]."' and password='".$_POST['password']."'"))
	{
		$row = mysqli_fetch_assoc($result);
		$usrnm = $row['userdetailid'];
	}
	else
		echo mysqli_error($conn);
	
	if ($usrnm=="")
	{
		?> <script> alert("Invalid Username/password"); </script> <?php
	}
	else
	{
		$_SESSION['admin']=$usrnm;
		header("Location: /menuitems.php");
	}
}
else
{
	echo "AsdadaS";
}
?>

<html>
<head>
<title>Restaurant POS</title>
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" title="default" />
<script src="js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>
<script src="js/jquery/custom_jquery.js" type="text/javascript"></script>
<script src="js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
</head>
<body id="login-bg"> 



 <form action="index.php" method="post">
<div id="login-holder">

	<div id="logo-login">
		<a href="index.html"></a>
	</div>
	
	<div class="clear"></div>
	
	<div id="loginbox">
	<div id="login-inner">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Username</th>
			<td><input type="text"  class="login-inp" name="username"/></td>
		</tr>
		<tr>
			<th>Password</th>
			<td><input type="password" value="************"  onfocus="this.value=''" class="login-inp" name="password"/></td>
		</tr>
		
		<tr>
			<th></th>
			<td><input type="submit" class="submit-login" name="login" /></td>
		</tr>
		</table>
	</div>
	<div class="clear"></div>
	<a href="" class="forgot-pwd"></a>
 </div>
 
	<div id="forgotbox">
		<div id="forgotbox-text">Please send us your email and we'll reset your password.</div>
		<div id="forgot-inner">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Email address:</th>
			<td><input type="text" value=""   class="login-inp" /></td>
		</tr>
		<tr>
			<th> </th>
			<td><input type="Submit" class="submit-login" name="forgot"/></td>
		</tr>
		</table>
		</div>
		<div class="clear"></div>
		<a href="" class="back-login">Back to login</a>
	</div>

</div>
</form>
</body>
</html>