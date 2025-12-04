
<?php
#just to make a php file

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sasin Elite Gym</title>
    <link rel="icon" type="image/png" href="images/fav.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="index.css">
     
    
</head>

<body>


    <div class="hero">
        <nav class="navbar" id="navbar">

            <div class="logo">
                <img src="Images/fulllogo.png" alt="logo">
            </div>

            <!-- Desktop Menu -->
            <div class="menu">
                <a href="#home">Home</a>
                <a href="#about">About Us</a>
                <a href="#services">Services</a>
                <a href="#gallery">Gallery</a>
                <a href="#contact">Contact Us</a>
            </div>

            <!-- Join Button -->
            <div  
            class="join" onclick="window.location.href='login.php'" >
                Join Us
            </div>

            <!-- Hamburger -->
            <div class="hamburger" onclick="toggleMenu()">
                ☰
            </div>

        </nav>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <a href="#">Home</a>
            <a href="#">About Us</a>
            <a href="#">Services</a>
            <a href="#">Gallery</a>
            <a href="#">Contact Us</a>
            <button class="mobile-join" onclick="window.location.href='login.html'">Join Us</button>
        </div>

        <!-- Hero Text -->
        <div class="hero-text" id="home">
            <h1 class="gym-name">SASIN ELITE GYM</h1>
            <p>Transform your body and mind at <b class="hero-name">SASIN ELITE GYM</b>.<br>
                Modern equipment, expert trainers, and a motivating fitness environment.
            </p>

            <button class="joininfo" onclick="window.location.href='login.php'">
                Join Us
            </button>
        </div>
    </div>

    <!-- About Us Section -->
    <section class="about-section" id="about">
        <div class="about-container">

            <div class="about-images">
                <img src="Images/gymhall1.jpg" alt="Gym Image 1" class="middle-img">
                <img src="Images/gymhall2.jpg" alt="Gym Image 2">
                <img src="Images/gymhall3.jpg" alt="Gym Image 3">
            </div>

            <div class="about-content">
                <h4 class="aboutus-heading">About Us</h4>
                <h2>From the moment you walk through the door, you know <span>Sasin Elite Gym</span> is a unique place
                </h2>

                <p>
                    At <b>Sasin Elite Gym</b>, we believe fitness is more than just a routine — it’s a lifestyle.
                    Our mission is to provide you with a motivating, inclusive, and results-driven environment.
                </p>

                <p>
                    With experienced trainers, modern equipment, and a friendly community, we ensure that
                    your fitness journey is both effective and enjoyable.
                </p>

                <div class="about-features">
                    <div>
                        <h5>Environment</h5>
                        <p><b>Clean and Airy</b></p>
                    </div>
                    <div>
                        <h5>Classes</h5>
                        <p><b>Every Level</b></p>
                    </div>
                    <div>
                        <h5>Prices</h5>
                        <p><b>Affordable</b></p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Classes Section -->
    <section class="classes-section" id="services">
        <h2 class="section-title">Our Classes</h2>
        <p class="section-subtitle">Choose a program that fits your fitness goals</p>

        <div class="classes-container">

            <div class="class-card">
                <img src="Images/cardio.jpg" alt="Cardio Class">
                <h3>Cardio Training</h3>
                <p>Boost your stamina and burn calories with high-energy cardio sessions.</p>
            </div>

            <div class="class-card">
                <img src="Images/strength training.jpg" alt="Strength Class">
                <h3>Strength Training</h3>
                <p>Build muscle and improve endurance with weight and resistance exercises.</p>
            </div>

            <div class="class-card">
                <img src="Images/yoga.jpg" alt="Yoga Class">
                <h3>Yoga</h3>
                <p>Enhance flexibility, balance, and mental focus with guided yoga sessions.</p>
            </div>

            <div class="class-card">
                <img src="Images/zumba.jpg" alt="Zumba Class">
                <h3>Zumba</h3>
                <p>Dance your way to fitness with fun, high-energy Zumba classes.</p>
            </div>

        </div>
    </section>

    <!-- Trainers Section -->
    <section class="trainers-section" id="trainer">
        <h2 class="section-title">Our Trainers</h2>
        <p class="section-subtitle">Meet the experts who guide you to achieve your fitness goals</p>

        <div class="trainers-container">

            <div class="trainer-card">
                <img src="Images/strength trainer.jpg" alt="Trainer 1">
                <h3>Ashok Poudel</h3>
                <p class="trainer-role">Strength & Conditioning Coach</p>
                <p>5+ years of experience helping clients build muscle and endurance.</p>
            </div>

            <div class="trainer-card">
                <img src="Images/yoga.jpg" alt="Trainer 2">
                <h3>Smriti Lama</h3>
                <p class="trainer-role">Yoga & Flexibility Expert</p>
                <p>Certified instructor focusing on balance, posture & mindfulness.</p>
            </div>

            <div class="trainer-card">
                <img src="Images/gym profile 2.avif" alt="Trainer 3">
                <h3>Rooney Carlsen</h3>
                <p class="trainer-role">Personal Trainer</p>
                <p>Designs customized workout and diet plans for all fitness levels.</p>
            </div>

            <div class="trainer-card">
                <img src="Images/cardio-trainer.jpg" alt="Trainer 4">
                <h3>Bikram Parajuli</h3>
                <p class="trainer-role">Cardio Coach</p>
                <p>Expert in fat-burning workouts and high-intensity interval training.</p>
            </div>

        </div>

    </section>

    <!-- Subscription Section -->
    <section class="subscription-section">
        <h2 class="section-title">Subscription Packages</h2>
        <p class="section-subtitle">Choose a plan that matches your fitness goals</p>

        <div class="pricing-container">

            <!-- Basic Plan -->
            <div class="pricing-card">
                <h3>Basic</h3>
                <h2 class="price">Rs 1,200 <span>/ month</span></h2>
                <ul>
                    <li>Gym Floor Access</li>
                    <li>Locker Room Facility</li>
                    <li>Limited Group Classes</li>
                </ul>
                <button class="price-btn">Join Now</button>
            </div>

            <!-- Standard Plan -->
            <div class="pricing-card popular">
                <div class="tag">POPULAR</div>
                <h3>Standard</h3>
                <h2 class="price">Rs 2,500 <span>/ month</span></h2>
                <ul>
                    <li>Unlimited Gym Access</li>
                    <li>Weekly Group Classes</li>
                    <li>1 Free PT Session / Month</li>
                </ul>
                <button class="price-btn">Join Now</button>
            </div>

            <!-- Premium Plan -->
            <div class="pricing-card">
                <h3>Premium</h3>
                <h2 class="price">Rs 4,000 <span>/ month</span></h2>
                <ul>
                    <li>Personal Trainer Included</li>
                    <li>Nutrition Guidance</li>
                    <li>Priority Support</li>
                </ul>
                <button class="price-btn">Join Now</button>
            </div>

        </div>
    </section>

    <!-- testomonial  / Review Section -->

    <section class="testimonials">
        <h2 class="section-title">What Our Members Say</h2>
        <p class="section-subtitle">Real reviews from our fitness family</p>

        <div class="testimonial-wrapper" id="testimonialWrapper">

            <div class="testimonial-card">
                <img src="Images/reviewer1.jpg" class="t-img">
                <h3>Ramesh Dahal</h3>
                <div class="stars">★★★★★</div>
                <p>
                    “The trainers here are super helpful and the gym environment is motivating.
                    Best place to start your fitness journey.”
                </p>
            </div>

            <div class="testimonial-card">
                <img src="Images/reviewer2.jpg" class="t-img">
                <h3>Jenny watson</h3>
                <div class="stars">★★★★☆</div>
                <p>
                    “Modern equipment, friendly trainers, and amazing classes. I love the yoga sessions!”
                </p>
            </div>

            <div class="testimonial-card">
                <img src="Images/reviewer3.jpg" class="t-img">
                <h3>Anuz Shrestha</h3>
                <div class="stars">★★★★★</div>
                <p>
                    “Perfect for muscle building and fat loss. Personal trainers give the best guidance.”
                </p>
            </div>

            <div class="testimonial-card">
                <img src="Images/reviewer4.jpg" class="t-img">
                <h3>CBum</h3>
                <div class="stars">★★★★★</div>
                <p>
                    “Perfect for muscle building and fat loss. Personal trainers give the best guidance.”
                </p>
            </div>

              <div class="testimonial-card">
                <img src="Images/reviewer4.jpg" class="t-img">
                <h3>CBum</h3>
                <div class="stars">★★★★★</div>
                <p>
                    “Perfect for muscle building and fat loss. Personal trainers give the best guidance.”
                </p>
            </div>

              <div class="testimonial-card">
                <img src="Images/reviewer4.jpg" class="t-img">
                <h3>CBum</h3>
                <div class="stars">★★★★★</div>
                <p>
                    “Perfect for muscle building and fat loss. Personal trainers give the best guidance.”
                </p>
            </div>


        </div>
    </section>

    <!-- Gallery Section -->

    <section class="gallery" id="gallery">
        <h2>Our Gallery</h2>
        <p style="text-align: center;
  margin: 30px auto 60px;
  max-width: 750px;
  color: #e4f3da;
  background: rgba(0,200,83,0.04);
  padding: 18px;
  border-radius: 10px;
  font-size: larger;
  border: 1px solid rgba(0,200,83,0.12);">At Sasin Elite Gym, we maintain a clean, safe, and fully-equipped
            environment. Browse our gallery to see the cardio zone, weight area, yoga studio, and stretching corners —
            everything designed to help you reach your fitness goals.</p>

        <div class="gallery-container">
            <div class="item"><img src="Images/gymhall1.jpg"></div>
            <div class="item"><img src="Images/gymhall2.jpg" alt=""></div>
            <div class="item"><img src="Images/gymhall3.jpg" alt=""></div>
            <div class="item"><img src="Images/gymhall4.jpg" alt=""></div>
            <div class="item"><img src="Images/gymhall4.jpg" alt=""></div>
            <div class="item"><img src="Images/gymhall5.jpg" alt=""></div>
            <div class="item"><img src="Images/gymhall2.jpg" alt=""></div>
            <div class="item"><img src="Images/gymhall1.jpg" alt=""></div>

        </div>
    </section>

    <footer class="footer">
        <div class="footer-container">

            <!-- About -->
            <div class="footer-section">
                <h3>Sasin Elite Gym</h3>
                <p>Your fitness journey starts here. We provide modern equipment, clean workout spaces, and expert
                    guidance to help you achieve your goals.</p>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-section" id="contact">
                <h4>Contact</h4>
                <ul class="contact-info">
                    <li>📍 Chitwan, Nepal</li>
                    <li>📞 +977-9803906919</li>
                    <li>📧 sasinelite@gmail.com</li>
                </ul>
            </div>

            <!-- Social -->

            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-snapchat"></i></a>
            </div>


        </div>

        <!-- Bottom Line -->
        <div class="footer-bottom">
            <p>© 2025 Sasin Elite Gym | All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById("mobileMenu").classList.toggle("show");
        }
    </script>

</body>

</html>