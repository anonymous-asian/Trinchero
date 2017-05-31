<?php
   include('session.php');
   $market = $_GET['market'];
?>
<!doctype html>
<html>
<head>
	<title>Market Page</title>
	<meta charset = "UTF-8">
    <link rel = "stylesheet"
         type = "text/css"
         href = "css/main.css" />

	<script>
	var market;
	function setMarket(mrkt){
		market = mrkt;
	}
	</script>
	
	<!--Initialize menu bar plugin-->
	<script type="text/javascript">
		$("#cssmenu").menumaker();
	</script>

	<!--Autocomplete brands-->
	<script type="text/javascript">
	$(function() {
		var availableTags = <?php include("autocomplete/autocomplete_brands.php"); ?>;
		$("#brand_name").autocomplete({
			source: availableTags,
			autoFocus:true
		});
	});
	</script>
	
	<!--Autocomplete companies-->
	<script type="text/javascript">
	$(function() {
		var availableTags = <?php include('autocomplete/autocomplete_companies.php'); ?>;
		$("#company_name").autocomplete({
			source: availableTags,
			autoFocus:true
		});
	});
	</script>
	
	<!--Autocomplete liters-->
	<script type="text/javascript">
	$(function() {
		var availableTags = <?php include('autocomplete/autocomplete_liters.php'); ?>;
		$("#liters").autocomplete({
			source: availableTags,
			autoFocus:true
		});
	});
	</script>
	
	<link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
	
	<script>
		$(document).ready(function() {
			$('table.display').DataTable();
			
			document.getElementById('brand_name').value = "";
			document.getElementById('company_name').value = "";
			document.getElementById('liters').value = "";
			document.getElementById('cases').value = "";
			
			$("#success-alert").hide();
			$("#myWish").click(function showAlert() {
				$("#success-alert").alert();
				$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
					$("#success-alert").slideUp(500);
				});   
			});
		} );
	</script>

	<style>
		div.dataTables_wrapper {
			margin-bottom: 3em;
		}
	</style>
</head>

<body>

<h1>Trinchero</h1>
	<h2>Market Survey Tool</h2>
	<h2><?= $_GET['market']; ?></h2>

<?php
echo $market;
	echo "<script>setMarket('$market')</script>";
	if($market=='KROGER 355 HYDE PARK') {
		$market = '`krog 355 hyde park`';
	} else {
		$market = '`'.strtolower($market).'`';
	}
	echo $market;
	$type = $_GET['type'];
	$input = $_GET['input'];
	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	$error = " ";
	
	//Update script
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$mybrandname = mysqli_real_escape_string($db,$_POST['brandName']);
		$mycompanyname = mysqli_real_escape_string($db,$_POST['companyName']);
		$myliters = mysqli_real_escape_string($db,$_POST['liters']); 
		$mycases = mysqli_real_escape_string($db,$_POST['cases']);
		
		$check = mysqli_query($db,"SELECT * FROM $market WHERE brandName='".$mybrandname."'");
				  
		if(mysqli_num_rows($check) > 0){
			echo "Here's the update: ".$mybrandname."-".$mycompanyname."-".$myliters."-".$mycases."<br>";
			$mysql = "UPDATE $market
					  SET
					  brandName='$mybrandname',
					  companyName='$mycompanyname',
					  liters='$myliters',
					  cases='$mycases'
					  WHERE
					  brandName='$mybrandname' AND
					  companyName='$mycompanyname' AND
					  liters='$myliters'";
		}
		else{
			echo "Does not exist! Added: ".$mybrandname."-".$mycompanyname."-".$myliters."-".$mycases."<br>";
			$mysql = "INSERT INTO $market (brandName, companyName, liters, cases)
				  VALUES ('$mybrandname', '$mycompanyname', '$myliters', '$mycases')
				  ON DUPLICATE KEY UPDATE
				  brandName = VALUES(brandName),
				  companyName = VALUES(companyName),
				  liters = VALUES(liters),
				  cases = VALUES(cases)";
		}
		
		$result2 = mysqli_query($db,$mysql);
		if (!$result2)
		{
		   echo "<span text-align:'center' style='color:white;'>".'Failure retrieving data'.mysqli_error($db)."</span>";
		}
		else{
		   echo("<a href='marketpage.php'></a>");
		}
	}
	
	//SQL query for table
	if($type == "n") {$sql = "SELECT * FROM $market
							  ORDER BY CASE
								WHEN `liters` = '750ml' THEN 1
								WHEN `liters` = '1.5L' THEN 2
								WHEN `liters` = 'TFE' THEN 3
								WHEN `liters` = 'Comp Brand' THEN 4
								ELSE 5
								END,
								brandName ASC";}
	else if($type=="liters") {$sql = "SELECT * FROM $market WHERE liters = '$input' ORDER BY liters ASC";}
	else if($type=="companyName") {$sql = "SELECT * FROM $market WHERE companyName = '$input' ORDER BY brandName ASC";}
	else {$sql = "SELECT * FROM $market WHERE brandName = '$input' ORDER BY brandName ASC";}
	
	//This connect is for the sum query (two diff types used -> mysql and mysqli!)
	mysql_select_db("trincherodb") OR DIE("Error: Cannot connect to database");
	
	//Sum of total 750ml cases
	$query1 = mysql_query("SELECT SUM(cases) FROM $market WHERE liters = '750ml'");
	$total_750ml = mysql_result($query1, 0 ,0);
	
	//Sum of total 1.5L cases
	$query2 = mysql_query("SELECT SUM(cases) FROM $market WHERE liters = '1.5L'");
	$total_15L = mysql_result($query2, 0 ,0);
	
	//Sum of total TFE cases
	$query3 = mysql_query("SELECT SUM(cases) FROM $market WHERE liters = 'TFE'");
	$total_TFE = mysql_result($query3, 0 ,0);
	
	//Sum of total Comp Brand cases
	$query4 = mysql_query("SELECT SUM(cases) FROM $market WHERE liters = 'Comp Brands'");
	$total_cb = mysql_result($query4, 0 ,0);
	
	//Sum of total store cases
	$query = mysql_query("SELECT SUM(cases) FROM $market");
	$totalStoreCases = mysql_result($query, 0 ,0);
	
    $result = mysqli_query($db,$sql);
?>

<!--Table-->
<table id="" class="display" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Brand Name</th>
			<th>Company Name</th>
			<th>Liters</th>
			<th>Cases</th>
			<th>PercentCases</th>
			<th>PercentAllCases</th>
			<th>PercentDisp</th>
			<th>PercentAll</th>
		</tr>
	</thead>
	<tbody>
		<?php while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){ ?>
		<tr>
			<td><?php echo $row['brandName']; ?></td>
			<td><?php echo $row['companyName']; ?></td>
			<td><?php echo $row['liters']; ?></td>
			<td><?php echo $row['cases']; ?></td>
			<?php
				//Percent cases
				if($row['liters']=='750ml') {
					$rounded = rtrim(rtrim(number_format((($row['cases']/$total_750ml)*100),2, ".", ""), '0'), '.');
					echo	"<td>".$rounded."%</td>";
				}
				else if($row['liters']=='1.5L') {
					$rounded = rtrim(rtrim(number_format((($row['cases']/$total_15L)*100),2, ".", ""), '0'), '.');
					echo	"<td>".$rounded."%</td>";
				}
				else if($row['liters']=='TFE') {
					$rounded = rtrim(rtrim(number_format((($row['cases']/$total_TFE)*100),2, ".", ""), '0'), '.');
					echo	"<td>".$rounded."%</td>";
				}
				
				else {
					$rounded = rtrim(rtrim(number_format((($row['cases']/$total_cb)*100),2, ".", ""), '0'), '.');
					echo	"<td>".$rounded."%</td>";
				}
				
				//Percent all cases
				$rounded = rtrim(rtrim(number_format((($row['cases']/$totalStoreCases)*100),2, ".", ""), '0'), '.');
				echo	"<td>".$rounded."%</td>";
				
				//Percent display
				echo	"<td>".'0'."%</td>";
				
				//Percent all
				$rounded = rtrim(rtrim(number_format((($row['cases']/$totalCases)*100),2, ".", ""), '0'), '.');
				echo	"<td>".$rounded."%</td>";
				echo 	"</tr>";
			?>
		</tr>
		<?php }
			echo "<tr>";
					echo	"<td>z</td>";
					echo	"<td></td>";
					echo	"<td><strong>Total cases</strong></td>";
					echo	"<td>".$totalStoreCases."</td>";
					
					echo	"<td></td>";
					echo	"<td></td>";
					echo	"<td></td>";
					echo	"<td></td>";
			echo "</tr>";
		?>
	</tbody>
</table>

<form name="frmUpdate" method="post" action="">
	<table border="0" width="500" align="center" class="demo-table">
		<?php if(!empty($success_message)) { ?>	
		<div class="success-message"><?php if(isset($success_message)) echo "<span style='color:red;'>".$success_message."</span>"; ?></div>
		<?php } ?>
		<?php if(!empty($error_message)) { ?>	
		<div class="error-message"><?php if(isset($error_message)) echo "<span style='color:red;'>".$error_message."</span>"; ?></div>
		<?php } ?>
		
		<tr>

<td>Brand Name</td>
<td><input id="brand_name" type="text" class="demoInputBox" name="brandName" value="<?php if(isset($_POST['brandName'])) echo $_POST['brandName']; ?>"></td>
</tr>
<tr>
<td>Company Name</td>
<td><input id="company_name" type="text" class="demoInputBox" name="companyName" value="<?php if(isset($_POST['companyName'])) echo $_POST['$companyName']; ?>"></td>
</tr>
<tr>
<td>Liters</td>
<td><input id="liters" type="text" class="demoInputBox" name="liters" value="<?php if(isset($_POST['liters'])) echo $_POST['liters']; ?>"></td>
</tr>
<tr>
<td>Cases</td>
<td><input id="cases" type="text" class="demoInputBox" name="cases" value="<?php if(isset($_POST['cases'])) echo $_POST['cases']; ?>"></td>
</tr>
<tr>
<td colspan=2>
<input type="submit" name="update-record" value="update" class="btnUpdate" onclick="return updateAlert();"></td>
</tr>

<script>
function updateAlert(){
	var brand = document.getElementById('brand_name').value;
	var comp = document.getElementById('company_name').value;
	var liters = document.getElementById('liters').value;
	var cases = document.getElementById('cases').value;
	
	alert('Update successful!\n\nBrand Name: ' + brand + '\nCompany Name: ' + comp + '\nLiters: ' + liters + '\nCases: ' + cases);
}
</script>

<!--
<div class="product-options">
    <a  id="myWish" href="javascript:;"  class="btn btn-mini" >Add to Wishlist </a>
    <a  href="" class="btn btn-mini"> Purchase </a>
</div>
<div class="alert alert-success" id="success-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Success! </strong>
    Product have added to your wishlist.
</div>
-->

<p>&nbsp;</p>
</body>
</html>
