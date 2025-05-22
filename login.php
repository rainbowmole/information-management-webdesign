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
					if(!empty($_POST['psword'])){
						$p = hash('sha256' , mysqli_real_escape_string($dbcon, $_POST['psword']));
					}else{
						$p = null;
						echo '<p class="error">Password Required</p>';
					}

					if ($e && $p) {
						// get user info from db; password hashing require
						$q = "SELECT user_id, fname, user_level from users where (email = '$e' AND psword = '$p')";
						$result = mysqli_query($dbcon, $q);
						if(@mysqli_num_rows($result) == 1){ //kung isa lang na get
							//start session
							session_start();
							$_SESSION = mysqli_fetch_array($result, MYSQLI_ASSOC);
							//sanity check
							$_SESSION['user_level'] = (int) $_SESSION['user_level'];
							//ternary operation
							$url = ($_SESSION['user_level'] === 1) ? 'admin_page.php' : 'members_page.php';
							header('Location: '.$url);
							exit();
							mysqli_free_result($result);
							mysqli_close($dbcon);
						}else{
							//No result form db.
							echo '<p class="error">User does not exist <br> register here </p>';
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
				<form action="login_page.php" method="post">
					<p id="log_email"><label class="label" for="email">Email Address: </label>
					<input type="text" id="pop_email" name="email" size="30" maxlength="50"
					value="<?php  if(isset($_POST['email'])) echo $_POST['email'];?>">
					</p>

					<p id="log_psword"><label class="label" for="psword">Password: </label>
					<input type="password" id="pop_psword" name="psword" size="20" maxlength="40"
					value="<?php  if(isset($_POST['psword'])) echo $_POST['psword'];?>">
					</p>

					<p><input type="submit" id="submit_login" name="login" value="login"></p>
				</div>
		</div>
	</div>
	<?php include('footer.php'); ?>
</body>
