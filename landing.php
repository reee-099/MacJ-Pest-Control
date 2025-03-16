<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacJ Pest Control - Professional Pest Management Services</title>
    <link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-size: cover;
            background-position: center;
            height: 80vh;
            color: white;
        }
        .service-card {
            transition: transform 0.3s;
        }
        .service-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="landing.php">
                <img src="MACJLOGO.png" alt="MacJ Pest Control" width="160" height="50">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#why-us">Why Choose Us</a></li>
                    <li class="nav-item">
                        <?php 
                        if(isset($_SESSION['client_id'])) {
                            echo '<a class="nav-link" href="logout.php">Logout</a>';
                        } else {
                            echo '<a class="nav-link" href="SignIn.php">Sign In</a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>  
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section d-flex align-items-center">
        <div class="container text-center">
            <a href="" class="btn btn-primary btn-lg px-5">Schedule Appointment</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Our Services</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Residential Pest Control</h5>
                            <p class="card-text">Complete protection for your home against termites, rodents, and insects.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Commercial Solutions</h5>
                            <p class="card-text">Customized pest management for businesses and industrial facilities.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Emergency Services</h5>
                            <p class="card-text">24/7 rapid response team for urgent pest infestations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why-us" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose MacJ Pest Control</h2>
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="h1 text-primary mb-3">✓</div>
                    <h5>Licensed Technicians</h5>
                    <p>Certified professionals with extensive training</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h1 text-primary mb-3">♻</div>
                    <h5>Eco-Friendly Solutions</h5>
                    <p>Safe treatments for families and pets</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h1 text-primary mb-3">⏰</div>
                    <h5>24/7 Availability</h5>
                    <p>Emergency services anytime, anywhere</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h1 text-primary mb-3">💯</div>
                    <h5>Satisfaction Guarantee</h5>
                    <p>100% money-back guarantee</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer with Contact Info -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="MACJLOGO.png" alt="Logo" width="160" height="50" class="mb-3">
                    <p>Professional pest management solutions for residential and commercial properties.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact Information</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">📍 123 Pest Control Way, Cityville</li>
                        <li class="mb-2">📞 (555) 123-4567</li>
                        <li class="mb-2">✉️ info@macjpestcontrol.com</li>
                        <li class="mb-2">⏲ Mon-Sun: 24/7 Emergency Service</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-5">
            <p class="text-center mb-0">© 2023 MacJ Pest Control. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>