<link rel="stylesheet" href="css/header.css">

<header style="padding: 15px 0;">
    <div class="header-div" style="padding-left: 3em;">
        <a href="index.php" class="brand-name" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
            <img src="media/logo.png" alt="JLougawan Logo" style="height: 55px;">
            <span class="brand-name" style="font-size: 1.5em; font-weight: bold;">Kalye Co.</span>
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
                    echo '<a href="#about">About</a>';
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
                    echo '<button id="header-Loginbtn"><a href="logout.php" style="color: #fff;">Logout</a></button>';
                } elseif ($currentPage === 'admin.php') {
                    echo '<a href="logout.php">Logout</a>';
                } elseif ($currentPage === 'menu.php') {
                    echo '<button id="cartBtn" style="background: none; border: none; cursor: pointer;"><img src="media/icons/bag.png" alt="Cart" style="width: 26px; height: 26px;"></button>';
                }

            ?>
        </nav>
    </div>
</header>
