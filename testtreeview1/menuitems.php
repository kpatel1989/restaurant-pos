<?php
  session_start();

if (isset($_SESSION['admin']))
{

?>

<?
	//require_once("autoload.php") 
?>

<? //// MPage::BeginBlock() ?>
<?php include "head.php"; ?>
<? //// MPage::EndBlock("head") ?>

<? //// MPage::BeginBlock() ?>
<div id="wrapper">
    <div id="content">
<div>
<?php
	function createTreeView($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
		echo " <ol class='treee'> ";
		foreach ($array as $categoryId => $parent) {
			if($parent['parent_id']==0){
				echo '<li > <label for="subfolder2" onClick="getData(this.id)" style="background: url(icon_folder.png) 15px 1px no-repeat;" id="'.$parent['name'].'@'.$parent['id'].'">'.$parent['name'].'</label> <input type="checkbox" name="subfolder2"/>';
				echo " <ol class='treee'> ";
				foreach ($array as $categoryId => $sub) {
					
					if ($sub['parent_id'] == $parent['id']){
						echo '<li> <label for="subfolder2" onClick="getData(this.id)" style="background: url(small_folder_icon.png) 15px 1px no-repeat;" id="'.$sub['name'].'@'.$sub['parent_id'].'">'.$sub['name'].'</label> <input type="checkbox" name="subfolder2"/>';
						$db=new _database();
						$conn = $db->connect();
						$qry3="SELECT * FROM items where category_id=".$sub['id'];
						$result3=mysqli_query($conn, $qry3);
						echo " <ol class='tree' style=\"width:200 \"> ";
						while($row = mysqli_fetch_assoc($result3)){
							echo '<li> <label for="subfolder2" onClick="getData(this.id)" style="background: url(folder-horizontal.png) 15px 1px no-repeat;" id="'.$row['item_id'].'^'.$sub['id'].'">'.$row['item_name'].'</label> <input type="checkbox" name="subfolder2"/></li>';
						}
						echo ' <li> <label for="subfolder2" style="background: url(folder-horizontal.png) 15px 1px no-repeat;" onClick="getData(this.id)" id="'.$sub['id'].'I">Add new Item.</label> <input type="checkbox" name="subfolder2"/></li>';
						echo "</ol>";
					}
				}
				echo '<li> <label for="subfolder2" style="background: url(\'small_folder_icon.png\') 15px 1px no-repeat;" onClick="getData(this.id)" id="'.$parent['id'].'">Add new.</label> <input type="checkbox" name="subfolder2"/></li>';
				echo "</ol> </li>";
			}
		}
		echo '<li> <label for=subfolder2" style="background: url(\'icon_folder.png\') 15px 1px no-repeat;" onClick="getData(this.id)" id="0">Add new.</label> <input type="checkbox" name="subfolder2"/></li>';
		echo "</ol>";
	
	}
?>
<head>
	<link rel="stylesheet" type="text/css" href="style1.css" media="screen"/>
	<script src="jquery.js">
	</script>
	<script type="text/javascript">
	
		$(document).ready(function(){
			$("#form").fadeOut(0);
		});
		
		function getData(id){
			//alert("data.php?name=" + id);
			document.getElementById('forrm').src = "data.php?name=" + id;
			 /* $.ajax({

					 type: "GET",
					 url: 'data.php',
					 data: "name=" + id, // appears as $_GET['id'] @ ur backend side
					 success: function(data) {
						   // data is ur summary
						  $('#form').html(data);
					 }

				   });*/
			$("#form").fadeIn(1000);
			
			 var links = document.getElementsByTagName("label");
				for(var i=0;i<links.length;i++)
				{
					
						links[i].style.color = "#808182";  
					
				} 
			 document.getElementById(id).style.color="#0000ff";
		}
		function uploadImg() {
			$.ajax({

					 type: "POST",
					 url: 'ItemDetails.php',
					 data: "images=1", // appears as $_GET['id'] @ ur backend side
					 success: function(data) {
						   // data is ur summary
						  $('#form').html(data);
					 }

				   });
		
		}
	</script>
</head>
<body id="treee">
<script>
	document.title = "Menu Items";
</script>
	<?php
	include "class._database.php";
	$db=new _database();
	$conn = $db->connect();
	$qry="SELECT * FROM categories";
	
	$result=mysqli_query($conn, $qry);
	$arrayCategories = array();
	$parentids = array();
	while($row = mysqli_fetch_assoc($result)){
		$arrayCategories[$row['id']] = array("parent_id" => $row['parent_id'], "name" => $row['name'],"id"=>$row['id']);
		$parentids[$row['id']] = $row['parent_id'];
	}
	// var_dump($arrayCategories);
	?>
<div id="content" style=" width:720px;" >
	<div style="float:left ; width:270px; ">
		<?php
		if(mysqli_num_rows($result)!=0)
		{
			createTreeView($arrayCategories, 0); 
		}
		?>
	</div>
	<div style="float:right; width:450px; " id="form" name="form">
		<iframe id="forrm" src="about:blank" style="border:none" width="450px" height="720px"></iframe>
	</div>
</div>
</body>
</html>
</div>  
</div>
<? //// MPage::EndBlock("body") ?>

<? //// MPage::BeginBlock() ?>

<?php include "sidebar.php"; ?>
<? //// MPage::EndBlock("sidebar") ?>

<? //// MPage::BeginBlock() ?>

<?php include "foot.php"; ?>
<? //// MPage::EndBlock("footer") ?>


<?php
}
else
{
header("Location: /");
}
?>