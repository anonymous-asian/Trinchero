<?php
   include('dbconnection.php');
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   //Get user email
   $ses_sql = mysqli_query($db,"select email from users where email = '$user_check' ");  
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);  
   $login_session = $row['email'];
   
   //Get user type
   $ses_sql = mysqli_query($db,"select membershipID from users where email = '$user_check' ");  
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   $user_type = $row['membershipID'];
   
   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
   }
   
   //Query database for market names
   	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	
	//This connect is for the sum query (two diff types used -> mysql and mysqli!)
	mysql_select_db("trincherodb") OR DIE("Error: Cannot connect to database");
	
	//Total for all stores
	$totalCases = 0;
	
	//Total for each store
	$totalPerStore = array();
	
	//Query an array to store names
	//Old query -> SHOW TABLES FROM trincherodb
	$queryStore = mysql_query("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema='trincherodb' ORDER BY table_name ASC");
	$store_list = array();
	while($array = mysql_fetch_array($queryStore)){
		if($array[0]!="brands" && $array[0]!="users"){
			$store_list[] = $array[0];
			
			//Query and add store total to over-all total
			$query = mysql_query("SELECT SUM(cases) FROM `$array[0]`");
			$totalStoreCases = 0;
			$totalStoreCases = mysql_result($query, 0 ,0);
			$totalCases += $totalStoreCases;
			$totalPerStore[] = $totalStoreCases;
		}
	}
	
   //Get reference to images in "images" folder
   $dir = "images";
   $images = glob($dir ."/*.jpg");
   //$images = scandir("images", 1);
   //print_r($images);
?>

<!doctype html>
<html>
<head>	
	<!-- load jquery ui css-->
	<link href="jquery/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<!-- load jquery library -->
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<!-- load jquery ui js file -->
	<script src="jquery/jquery-ui.min.js"></script>
	
	<!--Menu bar css-->
	<link rel="stylesheet" type="text/css" href="css/menumaker.css">
	<script type="text/javascript" src="js/menumaker.js"></script>

	<!--Navigation menu style-->
	<style>
		#cssmenu {
			position: relative;
			z-index: 1;
		}
	</style>
	
	<script>
		function marketButton(market){
			window.location.href = "marketPage.php?market="+market+"&type=n&input=n";
		}
	</script>
</head>
<body>
	<!--Navigation bar-->
	<div id="cssmenu">
	  <ul>
		<li><a href="mainmenu.php">Home</a></li>
		<li><a href="comparePage.php">Total Market</a></li>
		<li>
			<a href="#">Markets</a>
			<ul>
				<?php $storeNum=0; foreach($store_list as $store){ ?>
					<li onclick="marketButton('<?php echo $store_list[$storeNum]; ?>')"><a href="#"><?php echo $store_list[$storeNum]; ?></a></li>
				<?php $storeNum++;} ?>
			</ul>
		</li>
		<li><a href="http://www.nielsen.com/us/en/top10s.html">Neilsen Data</a></li>
		<li><a href="">Contact</a></li>
		<li style='float:right; right: 115px; top: 14px; color:#FFA07A;'><span class="clickable"><?php echo $login_session; ?></span></li>
		<li style='float:right; right: -125px;'><a href="login.php">Log Out</a></li>
	  </ul>
	</div>
	
	<script>
		$('.clickable').on('click',function(){
		var user = <?php echo $user_type; ?>;
			
		//Admin user
		if(user==1){
			alert("Welcome admin!");
			window.location.href="#";
		}
		
		//Regular user
		else {
			alert("Welcome user!");
			window.location.href="#";
		}
		});
	</script>
</body>
</html>
