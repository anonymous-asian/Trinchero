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
					//Declare variables
					$total_750ml=$total_15L=$total_TFE=$total_cb=$totalStoreCases=0;
					
					//Sum of total 750ml cases
					$query1 = mysqli_query($db,"SELECT SUM(cases) AS total FROM $store WHERE liters = '750ml'");
					if($query1){
						while($row = mysqli_fetch_array($query1)){
							$total_750ml+=$row['total'];
						}
					}
					
					//Sum of total 1.5L cases
					$query2 = mysqli_query($db,"SELECT SUM(cases) AS total FROM $store WHERE liters = '1.5L'");
					if($query2){
						while($row = mysqli_fetch_array($query2)){
							$total_15L+=$row['total'];
						}
					}
					
					//Sum of total TFE cases
					$query3 = mysqli_query($db,"SELECT SUM(cases) AS total FROM $store WHERE liters = 'TFE'");
					if($query3){					
						while($row = mysqli_fetch_array($query3)){
							$total_TFE+=$row['total'];
						}
					}
					
					//Sum of total Comp Brand cases
					$query4 = mysqli_query($db,"SELECT SUM(cases) AS total FROM $store WHERE liters = 'Comp Brands'");
					if($query4){
						while($row = mysqli_fetch_array($query4)){
							$total_cb+=$row['total'];
						}
					}
					
					//Sum of total store cases
					$query5 = mysqli_query($db,"SELECT SUM(cases) AS total FROM $store");
					if($query5){					
						while($row = mysqli_fetch_array($query5)){
							$totalStoreCases+=$row['total'];
						}
					}
	
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
						$rounded = rtrim(rtrim(number_format((($row['cases']/$totalPerStore[$storeNum])*100),2, ".", ""), '0'), '.');
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
