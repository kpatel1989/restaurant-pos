<html>
<head>
<link rel="stylesheet" type="text/css" href="css/theme.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script src="jquery.min.js">
</script>
<script>
	
	function test(){
		
		var request = $.ajax({
			url: "updatesidebar.php",
			type: "post",
			data: "getorders=1",
			success: function(data) {
				document.getElementById("sidebar").innerHTML=data;
			}
		});
	}
	function loadsidebar()
	{
		setInterval(function(){test()},1000);
	}
</script>
</head>
<body onload="test()">
<div id="sidebar" >
	
	<table>
		<caption> Table Orders </caption>
		<tr><th> Table Name </th>
			<th> Quantity </th>
		</tr>
	</table>
		
</div>

</body>
</html>