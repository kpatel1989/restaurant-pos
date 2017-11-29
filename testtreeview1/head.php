
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dashboard - Admin Template</title>
<link rel="stylesheet" type="text/css" href="css/theme.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<!--<script>
   var StyleFile = "theme" + document.cookie.charAt(6) + ".css";
   document.writeln('<link rel="stylesheet" type="text/css" href="css/' + StyleFile + '">');
</script>
[if IE]>
<link rel="stylesheet" type="text/css" href="css/ie-sucks.css" />
<![endif]-->
</head>

<body>
	<div id="container">
    	<div id="header">
        	<div> 
				<div> <h2>Restaurant POS</h2> </div>
				<div> <a href="logout.php" style="color:#4AADB1"> Logout </a> </div>
			
			</div>
    <div id="topmenu" style="margin-top:18">
            	<ul>
              
                    <li id="menu" class="current"><a href="menuitems.php">Menu</a></li>
                	<li id="userdetail" ><a href="UserDetails.php">Users</a></li>
                    <li id="waiter" ><a href="waiter.php">Waiter</a></li>
                    <li id="device" ><a href="DeviceManagement.php">Device</a></li>
                    <li id="livescreenmgmt" ><a href="LiveScreenManagement.php">LiveScreen</a></li>
					<li id="themes" ><a href="Themes.php">Themes</a></li>
                    <li id="reports" ><a href="Reports.php">Reports</a></li>
					<li id="settings" ><a href="Settings.php">Settings</a></li>
              </ul>
          </div>
      </div>
        <div id="top-panel">
            <div id="panel">
                <ul>
                    <li><a href="addorder.php" class="report" id = "addorder"> Add Order</a></li>
                   
                    <li><a href="searchorder.php" class="search" id = "search">Search Order</a></li>
                    <li><a href="LiveScreen.php" class="feed" id = "livescreen">Live Screen</a></li>
                </ul>
            </div>
      </div>