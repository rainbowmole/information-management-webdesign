<link rel="stylesheet" href="css/header.css">

<header style="padding: 15px 0;">
    <div class="header-div" style="padding-left: 3em;">
        <a href="index.php">
            <img src="media/logo.png" alt="JLougawan Logo" style="height: 55px;">
        </a>
    </div>

    <div class="header-div">
        <nav>
            <?php
                $currentPage = basename($_SERVER['PHP_SELF']);

                if ($currentPage === 'admin.php') {
                    echo '';
                } elseif ($currentPage === 'index.php') {
                    echo '<a href="menu.php">Menu</a>';
                    echo '<a href="about.php">About</a>';
                    echo '<a href="contact.php">Contact</a>';
                }
            ?>
        </nav>
    </div>

    <div class="header-div" style="padding-right: 3em;">
        <nav>
            <?php
                if ($currentPage === 'index.php') {
                    echo '<a href="register.php">Register</a>';
                    echo '<button id="header-loginBtn"><a href="login.php" style="color: #fff;">Login</a></button>';
                } elseif ($currentPage === 'admin.php') {
                    echo '<a href="logout.php">Logout</a>';
                } elseif ($currentPage === 'menu.php') {
                    echo '<button id="cartBtn" style="background: none; border: none; cursor: pointer;"><img src="media/icons/bag.png" alt="Cart" style="width: 26px; height: 26px;"></button>';
                }

            ?>
        </nav>
    </div>
</header>
