<?php
	
	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE)
	OR DIE("Error: Cannot connect to database".mysqli_error($db));
	
	$sql_liters = "SELECT DISTINCT liters FROM `$market` ORDER BY liters ASC";
	
	$result = mysqli_query($db, $sql_liters)
	OR DIE("Error: Cannot query database".mysqli_error($db));
	
	$list_liters = array();
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$list_liters[] = $row['liters'];
	}
	
	echo json_encode($list_liters);
?>