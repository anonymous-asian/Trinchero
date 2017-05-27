<?php
   include('session.php');
?>
<!doctype html>
<html>
<head>
	<title>Compare Stores</title>
      <meta charset = "UTF-8">
      <link rel = "stylesheet"
         type = "text/css"
         href = "css/main.css"/>

	<!--Initialize menu bar plugin-->
	<script type="text/javascript">
		$("#cssmenu").menumaker();
	</script>
	
	<link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
	
	<!--Table CSS-->
	<script>
		$(document).ready(function() {
			$('table.display').DataTable();
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
		<?php $storeNum=0; foreach($store_list as $store){ ?>
			<tr class="header" bgcolor="#FFA07A">
				<td colspan="3"><b>Store: <?php echo $store; ?></b></td>
				<td colspan="4"><b>Total: <?php echo $totalPerStore[$storeNum]; ?></b></td>
				<td><b>Percent: <?php $rounded = rtrim(rtrim(number_format((($totalPerStore[$storeNum]/$totalCases)*100),2, ".", ""), '0'), '.'); echo $rounded; ?>%</b></td>
				
				<?php
					//Sum of total 750ml cases
					$query1 = mysql_query("SELECT SUM(cases) FROM `$store` WHERE liters = '750ml'");
					$total_750ml = mysql_result($query1, 0 ,0);
					
					//Sum of total 1.5L cases
					$query2 = mysql_query("SELECT SUM(cases) FROM `$store` WHERE liters = '1.5L'");
					$total_15L = mysql_result($query2, 0 ,0);
					
					//Sum of total TFE cases
					$query3 = mysql_query("SELECT SUM(cases) FROM `$store` WHERE liters = 'TFE'");
					$total_TFE = mysql_result($query3, 0 ,0);
					
					//Sum of total Comp Brand cases
					$query4 = mysql_query("SELECT SUM(cases) FROM `$store` WHERE liters = 'Comp Brands'");
					$total_cb = mysql_result($query4, 0 ,0);
					
					//Sum of total store cases
					$query = mysql_query("SELECT SUM(cases) FROM `$store`");
					$totalStoreCases = mysql_result($query, 0 ,0);
	
					$sql = "SELECT * FROM `$store`
							  ORDER BY CASE
								WHEN `liters` = '750ml' THEN 1
								WHEN `liters` = '1.5L' THEN 2
								WHEN `liters` = 'TFE' THEN 3
								WHEN `liters` = 'Comp Brand' THEN 4
								ELSE 5
								END,
								brandName ASC";
					$result = mysqli_query($db,$sql);	
					while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
				?>
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
			</tr>
		<?php $storeNum++; } ?>
	</tbody>
</table>

<script>
$('tr.header').click(function(){
    /* get all the subsequent 'tr' elements until the next 'tr.header',
       set the 'display' property to 'none' (if they're visible), to 'table-row'
       if they're not: */
    $(this).nextUntil('tr.header').css('display', function(i,v){
        return this.style.display === 'table-row' ? 'none' : 'table-row';
    });
});
</script>

</body>
</html>
