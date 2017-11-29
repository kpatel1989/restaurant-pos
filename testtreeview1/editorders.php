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
if (isset($_REQUEST['id']))
{
	$orderid = $_REQUEST['id'];
	
	$qry= "Select * from `order` where orderid=".$orderid;
	$result = mysqli_query($conn, $qry);		
	$row = mysqli_fetch_assoc($result);
	
}
?>
<html>
<head>
<script>

function update(obj)
{
	alert(obj.id);
}
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
					
					table.rows[idindex].cells[3].innerHTML= parseInt(table.rows[idindex].cells[3].innerHTML) + parseInt(document.getElementById("qty").value);
					table.rows[idindex].cells[4].innerHTML=parseInt(table.rows[idindex].cells[3].innerHTML) * parseInt(table.rows[idindex].cells[2].innerHTML);
					
				}else
				{
					
		 
					var rowCount = table.rows.length;
					var row = table.insertRow(rowCount);
					//row.style.backgroundColor="#d9eaed";
					var cell1 = row.insertCell(0);
					cell1.innerHTML = rowCount ;
					cell1.style.display = "none";
					
					
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
</script>
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
	document.title = "Edit Order";
	document.getElementById("addorder").style.color="";
	document.getElementById("search").style.color="";
	document.getElementById("livescreen").style.color="";
</script>
<form action="updateorder.php" method="post">
<input type="hidden" id="orderid" name="orderid" value="<?php echo $orderid ?>"/>
<input type="hidden" id="itemids" name="itemids" value=""/>
<input type="hidden" id="itemprice" name="itemprice" value=""/>
<input type="hidden" id="itemqty" name="itemqty" value=""/>
	<table> <tr><td style="width: 70;">Order Id:</td> <td> <?php echo $row['OrderId'] ?> </td> <td style="width: 100;"> Created Time </td> <td style="width: 150;"> <input type="text" name="createdtime" value="<?php echo $row['CreatedTime'] ?> " readonly/> </td> </tr> </table>
	<center>
	<table style="width:400" id="items" ><caption> <h3> Order Item Details </h3></caption>
	
	<?php
		$qry1= "Select o.*,i.* from orderitem o, items i where o.item_id = i.item_id and orderid=".$orderid;
		$result1 = mysqli_query($conn, $qry1);
		?> <tr><th style="display:none"></th><th> Item Name</th> <th> Price </th>  <th> Quantity </th> <th> Total Price </th> </tr> <?php
		while ($row1 = mysqli_fetch_assoc($result1))
		{?>
		<tr><td style="display:none"></td><td><?php echo $row1['item_name'] ?> </td><td><?php echo $row1['Price'] ?> </td><td><?php echo $row1['Quantity']?></td> <td> <?php echo intval($row1['Quantity'])*intval($row1['Price']); ?></td> </tr>
		<script>
			
			document.getElementById("itemids").value =document.getElementById("itemids").value + "<?php echo $row1['item_id'] ?>" + ",";
			document.getElementById("itemprice").value=document.getElementById("itemprice").value + "<?php echo $row1['Price'] ?>" + "," ;
			document.getElementById("itemqty").value=document.getElementById("itemqty").value + "<?php echo $row1['Quantity'] ?>" +",";
		
		</script>
		<?php }?>			
	</table>
	
		<br/><br/>
	<table style="border-style:none; border-color:white;"><caption> <h3> Add New Item </h3></caption>
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
	</center>
	
	</form>
	
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