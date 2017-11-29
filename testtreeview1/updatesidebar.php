<?php
	session_start();
	require "class._database.php";
	$db=new _database();
	$conn = $db->connect();
?>
<table>
		<caption> Table Orders </caption>

		<tr><th> Table Name </th>
			<th> Quantity </th>
		</tr>
		
		<?php
			
			
			$qry = "Select orderid,tableid,status from `order` where status!=5  and tableid is not NULL ";
			$result=mysqli_query($conn, $qry);
			while($row = mysqli_fetch_assoc($result)){
				
				?><tr> <td><a href="orderitemdetails.php?tableid=<?php echo $row['tableid']?>" target="_blank"> <?php 
							$result1=mysqli_query($conn, "Select tablename from restable where tableId=".$row['tableid']);
							$row1 = mysqli_fetch_assoc($result1);
							echo $row1['tablename'];
						?> 
					</a></td>
				<td> <?php
						$result3=mysqli_query($conn, "Select sum(quantity) as served from orderitem where orderid=".$row['orderid']." and isserved=1");
						$row3 = mysqli_fetch_assoc($result3);
						$served=$row3['served'];
						echo $row3['served'] ."/";
						$result2=mysqli_query($conn, "Select sum(quantity) as qty from orderitem where orderid=".$row['orderid']);
						$row2 = mysqli_fetch_assoc($result2);
						$total = $row2['qty'];
						echo $row2['qty'];
						
						if ($served == $total && $row['status']<4)
						{
							$qry = "update `order` set status=3 where orderid=" . $row['orderid'];
							if (mysqli_query($conn, $qry))
							{
							}
							else 
								echo mysqli_error($conn);
						}
					?>
				</td>
				</tr>
			<?php
			}
			
			
		?>
		
	</table>
	
	
		
	
	
	