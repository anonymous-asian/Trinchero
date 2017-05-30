<?php
	define("DB_SERVER", "MYSQLCONNSTR_localdb");
	define("DB_USERNAME", "trinchero_user");
	define("DB_PASSWORD", "Trincher0");
	define("DB_DATABASE", "trincherodb");
	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
?>