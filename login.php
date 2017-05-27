<?php
   include("dbconnection.php");
   session_start();
   $error = " ";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // email and password sent from form 
      
      $myemail = mysqli_real_escape_string($db,$_POST['email']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT userID FROM users WHERE email = '$myemail' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myemail and $mypassword, table row must be 1 row
		
      if($count == 1) {
         
         $_SESSION['login_user'] = $myemail;
         
         header("location: mainmenu.php");
      }else {
         $error = "Your Email or Password is invalid";
      }
   }
?>
<html>
   
   <head>
      <title>Sign-In</title>
      
      <meta charset = "UTF-8">
      <link rel = "stylesheet"
         type = "text/css"
         href = "softwareCSS.css" />
   </head>
   
   <body>
   
      <h1>Trinchero</h1>
      <h2>Market Suvey Tool</h2>

      <div align = "center">
         <div style = "width:400px; border: solid 1px #333333; " align = "center">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
            
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <input type = "text" name = "email" placeholder="Email" class = "box"/><br /><br />
                  <input type = "password" name = "password" placeholder="Password" class = "box" /><br/><br />
                  <p>
                  <input type = "submit" value = " Log In " id="button"/>
                  <input type = "button" value = " Sign Up " onclick="location.href = 'registerPage.php';" id="button"/><br />
                  </p>
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
               
            </div>
            
         </div>
         
      </div>

   </body>
</html>