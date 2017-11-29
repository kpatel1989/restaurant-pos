<?php
  session_start();

if (isset($_SESSION['admin']))
{
?>
<?php
require "class._database.php";

$db=new _database();
$conn = $db->connect();
if(isset($_POST['sett']))
{
	$result = mysqli_query($conn, "update theme set isset=0 where 1");
	if ($result = mysqli_query($conn, "update theme set isset=1 where themename='".$_POST['sett']."'"))
	{}
	else
		echo mysqli_error($conn);
	
	
}
else
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


$themename = "";
$result = mysqli_query($conn, "Select themename from theme where isset=1");
$row = mysqli_fetch_assoc($result);
$themename = $row['themename'];
?>
<html>
<head>
<script language="javascript">

	function changetheme(obj)
	{
		var ele = document.forms['themesform']['theme'];
		for (i=0; i<ele.length; i++) {
			ele[i].checked = false;
		}
		obj.checked = true;
		var request = $.ajax({
			url: "Themes.php",
			type: "post",
			data: "sett="+obj.id,
			success: function(data){
			}
		});
	}
</script>
</head>
<body>
<script>
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("themes").className="current";
	document.getElementById("settings").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("reports").className="";
	document.title = "Themes";
</script>

<center>
	
	<form name="themesform" id="themesform">
	<table>
	<tr> 
		<td> <div style="">
				<div style=""> <center> <img src="./t1.png" style=" width: 150px; height: 100px; "> </center></div>
				<div style=""><center> Black <br> <input type="checkbox" name="theme" id="black" onchange="changetheme(this)"> Set this theme.</center> </div>
			</div></td>
		<td> <div style="">
				<div style=""> <center> <img src="./t2.png" style=" width: 150px; height: 100px; "></center></div>
				<div style=""><center> Pruple <br> <input type="checkbox" name="theme" id="purple" value="1" onchange="changetheme(this)"> Set this theme.</center> </div>
			</div></td>
		<td> <div style="">
				<div style=""> <center> <img src="./t3.png" style=" width: 150px; height: 100px; "></center></div>
				<div style=""><center> Orange <br> <input type="checkbox" name="theme" id="orange" onchange="changetheme(this)"> Set this theme.</center> </div>
			</div></td></tr>
		<tr> <td> <div style="">
				<div style=""> <center> <img src="./t4.png" style=" width: 150px; height: 100px; "></center></div>
				<div style=""><center> Blue <br> <input type="checkbox" name="theme" id="blue" onchange="changetheme(this)"> Set this theme.</center> </div>
			</div></td>
		<td> <div style="">
				<div style=""> <center> <img src="./t5.png" style=" width: 150px; height: 100px; "></center></div>
				<div style=""><center> Green <br> <input type="checkbox" name="theme" id="green" onchange="changetheme(this)"> Set this theme.</center> </div>
			</div></td>
		<td> <div style="">
				<div style=""><center>  <img src="./t6.png" style=" width: 150px; height: 100px; "></center></div>
				<div style=""><center> Yellow <br> <input type="checkbox" name="theme" id="yellow" onchange="changetheme(this)"> Set this theme.</center> </div>
			</div></td></tr>
		<tr> <td> <div style="">
				<div style=""> <center> <img src="./t7.png" style=" width: 150px; height: 100px; "></center></div>
				<div style=""><center> Red <br> <input type="checkbox" name="theme" id="red" onchange="changetheme(this)"> Set this theme.</center> </div>
			</div></td>
		</tr>
		<script>
			document.getElementById("<?php echo $themename?>").checked=true;
		</script>
	</table>
	</form>
</center>


</body>
</html>


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

}
else
{
header("Location: /");
}

?>