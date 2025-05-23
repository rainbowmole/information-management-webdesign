<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kalye Co. - Register</title>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/register.css">


</head>
<body>
    <?php include 'header.php'; ?>

    <?php
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//error array to store all errors
			$errors = array();
			//naglagay ba ng fname?
			if(empty($_POST['firstname'])) {
				$errors[] = 'Please input your first name.';
			}else{
				$fn = trim($_POST['firstname']);
			}
			//naglagay nadin ba ng lname?
			if(empty($_POST['lastname'])) {
				$errors[] = 'Please input your last name.';
			}else{
				$ln = trim($_POST['lastname']);
			}
			//naglagay ba ng email?
			if(empty($_POST['email'])) {
				$errors[] = 'Please input your email.';
			}else{
				$e = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

				if(!filter_var($e, FILTER_VALIDATE_EMAIL)) {
					$errors[] = 'Invalid Email Format';
				}
			}
			//phone number
			if(empty($_POST['phone'])) {
				$errors[] = 'Please input your contact number.';
			}else{
				$phoneNum = trim($_POST['phone']);
			}
			//for address
			if(empty($_POST['address'])) {
				$errors[] = 'Please input your address';
			}else{
				$adress = trim($_POST['address']);
			}
			//nagmatch ba password niya?
			if(!empty($_POST['password1'])){
				if($_POST['password1'] != $_POST['password2']){
					$errors[] = 'Your password do not match.';
				}else{
					$p = trim($_POST['password1']);
				}
			}else{
				$errors[] = 'Please input your password.';
			}
			//okay na lahat ng inputs
			if(empty($errors)){
				require('mysqli_connect.php');
				//query to register data
				$hashp = hash('sha256', $p);
				$q = "insert into users(firstname, lastname, email, password, phone, address, created_at, updated_at) values ('$fn', '$ln', '$e', '$hashp', '$phoneNum', '$adress', NOW(), NOW());";
				$result = @mysqli_query($dbcon, $q);

				if($result){ //ok result
					header("location: menu.php");
					exit();
				}else{  //not ok result
					//display error
					echo'<h2>System Error</h2>
					<p class="error">Your registration failed due to an unexpected error. Sorry for the inconvenience.</p>';
					//for debugging
					echo '<p>'.mysqli_error($dbcon).'</p>';
				}
				//close the connection 
				mysqli_close(($dbcon));
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
    <main>
        <div class="content" id="register-page">
            <div class="title">
                <h2> Your Bowl,<br>Your <span style="color: #fc921b">Rules</span>.</h2>
                <p class="description">Make it spicy, extra eggy, or all the toppings! Register now and customize every bowl to match your cravings.</p>
                    
                <p class="description">Already have an account? Login <a href="login.php">Here</a>.</p>
            </div>
            
            
            <div id="reg-form-div">
                <form autocomplete="off" action="Register.php" method="post">
                    <h2>Register</h2>
                    <div class="register-inputs">
                        <p><label class="label" for="firstname">First Name: </label>
                        <input type="text" id="firstname" name="firstname" size="30" maxlength="40"
                        value="<?php  if(isset($_POST['firstname'])) echo $_POST['firstname'];?>">
                        </p>

                        <p><label class="label" for="lastname">Last Name: </label>
                        <input type="text" id="lastname" name="lastname" size="30" maxlength="40"
                        value="<?php  if(isset($_POST['lastname'])) echo $_POST['lastname'];?>">
                        </p>

                        <p><label class="label" for="email">Email Address: </label>
                        <input type="text" id="email" name="email" size="30" maxlength="50"
                        value="<?php  if(isset($_POST['email'])) echo $_POST['email'];?>">
                        </p>

                        <p><label class="label" for="phone">Contact Number: </label>
                        <input type="text" id="phone" name="phone" size="30" maxlength="50"
                        value="<?php  if(isset($_POST['phone'])) echo $_POST['phone'];?>">
                        </p>

                        <p><label class="label" for="address">Address: </label>
                        <input type="text" id="address" name="address" size="30" maxlength="50"
                        value="<?php  if(isset($_POST['address'])) echo $_POST['address'];?>">
                        </p>

                        <p><label class="label" for="password1">Password: </label>
                        <input type="password" id="password1" name="password1" size="20" maxlength="40"
                        value="<?php  if(isset($_POST['password1'])) echo $_POST['password1'];?>">
                        </p>

                        <p><label class="label" for="password2">Repeat Password: </label>
                        <input type="password" id="password2" name="password2" size="20" maxlength="40"
                        value="<?php  if(isset($_POST['password2'])) echo $_POST['password2'];?>">
                        </p>
                    </div>
                    
                    <div class="submit-button">
                        <input type="submit" id="submit" name="submit" value="Register">
                    </div>
                    
                </form>
                <div id="toastBox"></div>
            </div>

        </div>
	    <?php include("footer.php"); ?>
    </main>
</body>
</html>