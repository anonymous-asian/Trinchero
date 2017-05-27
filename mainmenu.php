<?php
   include('session.php');
?>

<html>
<head>
	<title>Home</title>
	<meta charset = "UTF-8">
   	<link rel = "stylesheet"
         type = "text/css"
         href = "softwareCSS.css" />
		 
	<style type="text/css">
		.background-image
		{
			background-image:url('<?php echo $images[1] ?>');
			background-size: cover;
			display: block;
			filter: blur(5px);
			-webkit-filter: blur(5px);
			height: 100%;
			left: 0;
			position: fixed;
			right: 0;
			z-index: -1;
		}
		
		.border
		{
			position:absolute;
			left:0; right:0;
			top:0; bottom:0;
			margin:auto;
			
			max-width:75%;
			max-height:75%;
			border:2px solid #FFF;
		}
		
		.border p
		{
			margin: 0;
			position: absolute;
			top: 50%;
			left: 50%;
			margin-right: -50%;
			transform: translate(-50%, -50%)
		}
		
		.text
		{
			color: white;
			font-family: Courier New;
			font-size: 4vw;
			word-spacing: -10px;
		}
		
		.text2
		{
			color: white;
			font-family: monospace;
			font-size: 48px;
		}
	</style>
	
	<!--Initialize menu bar plugin-->
	<script type="text/javascript">
		$("#cssmenu").menumaker();
	</script>

</head>
<body>
	<div class="background-image"></div>
	<div class="border">
		<p class="text">Every harvest. Every bottle.<br>Family is at the heart of what we do.</p>
	</div>
</body>
</html>
