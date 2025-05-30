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
    <link rel="stylesheet" href="css/login.css">


</head>
<body>
    <?php include 'header.php'; ?>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require('mysqli_connect.php');

            $errors = array(); // collect all errors here

            // Validate email
            if (!empty($_POST['email'])) {
                $e = mysqli_real_escape_string($dbcon, $_POST['email']);
            } else {
                $e = null;
                $errors[] = 'Email Required';
            }

            // Validate password
            if (!empty($_POST['password'])) {
                $p = hash('sha256', mysqli_real_escape_string($dbcon, $_POST['password']));
            } else {
                $p = null;
                $errors[] = 'Password Required';
            }

            if ($e && $p) {
                // Get user info from db
                $q = "SELECT user_id, firstname, 'user' AS role FROM users WHERE email = '$e' AND password = '$p'";
                $result = mysqli_query($dbcon, $q);

                if ($result && mysqli_num_rows($result) != 1) {
                    // Check if admin
                    $q_admin = "SELECT admin_id, username, 'admin' FROM admin WHERE email = '$e' AND password = '$p'";
                    $result_admin = mysqli_query($dbcon, $q_admin);

                    if ($result_admin && mysqli_num_rows($result_admin) == 1) {
                        // Start admin session
                        $admin_data = mysqli_fetch_array($result_admin, MYSQLI_ASSOC);
                        session_start();
                        $_SESSION['admin_id'] = $admin_data['admin_id'];
                        $_SESSION['username'] = $admin_data['username'];
                        $_SESSION['role'] = 'admin';
                        mysqli_free_result($result_admin);
                        mysqli_close($dbcon);
                        header('Location: ap/admin.php');
                        exit();
                    } else {
                        $errors[] = 'Incorrect email or password.';
                    }
                } else {
                    // User login
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
            } else {
                // Validation failed (if email or password missing)
                $errors[] = 'Please try again.';
            }

            mysqli_close($dbcon);

            // Show errors using toast
            if (!empty($errors)) {
                echo '<script>
                window.onload = function() {';
                foreach ($errors as $msg) {
                    echo 'showToast("'.htmlspecialchars($msg).'");';
                }
                echo '};
                </script>';
            }
        }
    ?>

    <main>
        <div class="content" id="login-page">
            <div class="title">
                <h2> The Bowl <span style="color: #fc921b">Awaits</span>.</h2>
                <p class="description">Your perfect mix is just a few clicks away. Login now and let the flavor journey continue.</p>
                    
                <p class="description">Don’t have an account? <a href="register.php">Register here</a>.</p>
            </div>

            <div id="login-form-div">
                <form autocomplete="off" action="" method="post">
                    <h2>Login</h2>
                    <div class="login-inputs">

                        <!-- Email Field -->
                        <label for="pop_email">Email:</label><br>
                        <input 
                            required 
                            type="text" 
                            id="pop_email" 
                            name="email" 
                            placeholder="Enter your email" 
                            size="30" 
                            maxlength="50" 
                            value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"><br>

                        <!-- Password Field -->
                        <div class="input-group password-wrapper">
                            <label for="pop_password">Password:</label>
                            <div class="password-field">
                                <input 
                                    required 
                                    type="password" 
                                    id="pop_password" 
                                    name="password" 
                                    placeholder="Enter your password" 
                                    size="20" 
                                    maxlength="40" 
                                    value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>">
                                <img src="media/icons/eye.png" alt="Show Password" class="toggle-eye" data-target="pop_password">
                                <img src="media/icons/eye-slash.png" alt="Hide Password" class="toggle-eye hidden" data-target="pop_password">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="submit-button">
                        <input type="submit" id="submit_login" name="login" value="Continue">
                    </div>
                </form>
                <div id="toastBox"></div>
            </div>



        </div>
	    <?php include("footer.php"); ?>
    </main>
    <script src="js/login-register.js"></script>
</body>
</html>