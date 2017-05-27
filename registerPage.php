<?php
   include('session.php');

if(!empty($_POST["register-user"])) {
	/* Form Required Field Validation */
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
		$error_message = "All Fields are required";
		break;
		}
	}
	/* Password Matching Validation */
	if($_POST['password'] != $_POST['confirm_password']){ 
	$error_message = 'Passwords should be same<br>'; 
	}

	/* Email Validation */
	if(!isset($error_message)) {
		if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$error_message = "Invalid Email Address";
		}
	}

}
   $error = " ";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // info from form 
      //$myuserid = mysqli_real_escape_string($db,$_POST['userID']);
      $myemail = mysqli_real_escape_string($db,$_POST['email']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
 	  $myfirstname = mysqli_real_escape_string($db,$_POST['firstName']); 
  	  $mylastname = mysqli_real_escape_string($db,$_POST['lastName']); 
      
      $sql = "INSERT INTO users (userID,firstName,lastName,password,email) VALUES('$myuserid','$myfirstname','$mylastname','$mypassword','$myemail')";
      $result = mysqli_query($db,$sql);
     if (!$result)
{
   echo "<span style='color:white;'>".'Failed'."</span>";
}
else{
	
	$_SESSION[ 'message' ] = "Registration succesful! Added $firstName to the database!";
    //redirect the user to welcome.php
    header( "location: mainmenu.php" );
   }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Trinchero Sign In</title>

	<meta charset = "UTF-8">
	<link rel = "stylesheet"
   		type = "text/css"
   		href = "softwareCSS.css" />
</head>
<body>

	<h1>Trinchero</h1>
	<h2>Market Suvey Tool</h2>
	<div1>
	<form name="frmRegistration" method="post" action="">

<div2>
	<input type="text" class="demoInputBox" name="firstName" placeholder="First Name" value="<?php if(isset($_POST['firstName'])) echo $_POST['firstName']; ?>"></br>
	<input type="text" class="demoInputBox" name="lastName" placeholder="Last Name" value="<?php if(isset($_POST['lastName'])) echo $_POST['lastName']; ?>"></br>
	<input type="password" class="demoInputBox" name="password" placeholder="Password" value=""></br>
	<input type="password" class="demoInputBox" name="confirm_password" placeholder="Re-enter Password" value=""></br>
	<input type="text" class="demoInputBox" name="email" placeholder="Email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"></br>
	<input type="submit" name="register-user" value="Register" class="btnRegister" id="button"></br>
</div2>

	<!--<table border="0" width="500" align="center" class="demo-table">-->
		<?php if(!empty($success_message)) { ?>	
		<div class="success-message"><?php if(isset($success_message)) echo $success_message; ?></div>
		<?php } ?>
		<?php if(!empty($error_message)) { ?>	
		<div class="error-message"><?php if(isset($error_message)) echo $error_message; ?></div>
		<?php } ?>
		<!--
		<tr>
<td>User ID</td>
<td><input type="text" class="demoInputBox" name="userID" value="<?php if(isset($_POST['userID'])) echo $_POST['userID']; ?>"></td>
</tr>
<tr>
<td>First Name</td>
<td><input type="text" class="demoInputBox" name="firstName" value="<?php if(isset($_POST['firstName'])) echo $_POST['firstName']; ?>"></td>
</tr>
<tr>
<td>Last Name</td>
<td><input type="text" class="demoInputBox" name="lastName" value="<?php if(isset($_POST['lastName'])) echo $_POST['lastName']; ?>"></td>
</tr>
<tr>
<td>Password</td>
<td><input type="password" class="demoInputBox" name="password" value=""></td>
</tr>
<tr>
<td>Confirm Password</td>
<td><input type="password" class="demoInputBox" name="confirm_password" value=""></td>
</tr>
<tr>
<td>Email</td>
<td><input type="text" class="demoInputBox" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"></td>
<tr>
<td colspan=2>
<input type="submit" name="register-user" value="Register" class="btnRegister"></td>
</tr>
</table>-->
</form>
</body>
</html>