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
?>
<html>
<head>
</head>
<body>
<script>
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("reports").className="current";

	document.title = "Reports";
</script>

<a href="InvoiceReport.php"> Invoice Report </a>
<br/>
<a href="UserLogReport.php"> User Log </a>

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
<?php
}
else
{
header("Location: /");
}

?>