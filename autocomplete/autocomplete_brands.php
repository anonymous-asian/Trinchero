<?php
	
	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE)
	OR DIE("Error: Cannot connect to database".mysqli_error($db));
	
	$sql_brands = "SELECT DISTINCT brandName FROM `$market` ORDER BY brandName ASC";
	
	$result = mysqli_query($db, $sql_brands)
	OR DIE("Error: Cannot query database".mysqli_error($db));
	
	$list_brands = array();
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$list_brands[] = $row['brandName'];
	}
	
	echo json_encode($list_brands);
?>