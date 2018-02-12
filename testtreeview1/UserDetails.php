<?php
session_start();
if (isset($_SESSION['admin']))
{

?>
<?
require_once("autoload.php") 
?>

<? // MPage::BeginBlock() ?>
<?php include "head.php"; ?>
<? // MPage::EndBlock("head") ?>

<? // MPage::BeginBlock() ?>
<div id="wrapper">
    <div id="content">

<?php

require "class._database.php";

$db=new _database();
$conn = $db->connect();


$username='';
$password='';
$fname='';
$lname='';
$address='';
$contactnumber='';
$isadmin='';
$is_admin='';
$idtype='';
$idcode='';
$documents='';
$emailid='';
$userid='';
$addnew='';
$searchquery='SELECT userdetailid,fname,lname,isadmin from userdetail  ORDER BY `IsAdmin` DESC, `fname` ASC';


	if (isset($_POST['save'])) // -----------------------------------------------------------------SAVE
	{
		$tmp = implode('',$_POST);
		$userdetailhash = md5($tmp);
		if(isset($_SESSION["userdetailhash"]) && $_SESSION["userdetailhash"] == $userdetailhash)
		{
			
		}
		else
		{
			$_SESSION["userdetailhash"] = $userdetailhash;

			if (isset($_POST['userid']))
				$userid = $_POST['userid'];
			if (isset($_POST['username']))
				$username = $_POST['username'];
			if (isset($_POST['password']))
				$password=$_POST['password'];
			if (isset($_POST['fname']))
				$fname=$_POST['fname'];
			if (isset($_POST['lname']))
				$lname=$_POST['lname'];
			if (isset($_POST['emailid']))
				$emailid=$_POST['emailid'];
			if (isset($_POST['contactnumber']))
				$contactnumber=$_POST['contactnumber'];
			if (isset($_POST['address']))
				$address=$_POST['address'];
			if (isset($_POST['idtype']))
				$idtype=$_POST['idtype'];
			if (isset($_POST['idcode']))
				$idcode=$_POST['idcode'];
			if (isset($_POST['documents']))
				$documents=$_POST['documents'];
			if (isset($_POST['isaddnew']))
				$addnew=$_POST['isaddnew'];

			$isadmin = isset($_POST['isadmin']) && $_POST['isadmin'] ? '1' : '0' ;
			$is_admin=$isadmin;
			if($isadmin == '1') 
				$isadmin = 'checked';
			else
				$isadmin = 'unchecked';
		
			
			if ($addnew=="1")
			{
				$qry="SELECT max(userdetailid) as 'newid' FROM userdetail";
				if ($result = mysqli_query($conn, $qry))
				{
					$row = mysqli_fetch_assoc($result);
					$newid = $row['newid']+1;
					$qry="INSERT INTO  `restaurantpos`.`userdetail` (`UserDetailId` ,`Fname` ,`Lname` ,`Email` ,`Password` ,`Address` ,`Phone` ,`IdentityType` ,`IdentityCode` ,`Documents` ,`IsAdmin`)VALUES (".$newid .",'".$fname ."','".$lname."','".$emailid."','".$password."','".$address ."',".$contactnumber.",'".$idtype ."','".$idcode ."','".$documents."',".$is_admin.")";
					if ($result = mysqli_query($conn, $qry))
					{
					
					}	
					else
						echo mysqli_error($conn);
				}
				else
					echo mysqli_error($conn);
			}
			else
			{
				$qry= "UPDATE `userdetail` SET `Fname`='".$fname."',`Lname`='".$lname."',`Email`='".$emailid."',`Password`='".$password."',`Address`='".$address."',`Phone`=".$contactnumber.",`IdentityType`='".$idtype."',`IdentityCode`='".$idcode."',`Documents`='".$documents."',`IsAdmin`=".$is_admin." WHERE userdetailid=".$userid;
				if ($result = mysqli_query($conn, $qry))
				{
				}	
				else
					echo mysqli_error($conn);
			}
			
			$username='';
			$password='';
			$fname='';
			$lname='';
			$address='';
			$contactnumber='';
			$isadmin='unchecked';
			$idtype='';
			$idcode='';
			$documents='';
			$emailid='';
			$addnew=1;
			unset($_POST['save']);
		}
	}
		
	else if (isset($_POST['cancel']))
	{

		$username='';
		$password='';
		$fname='';
		$lname='';
		$address='';
		$contactnumber='';
		$isadmin='unchecked';
		$idtype='';
		$idcode='';
		$documents='';
		$emailid='';
		$addnew=1;
		unset($_POST['cancel']);
	}
	else if (isset($_POST['delete']))
	{
		$tmp = implode('',$_POST);
		$userdetailhash = md5($tmp);
		if(isset($_SESSION["userdetailhash"]) && $_SESSION["userdetailhash"] == $userdetailhash)
		{
		}
		else
		{
			if (isset($_POST['userid']))
				$userid = $_POST['userid'];
			$qry = "DELETE from userdetail where userdetailid=" .$userid;
			if ($result = mysqli_query($conn, $qry))
			{
			}
			else
				echo mysqli_error($conn);
			$addnew=1;
		}
	}
	else if (isset($_POST['search']))
	{
		if (isset($_POST['searchstring']))
		$searchstring = $_POST['searchstring'];
		if ($searchstring=="")
			$searchquery="SELECT userdetailid,fname,lname,isadmin from userdetail  ORDER BY `IsAdmin` DESC, `fname` ASC";
		else
			$searchquery = "Select userdetailid,fname,lname,isadmin from userdetail where fname like '%". $searchstring ."%' or lname like '%". $searchstring ."%' or email like '%". $searchstring ."%' or identitycode like '%". $searchstring ."%'  ORDER BY `IsAdmin` DESC, `fname` ASC";
		
		//echo "search :" . $searchquery;
		unset($_POST['search']);
		$addnew=1;
	}
	else if (isset($_POST['showall']))
	{
		$searchquery="SELECT userdetailid,fname,lname,isadmin from userdetail  ORDER BY `IsAdmin` DESC, `fname` ASC";
	}
	else if (isset($_POST['change']))
	{
		if (isset($_POST['userid']))
			$userid = $_POST['userid'];
		if (isset($_POST['password']))
			$password=$_POST['password'];
		$qry= "UPDATE `userdetail` SET `Password`='".$password."' WHERE userdetailid=".$userid;
		if ($result = mysqli_query($conn, $qry))
		{
			//echo $qry;
		}	
		else
			echo mysqli_error($conn);
	}
	else if (isset($_REQUEST['userid']))
	{
		$userid = $_REQUEST['userid'];
		
		$qry = 'SELECT fname,lname,password,address,phone,isadmin,identitytype,identitycode,email,documents from userdetail where userdetailid='.$userid ;
		if ($result = mysqli_query($conn, $qry))
		{
			
			$row = mysqli_fetch_assoc($result);
			
			$password=$row['password'];
			$fname=$row['fname'];
			$lname=$row['lname'];
			$address=$row['address'];
			$contactnumber=$row['phone'];
			
			$isadmin = $row['isadmin'];
			if($isadmin == '1') 
				$isadmin = 'checked';
			else
				$isadmin = 'unchecked';
			
			$idtype=$row['identitytype'];
			$idcode=$row['identitycode'];
			$documents=$row['documents'];
			$emailid=$row['email'];
			
		}
		$addnew=2;
		unset($_REQUEST['userid']);
	}
	

?>
<html>
<head>

<link rel="stylesheet" type="text/css" href="style1.css" media="screen"/>
<script src="jquery.js">
	</script>
<script language="javascript">

		function changepassword()
		{
			document.getElementById("changepassworddiv").style.display="block";
		}
		function confirmchange()
		{
			var pass1 = document.getElementById("newpass").value;
			var pass2 = document.getElementById("retypepass").value;
			if (pass1==pass2 && pass1!="")
			{
				document.getElementById("changepassworddiv").style.display="none";
				document.getElementById("password").value=pass1;
				return true;
			}
			else
			{
				alert("Password fields are blank or mismatched");
				return false;
			}
			
		}
		
		function validatesave()
		{
			var msg="";
			var x;
			x=document.getElementById("fname");
			if (x.value=="") {
				msg="First name is invalid \n";
				x.style.border='2px solid red';
			}
			else
				x.style.border='';
			x=document.getElementById("lname");
			if (x.value=="") {
				msg+="Last name is invalid \n";
				x.style.border='2px solid red';
			}
			else
				x.style.border='';
			x=document.getElementById("password");
			if (x.value=="") {
				msg+="Password is invalid \n";
				x.style.border='2px solid red';
			}
			else if (x.value.length<6 || x.value.length>15)
			{
				msg+="Password must contain 6 to 15 characters \n";
				x.style.border='2px solid red';
			}
			else
				x.style.border='';
			var mailformat =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			x=document.getElementById("emailid");
			if (!mailformat.test(x.value)) {
				msg+="email is invalid \n";
				x.style.border='2px solid red';
			}
			else
				x.style.border='';
			
				
			x=document.getElementById("contactnumber");
			
			if (x.value=="" ) {
				msg+="contactnumber is invalid \n";
				x.style.border='2px solid red';
			}
			else if (isNaN(x.value))
			{
				msg+="contactnumber must be in numbers \n";
				x.style.border='2px solid red';
			}
			else if (x.value.length != 12)
			{
				msg+="contactnumber must be of 12 digits \n";
				x.style.border='2px solid red';
			}
			else
				x.style.border='';
				
			x=document.getElementById("idtype");
			if (x.value=="") {
				msg+="Identity type is invalid \n";
				x.style.border='2px solid red';
			}
			else
				x.style.border='';
			x=document.getElementById("idcode");
			if (x.value=="") {
				msg+="Identity code is invalid \n";
				x.style.border='2px solid red';
			}
			else
				x.style.border='';
			
			if (msg=="")
				return true;
			else
			{
				alert (msg);
				return false;
			}
		}	

		function pcancel1()
		{
			
			document.getElementById("changepassworddiv").style.display="none";
		}
		
</script>
</head>
<body>
<script>
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("userdetail").className="current";
	document.getElementById("reports").className="";
	document.title = "User Details";
</script>
<form name="userdetails" id="userdetails" action="UserDetails.php" method="post" >
<input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>" />
<input type="hidden" name="isaddnew" id="isaddnew" value="<?php echo $addnew; ?>" />
<center>
<div style=" width: 700px; ">
	<div style=" width: 300px; float: left; ">
		<table style=" width: 300px;" border=2> 
			<caption>Registered Users <br> 
				<input type="submit" name="search" value="Show All" >  
				<input type = "text" name="searchstring" />
				<input type="submit" name="search" value="" style="background-image: url(search.png);width: 40;background-size: 55%;background-repeat: no-repeat;background-position: center;">  
			</caption>
			
			<?php
				//$qry = 'SELECT userdetailid,fname,lname,isadmin from userdetail  ORDER BY `IsAdmin` DESC, `fname` ASC';
				$qry = $searchquery;
				if ($result = mysqli_query($conn, $qry))
				{
					while ($row = mysqli_fetch_assoc($result))
					{  ?>
						<tr><td><a href="UserDetails.php?userid=<?php echo $row['userdetailid'] ?>" > <?php echo $row['fname'] . ' ' . $row['lname']	?> </a></td>
							<td> <font color="red">
								<?php if ($row['isadmin'] == 1)
									{ echo 'Admin'; }
									else {
									 echo ' &nbsp;';
									}?>
							</font> </td>
						</tr>
					<?php
					}
				} 
				
			?>
			</table>
	</div>
	
<div style=" width: 380px; float: right; ">
<table  >
		<caption>User Details </caption>
		
		<tr>
			<td style=" width: 110px;"> First Name </td>
			<td><input type="text" size="20" id="fname" name="fname" value = "<?php echo $fname; ?>"/> </td>
		</tr>
		<tr>
			<td style=" width: 110px;"> Last Name </td>
			<td><input type="text" size="20" id="lname" name="lname" value = "<?php echo $lname; ?>"/> </td>
			
		</tr>
		<tr>
			<td style=" width: 110px;"> Password </td>
			<td> <input type="password" size="20" id="password" name="password" value = "<?php echo $password; ?>" readonly/>
			</td>
		</tr>
		
		<tr>
			<td style=" width: 110px;"></td><td>
			
			<a href="#" onclick="changepassword()"> change password </a> 
			<div name="changepassworddiv" id="changepassworddiv" style="display:none">
			<table> <tr><td>New Password : </td> <td> <input type="password" size="20" id="newpass" name="newpass" value = ""/></td></tr>
			 <tr><td>Retype Password :</td> <td> <input type="password" size="20" id="retypepass" name="retypepass" value = ""/></td></tr>
			<tr><td><input type="button" name="change" id="change" value="Change Password" onclick="confirmchange()" style="width: 130;"/></td>
				<td><input type="button" name="pcancel" id="pcancel" value="Cancel" onclick="pcancel1()"/></td>
			</tr></table>
			</div>
			
			</td>
		</tr>
		
		<tr>
			<td style=" width: 110px;" colspan=2> <input type="checkbox" name="isadmin" id="isadmin" value="1" <?php echo $isadmin; ?>/> Is Admin  ?</td>
		</tr>
		<tr>
			<td style=" width: 110px;"> Email Id </td>
			<td><input type="text" size="20" id="emailid" name="emailid" value = "<?php echo $emailid; ?>"/> </td>
		</tr>
		<tr>
			<td style=" width: 110px;"> Address </td>
			<td><textarea id="address" name="address" style="width: 155px;height: 70px;resize: none;"><?php echo $address; ?></textarea></td>
		</tr>
		<tr>
			<td style=" width: 110px;"> Contact Number </td> 
			<td><input type="text" size="20" id="contactnumber" name="contactnumber" value = "<?php echo $contactnumber; ?>"/> </td>
		</tr>
		<tr>
			<td style=" width: 110px;"> Identity type </td> 
			<td><input type="text" size="20" id="idtype" name="idtype" value="<?php echo $idtype; ?>"/> </td>
		</tr>
		<tr>
			<td style=" width: 110px;"> Identity Code </td> 
			<td><input type="text" size="20" id="idcode" name="idcode" value="<?php echo $idcode; ?>"/> </td>
		</tr>
		<tr>
			<td style=" width: 110px;"> Documents </td>
			<td><input type="text" size="20" id="documents" name="documents" value="<?php echo $documents; ?>"/></td>
		</tr>

		<tr>
			<td></td>
			<td><center><input type="Submit" value="Save" name="save" onclick="javascript:return validatesave()"/>
			<input type="Submit" value="Cancel" name="cancel"/>
			<?php
			if ($addnew==2) {
			?>
 				<input type="Submit" value="Delete" name="delete"/></td>
			<?php }
			?>
			</center></td>
		</tr>
</table>
</div></div></center>
</form>

</div>
<? // MPage::EndBlock("body") ?>

<? // MPage::BeginBlock() ?>

<?php include "sidebar.php"; ?>
<? // MPage::EndBlock("sidebar") ?>

<? // MPage::BeginBlock() ?>

<?php include "foot.php"; ?>
<? // MPage::EndBlock("footer") ?>
</body></html>
<?php
}
else
{
header("Location: /");
}

?>