<?php
	
	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE)
	OR DIE("Error: Cannot connect to database".mysqli_error($db));
	
	$sql_companies = "SELECT DISTINCT companyName FROM `$market` ORDER BY companyName ASC";
	
	$result = mysqli_query($db, $sql_companies)
	OR DIE("Error: Cannot query database".mysqli_error($db));
	
	$list_companies = array();
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$list_companies[] = $row['companyName'];
	}
	
	echo json_encode($list_companies);
?>