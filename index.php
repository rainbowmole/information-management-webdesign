<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JLougawan - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">

</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="hero-image">
        <div class="hero-text">
            <h1>Authentic Filipino <span style="color: #fc921b;">Lugaw</span> Experience</h1>
            <p style="margin: 1.5rem 0rem;">Craving comfort food? Order our best-selling lugaw, mami, tokwa’t baboy, and more — freshly made and delivered to your doorstep.</p>
            <a href="menu.php">
                <button class="hero-btn1" style="margin-right: 15px;">Order Now</button>
            </a>
            <a href="#about">
                <button class="hero-btn">Learn More</button>
            </a>
        </div>
    </div>

    <main>
        <section id="b-sell" class="home-s">
            <h2>Best Sellers</h2>
            
            <div class="card-container">
                <!-- BEST SELLERS CARD -->
                <div class="food-card">
                    <span class="best-badge">Top Pick</span>
                    <img src="media/menu/lugaw-egg.jpg" alt="Lugaw with Egg" />
                    <h3 class="card-title">Lugaw w/ Egg</h3>
                    <div class="card-overlay">
                        <h3>Lugaw w/ Egg</h3>
                        <p>Classic rice porridge with boiled egg.</p>
                        <span class="price">₱35.00</span>
                    </div>
                </div>

                <div class="food-card">
                    <img src="media/menu/tokwa-baboy.jpg" alt="Tokwa’t Baboy" />
                    <h3 class="card-title">Tokwa't Baboy</h3>
                    <div class="card-overlay">
                        <h3>Tokwa’t Baboy</h3>
                        <p>Crispy tofu and pork with vinegar soy dip.</p>
                        <span class="price">₱55.00</span>
                    </div>
                </div>

                <div class="food-card">
                    <img src="media/menu/beef-pares.jpg" alt="Beef Pares" />
                    <h3 class="card-title">Beef Pares</h3>
                    <div class="card-overlay">
                        <h3>Beef Pares</h3>
                        <p>Tender beef in sweet-savory sauce, served with garlic rice and broth.</p>
                        <span class="price">₱50.00</span>
                    </div>
                </div>

                <div class="food-card">
                    <img src="media/menu/lomi.jpg" alt="Lomi" />
                    <h3 class="card-title">Lomi</h3>
                    <div class="card-overlay">
                        <h3>Lomi</h3>
                        <p>Tender beef in sweet-savory sauce, served with garlic rice and broth.</p>
                        <span class="price">₱130.00</span>
                    </div>
                </div>
              
                <!-- Add more cards as needed -->
            </div>
        </section>

        <section id="how-to-order" class="home-s">
            <h2>How to Order</h2>
            
            <div class="card-container" style="margin-top: 40px;">
                <div class="steps-card">
                    <div class="icon-circle">
                        <img src="media/icons/shopping-cart.png" alt="Cart Icon" style="width: 26px; height: 26px;"/>
                    </div>
                    <h3>1. Choose Your Items</h3>
                    <p>Browse our menu and select your favorite lugaw and sides.</p>
                </div>

                <div class="steps-card">
                    <div class="icon-circle">
                        <img src="media/icons/money.png" alt="Cart Icon" style="width: 35px; height: 35px;"/>
                    </div>
                    <h3>2. Place Your Order</h3>
                    <p>Complete your order with cash or card.</p>
                </div>

                <div class="steps-card">
                    <div class="icon-circle">
                        <img src="media/icons/sparkle.png" alt="Cart Icon" style="width: 30px; height: 30px;"/>
                    </div>
                    <h3>3. Enjoy your Meal</h3>
                    <p>Receive your freshly prepared meal and enjoy!</p>
                </div>
            </div>

        </section>
	    <?php include("footer.php"); ?>
    </main>
    
    <script src="js/scroll-header.js"></script>
</body>
</html>