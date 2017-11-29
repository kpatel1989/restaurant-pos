

<?php
echo "<Menu>";
	include "class._database.php";
	$db=new _database();
	$conn = $db->connect();
	if($result=mysqli_query($conn, "select * from categories where parent_id=0 order by name"))
	{
		while($row=mysqli_fetch_assoc($result))
		{
			$noofsubcat = mysqli_query($conn, "Select count(*) as total from categories where parent_id=".$row['id']);
			$noofrow = mysqli_fetch_assoc($noofsubcat);
			if ($noofrow['total']>0)
			{
				echo '<rootcat name="'.$row['name'].'" id="'.$row['id'].'">';
				if($result1=mysqli_query($conn, "select * from categories where parent_id=".$row['id']." order by name"))
				{
					while($row1=mysqli_fetch_assoc($result1))
					{
						$itemcountresult = mysqli_query($conn, "Select count(*) as total from items where Category_id=".$row1['id']);
						$itemrow = mysqli_fetch_assoc($itemcountresult);
						if ($itemrow['total']>0)
						{
				
							echo '<subcat name="'.$row1['name'].'" id="'.$row1['id'].'" parent_id="'.$row1['parent_id'].'">';
							echo '<description>'.$row1['title'].'</description>';
							echo '<images>'.$row1['image'].'</images>';
							if($result2=mysqli_query($conn, "select * from items where Category_id=".$row1['id']." and is_active=1 order by item_name"))
							{
								while($row2=mysqli_fetch_assoc($result2))
								{
									echo '<item name="'.$row2['item_name'].'" id="'.$row2['item_id'].'" categoryid="'.$row2['Category_id'].'">';
									echo '<description>'.$row2['description'].'</description>';
									echo '<ingredients>'.$row2['ingredients'].'</ingredients>';
									echo '<preperation>'.$row2['preperation'].'</preperation>';
									echo '<price>'.$row2['price'].'</price>';
									echo '<images>'.$row2['images'].'</images>';
									echo '<approx_duration>'.$row2['approx_duration'].'</approx_duration>';
									echo "</item>";
								}
							}
							echo "</subcat>";
						}
					}
				}
				
				echo "</rootcat>";
			}
		}
	}
        if($result1=mysqli_query($conn, "select * from restable"))
	{
		while($row=mysqli_fetch_assoc($result1))
		{
			echo '<table name="'.$row['TableName'].'" id="'.$row['TableId'].'"></table>';
                }
        }
	echo "</Menu>";
?>
