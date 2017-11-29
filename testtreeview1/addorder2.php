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
REQUIRE "class._database.php";
$tmp = implode('',$_POST);
$addorders = md5($tmp);
if(isset($_SESSION["addorders"]) && $_SESSION["addorders"] == $addorders)
{
	
}
else
{
		$_SESSION["addorders"] = $addorders;

	if(isset($_POST['save']))
	{

		$ids=$_POST["itemids"];
		$prices=$_POST["itemprice"];
		$qtys=$_POST["itemqty"];
		
		$db=new _database();
		$conn = $db->connect();
		$result2=mysqli_query($conn, "select max(OrderId) from `order`");
		
		if($result2)
		{
			$row = mysqli_fetch_assoc($result2);
			$oid=$row["max(OrderId)"];
			$oid+=1;
			$today = date("Y-m-d H:i:s");
			while(true)
			{
				if ($result=mysqli_query($conn, "insert into `order`(OrderId,CreatedTime,AcceptedTime,AdminUserDetailId) values(".$oid.",'".$today."','".$today."',".$_SESSION['admin'].")"))
				if($result){
					while ($ids != "") {
						$tmpid=substr($ids,0,strpos($ids,","));
						$ids=substr($ids,strpos($ids,",")+1);
						
						$tmpprice=substr($prices,0,strpos($prices,","));
						$prices=substr($prices,strpos($prices,",")+1);
						
						$tmpqty=substr($qtys,0,strpos($qtys,","));
						$qtys=substr($qtys,strpos($qtys,",")+1);
						
						$result3=mysqli_query($conn, "select max(OrderItemId) from orderitem");
						$row3 = mysqli_fetch_assoc($result3);
						$oiid=$row3["max(OrderItemId)"];
						$oiid+=1;
						if($result1=mysqli_query($conn, "insert into orderitem (OrderItemId,OrderId,Item_Id,Quantity,Price) values(" .$oiid.",".$oid.",".$tmpid.",".$tmpqty.",'".$tmpprice."')"))
						{}

						
						}
						break;
					}
			}
		}
		

		
	}
	}
{


?>

<html>
<head>
<script src="jquery.js">
	</script>
<SCRIPT language="javascript">

		var idindex="";
		
		function exist(id){
		
			var ids=document.getElementById("itemids").value;
			if(ids=="")
			{
				return false;
			}
			var n=ids.split(",");
			//alert(id+ " " + ids + " " +n.length);
			for(var i=0;i<n.length-1;i++)
			{
				if(n[i]==id)
				{
					idindex=i+1;
					return true;
				}
			}
			return false;
		}
		
        function addRow() {
			var msg="";
			var rx = new RegExp(/^\d+$/);
			
			//alert(rx.test(document.getElementById("qty").value));
			if(!rx.test(document.getElementById("qty").value))
			{
				msg+="Qty should be only number \n";
			}
			rx=new RegExp(/^-?\d*(\.\d+)?$/);
			if(!rx.test(document.getElementById("price").value))
			{
				msg+="Enter Proper Price\n";
			}
			var e=document.getElementById("item");
			if(e.options[e.selectedIndex].text=="Select")
			{
				msg+="Select the item.";
			}
			if(msg=="")
			{
				var table = document.getElementById("items");
				var e=document.getElementById("item");
				if(exist(e.options[e.selectedIndex].value))
				{
					//alert("hi");
					var price=document.getElementById("itemprice").value;
					var n=price.split(",");
					n[idindex-1]=document.getElementById("price").value;
					var tmp="";
					for(var i=0;i<n.length-1;i++)
					{
						tmp+=n[i]+",";
					}
					document.getElementById("itemprice").value=tmp;
					//alert(tmp);
					
					var price=document.getElementById("itemqty").value;
					var n=price.split(",");
					n[idindex-1]=parseInt(table.rows[idindex].cells[3].innerHTML) + parseInt(document.getElementById("qty").value);
					var tmp="";
					for(var i=0;i<n.length-1;i++)
					{
						tmp+=n[i]+",";
					}
					document.getElementById("itemqty").value=tmp;
					//alert(tmp);
					
					//alert(table.rows[idindex].cells[3].innerHTML);
					table.rows[idindex].cells[3].innerHTML= parseInt(table.rows[idindex].cells[3].innerHTML) + parseInt(document.getElementById("qty").value);
					table.rows[idindex].cells[4].innerHTML=parseInt(table.rows[idindex].cells[3].innerHTML) * parseInt(table.rows[idindex].cells[2].innerHTML);
					//alert("index:"+idindex);
				}else
				{
					
		 
					var rowCount = table.rows.length;
					var row = table.insertRow(rowCount);
					row.style.backgroundColor="#d9eaed";
					var cell1 = row.insertCell(0);
					cell1.innerHTML = rowCount ;
					/*var element1 = document.createElement("input");
					element1.type = "checkbox";
					element1.name="chkbox[]";
					cell1.appendChild(element1);*/
					
					
					var cell2 = row.insertCell(1);
					cell2.innerHTML = e.options[e.selectedIndex].text;
					document.getElementById("itemids").value=document.getElementById("itemids").value + e.options[e.selectedIndex].value + ",";
					
					var cell3 = row.insertCell(2);
					cell3.innerHTML = document.getElementById("price").value;
					document.getElementById("itemprice").value=document.getElementById("itemprice").value+document.getElementById("price").value+"," ;
					
					var cell4 = row.insertCell(3);
					cell4.innerHTML = document.getElementById("qty").value;
					document.getElementById("itemqty").value=document.getElementById("itemqty").value +document.getElementById("qty").value +",";
					
					var cell4 = row.insertCell(4);
					cell4.innerHTML = parseInt(document.getElementById("qty").value) * parseInt(document.getElementById("price").value);
					
					row.style.color="black";
				}
				/*var element2 = document.createElement("input");
				element2.type = "text";
				element2.name = "txtbox[]";
				cell3.appendChild(element2);*/
			}else
			{
				alert(msg);
			}
 
        }
 
        function deleteRow(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
 
            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
 
 
            }
            }catch(e) {
                alert(e);
            }
        }
 
	function getPrice()
	{
		var e=document.getElementById("item");
		var temp=e.options[e.selectedIndex].id;
		document.getElementById("price").value=temp.substring(0,temp.indexOf("+"));
	}
	
	function getSubcat()
	{	
		var e=document.getElementById("subcat");
		var eroot=document.getElementById("rootcat");
		var cnt=1;	
	
		for(;cnt<e.length;cnt++)
		{
			if(e.options[cnt].id==eroot.options[eroot.selectedIndex].value)
			{
				e.options[cnt].style.display = 'block';
			}else
			{
				e.options[cnt].style.display = 'none';
			}			
		}
		e.selectedIndex=0;		
	}
	
	function getitems()
	{
		var e=document.getElementById("item");
		var eroot=document.getElementById("subcat");
		var cnt=1;	
	
		for(;cnt<e.length;cnt++)
		{
			var temp=e.options[cnt].id;
			temp=temp.substring(temp.indexOf("+")+1);
			if(temp==eroot.options[eroot.selectedIndex].value)
			{
				e.options[cnt].style.display = 'block';
			}else
			{
				e.options[cnt].style.display = 'none';
			}			
		}
		e.selectedIndex=0;
	}
    </SCRIPT>
</head>
<body>
<script>
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("reports").className="";
	document.title = "Add order";
	document.getElementById("addorder").style.color="#5D99A3";
	document.getElementById("search").style.color="";
	document.getElementById("livescreen").style.color="";
</script>
<center>
<form action="addorder.php" method="post">
<input type="hidden" id="itemids" name="itemids" value=""/>
<input type="hidden" id="itemprice" name="itemprice" value=""/>
<input type="hidden" id="itemqty" name="itemqty" value=""/>
<table id="items" style="border:solid; width:600px;">
<tr style="background:#294145; color:#ffffff;">
	<th>
		No.
	</th>
 <th>
	Item Name
 </th>
 <th>
	Price
 </th>
 <th>
	Quantity
 </th>
 <th>
	Total
 </th>
</tr>
</table>
<br/><br/>

<table style="border-style:none; border-color:white;">
<tr>
	<th>
		RootCategory
	</th>
	<th>
		SubCategory
	</th>
	<th>
		Item
	</th>
	<th>
		Price
	</th>
	<th>
		Qty
	</th>
	<th>
	</th>
</tr>
<tr>
<td>
<select id="rootcat" name="rootcat" onChange="getSubcat()" Style="width:150px;">
		<option  value="all">Select</option>
		<?php
		$db=new _database();
		$conn = $db->connect();
		$result2=mysqli_query($conn, "select id,name from categories where parent_id='0' order by name");
		while($row = mysqli_fetch_assoc($result2))
		{
		?>
		<option value="<?php echo $row['id']; ?>">
		<?php echo $row['name']; ?>
		</option>
		<?php } ?>
</select>
</td><td>
<select id="subcat" name="subcat" onchange="getitems()" Style="width:150px;">
		<option value="all">Select</option>
		<?php
		$db=new _database();
		$conn = $db->connect();
		$result2=mysqli_query($conn, "select id,name,parent_id from categories where parent_id<>'0' order by name");
		while($row = mysqli_fetch_assoc($result2))
		{
		?>
		<option value="<?php echo $row['id']; ?>" id="<?php echo $row['parent_id']; ?>">
		<?php echo $row['name']; ?>
		</option>
		<?php } ?>
</select>
</td>
<td>
<select id="item" name="item" onchange="getPrice()" Style="width:150px;">
		<option value="all">Select</option>
		<?php
		$db=new _database();
		$conn = $db->connect();
		$result2=mysqli_query($conn, "select item_id,item_name,Category_id,price from items order by item_name");
		while($row = mysqli_fetch_assoc($result2))
		{
		?>
		<option value="<?php echo $row['item_id']; ?>" id="<?php echo $row['price']."+".$row['Category_id']; ?>">
		<?php echo $row['item_name']; ?>
		</option>
		<?php } ?>
</select>
</td>
<td>
<input type="text" name="price" id="price" Style="width:50px;"/>
</td>
<td>
<input type="text" name="qty" id="qty" Style="width:50px;"/>
</td>
<td>
<input type="button" onClick="addRow()" value="Add to order" style="width: 100;"/>
</td>
</tr>
<tr>
	<td>
	</td>
</tr>
<tr>
	<td colspan="2">
	
	</td>
	<td>
		<center>
		<input type="submit" value="Save" name="save" id="save"/>
	</center>
	</td>
</tr>
</table>

</form>
</center>
</body>
</html>
<?php } ?>
  
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