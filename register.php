<!doctype html> 
<html lang="en">
<head>
	<title>JLougawan</title>
	<meta charset = "utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	
</head>
<body>
	<div id="container">
		<div id='regcontent'>
			<div id="regerrors">
			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST'){
					//error array to store all errors
					$errors = array();
					//naglagay ba ng fname?
					if(empty($_POST['fname'])) {
						$errors[] = 'Please input your first name.';
					}else{
						$fn = trim($_POST['fname']);
					}
					//naglagay nadin ba ng lname?
					if(empty($_POST['lname'])) {
						$errors[] = 'Please input your last name.';
					}else{
						$ln = trim($_POST['lname']);
					}
					//naglagay ba ng email?
					if(empty($_POST['email'])) {
						$errors[] = 'Please input your email.';
					}else{
						$e = trim($_POST['email']);
					}
					//nagmatch ba password niya?
					if(!empty($_POST['psword1'])){
						if($_POST['psword1'] != $_POST['psword2']){
							$errors[] = 'Your password do not match.';
						}else{
							$p = trim($_POST['psword1']);
						}
					}else{
						$errors[] = 'Please input your password.';
					}
					//okay na lahat ng inputs
					if(empty($errors)){
						require('mysqli_connect.php');
						//query to register data
						$hashp = hash('sha256', $p);
						$q = "insert into users(fname, lname, email, psword, registration_date, user_level) values ('$fn', '$ln', '$e', '$hashp', NOW(), 0);";
						$result = @mysqli_query($dbcon, $q);

						if($result){ //ok result
							header("location: register-success.php");
							exit();
						}else{  //not ok result
							//display error
							echo'<h2>System Error</h2>
							<p class="error">Your registration failed due to an unexpected error. Sorry for the inconvenience.</p>';
							//for debugging
							echo '<p>'.mysqli_error($dbcon).'</p>';
						}
						//close the connection 
						mysqli_close(($dbon));
						include('footer.php');
						exit();

					}else{ //nagka-error
						echo'<h2>Error!</h2><p class="error">The following 
						error(s) occured:<br/>';
						foreach($errors as $msg){
							echo " - $msg<br/>\n";
						}
						echo '</p><h3>Please try again.</h3><br/><br/>';
					}

				}

			?>
			</div>
			<div id="regpage">
				<h2>Registration Page</h2>
				<form action="register.php" method="post">
					<p><label class="label" for="fname">First Name: </label>
					<input type="text" id="fname" name="fname" size="30" maxlength="40"
					value="<?php  if(isset($_POST['fname'])) echo $_POST['fname'];?>">
					</p>

					<p><label class="label" for="lname">Last Name: </label>
					<input type="text" id="lname" name="lname" size="30" maxlength="40"
					value="<?php  if(isset($_POST['lname'])) echo $_POST['lname'];?>">
					</p>

					<p><label class="label" for="email">Email Address: </label>
					<input type="text" id="email" name="email" size="30" maxlength="50"
					value="<?php  if(isset($_POST['email'])) echo $_POST['email'];?>">
					</p>

					<p><label class="label" for="psword1">Password: </label>
					<input type="password" id="psword1" name="psword1" size="20" maxlength="40"
					value="<?php  if(isset($_POST['psword1'])) echo $_POST['psword1'];?>">
					</p>

					<p><label class="label" for="psword2">Repeat Password: </label>
					<input type="password" id="psword2" name="psword2" size="20" maxlength="40"
					value="<?php  if(isset($_POST['psword2'])) echo $_POST['psword2'];?>">
					</p>

					<p><input type="submit" id="submit" name="submit" value="Register"></p>

				</form>
			</div>
		</div>
	</div>
	<?php include('footer.php'); ?>
</body>

</html>