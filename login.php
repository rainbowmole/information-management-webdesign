<body>
	<div id="container">
		
		<div id='login'>
			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST'){
					require('mysqli_connect.php');
					//validate email
					if(!empty($_POST['email'])){
						$e = mysqli_real_escape_string($dbcon, $_POST['email']);
					}else{
						$e = null;
						echo '<p class="error">Email Required</p>';
					}

					//validate password
					if(!empty($_POST['password'])){
						$p = hash('sha256' , mysqli_real_escape_string($dbcon, $_POST['password']));
					}else{
						$p = null;
						echo '<p class="error">Password Required</p>';
					}

					if ($e && $p) {
						// get user info from db; password hashing require
						$q = "SELECT user_id, firstname, 'user' AS role from users where email = '$e' AND password = '$p'";
						$result = mysqli_query($dbcon, $q);

						if($result && mysqli_num_rows($result) != 1){ //check lang if true
							//checkng for the admins
							$q_admin = "SELECT admin_id, username, 'admin' from admin where email = '$e' and password = '$p'";
							$result_admin = mysqli_query($dbcon, $q_admin);

							if ($result_admin && mysqli_num_rows($result_admin) == 1){
								//start admin session pagnahanap
								$admin_data = mysqli_fetch_array($result_admin, MYSQLI_ASSOC);
								session_start();
								$_SESSION['admin_id'] = $admin_data['admin_id'];
								$_SESSION['username'] = $admin_data['username'];
								$_SESSION['role'] = 'admin';
								mysqli_free_result($result_admin);
								mysqli_close($dbcon);
								header('Location: ap/admin.php');
								exit();
							}else{
								echo'<p class="error">Incorrect email or password.</p>';
							}
						}else{
							//user login na
							$user_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
							session_start();
							$_SESSION['user_id'] = $user_data['user_id'];
							$_SESSION['firstname'] = $user_data['firstname'];
							$_SESSION['role'] = 'user';
							mysqli_free_result($result);
							mysqli_close($dbcon);
							header('Location: index.php');
							exit();
						}
					}else{
						// problem 
						echo '<p class="error">Please try again </p>';
					}
					mysqli_close($dbcon);
					}
			?>
			<div id="pop_login">
				<h2>Login</h2>
				<form action="" method="post">
					<p id="log_email"><label class="label" for="email">Email Address: </label>
					<input type="text" id="pop_email" name="email" size="30" maxlength="50"
					value="<?php  if(isset($_POST['email'])) echo $_POST['email'];?>">
					</p>

					<p id="log_password"><label class="label" for="password">Password: </label>
					<input type="password" id="pop_password" name="password" size="20" maxlength="40"
					value="<?php  if(isset($_POST['password'])) echo $_POST['password'];?>">
					</p>

					<p><input type="submit" id="submit_login" name="login" value="login"></p>
				</div>
		</div>
	</div>
	<?php include('footer.php'); ?>
</body>
