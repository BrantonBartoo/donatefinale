<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Now - Make a Difference</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .slideshow-container {
            position: relative;
            max-width: 100%;
            margin: auto;
            overflow: hidden;
            background: #f9f9f9;
            padding: 20px;
            text-align: center;
        }
        .slide {
            display: none;
        }
        .slide img {
            width: 80%;
            border-radius: 5px;
        }
        .slide-description {
            margin-top: 10px;
            font-size: 18px;
        }
        .adverts-container {
            padding: 40px 20px;
            background: #ffffff;
            text-align: center;
        }
        .advert-card {
            display: inline-block;
            width: 30%;
            margin: 10px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .advert-card img {
            width: 100%;
            border-radius: 5px;
        }
    </style>
    <script>
        let slideIndex = 0;
        function showSlides() {
            let slides = document.getElementsByClassName("slide");
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) { slideIndex = 1; }
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 3000);
        }
        window.onload = showSlides;
    </script>
</head>
<body>
    <!-- Hero Section with Full Height and Centered Text -->
    <section id="hero">
        <h1>Donate for a Better Tomorrow</h1>
    </section>

    <!-- Slideshow Section -->
    <section class="slideshow-container">
        <h2>Our Impact</h2>
        <div class="slide">
            <img src="images/donation1.jpg" alt="Helping Children">
            <p class="slide-description">Providing education for underprivileged children.</p>
        </div>
        <div class="slide">
            <img src="images/donation2.jpg" alt="Medical Aid">
            <p class="slide-description">Supporting healthcare initiatives worldwide.</p>
        </div>
        <div class="slide">
            <img src="images/donation3.jpg" alt="Food Distribution">
            <p class="slide-description">Feeding families in need across the globe.</p>
        </div>
    </section>

    <!-- Adverts Section -->
    <section class="adverts-container">
        <h2>Advertisements</h2>
        <?php
        require 'db_connection.php'; // Ensure you have a connection file
        $query = "SELECT * FROM adverts ORDER BY id DESC LIMIT 3";
        $result = $pdo->query($query);
        while ($advert = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="advert-card">
                <img src="uploads/<?= htmlspecialchars($advert['image']) ?>" alt="Advert">
                <p><?= htmlspecialchars($advert['description']) ?></p>
            </div>
        <?php endwhile; ?>
    </section>

    <!-- Latest Updates Section -->
    <section id="latest-updates" style="padding: 40px 20px; background: #f9f9f9; text-align: center;">
        <h2>Latest Articles & Global Donations</h2>
        <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 20px;">
            <div style="width: 45%; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <h3>Latest Articles</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="#" style="text-decoration: none; color: #007BFF;">How Online Donations Are Changing the World</a></li>
                    <li><a href="#" style="text-decoration: none; color: #007BFF;">Top 5 Most Generous Countries in 2024</a></li>
                    <li><a href="#" style="text-decoration: bnone; color: #007BFF;">Impact of Small Donations in Big Causes</a></li>
                </ul>
            </div>
            <div style="width: 45%; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <h3>Global Donations</h3>
                <p><strong>Total Donations This Month:</strong> $500,000+</p>
                <p><strong>Top Donating Country:</strong> USA</p>
                <p><strong>Ongoing Fundraisers:</strong> 120+</p>
            </div>
        </div>
    </section>

    <!-- About Section with Container -->
    <section id="about">
        <div class="container">
            <h2>About Us</h2>
            <p>Our mission is to support underprivileged communities and make a lasting impact through your generous donations.</p>
            <p>Every contribution helps to make the world a better place. Join us in making a difference!</p>
        </div>
    </section>

    <!-- Donate Section -->
    <section id="donate">
        <div class="content">
            <h2>Make a Donation</h2>
            <p>Your contribution will help fund education, healthcare, and other vital services for those in need.</p>
            <a href="donate.php" class="donate-btn">Donate Now</a>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <p>If you have any questions, feel free to reach out.</p>
            <p>Email: support@donationapp.com</p>
        </div>
        <a href="admin_login.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; font-size: 16px; border-radius: 5px; transition: background 0.3s ease; margin: 10px 0;" 
           onmouseover="this.style.backgroundColor='#0056b3'" 
           onmouseout="this.style.backgroundColor='#007BFF'">
           Admin Login
        </a>
    </section>

    <footer>
        <p>&copy; 2025 DonationApp - All rights reserved</p>
    </footer>
</body>
</html>
