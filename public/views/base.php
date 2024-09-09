<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$isAuthenticated = isset($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

    <title><?php echo isset($title) ? $title : 'ChronoLuxe'; ?></title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../assets/css/mdb5.css"/>
    <link rel="stylesheet" href="../assets/css/roboto.css"/>
    <link rel="stylesheet" href="../assets/css/style.css"/>

    <link rel="shortcut icon" href="../assets/images/brand.png" type="image/x-icon">
    

</head>
<body>
    <div id="spinner" style="display: none;">
        <div class="spinner-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div id="spinner-message" class="spinner-message"></div>
        </div>
    </div>
    

    
      <style>
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255, 255, 255, 0.7); /* Slightly transparent white background */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999; /* Ensures the spinner is on top of other elements */
        }
        .spinner-message {
            margin-top: 10px; /* Space between spinner and message */
            font-size: 1.2rem;
            color: #333;
        }
    </style>
    

    <style>
    .card {
        border: none;
        border-radius: 15px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
        overflow: hidden; /* Ensure corners are rounded */
    }
    .card-img-top {
        object-fit: cover; /* Ensure the image covers the card area */
        height: 200px; /* Fixed height for uniformity */
    }
    .star-rating {
        color: gold; /* Star color */
        font-size: 1rem; /* Adjust star size */
    }
    .badge-sale {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: red;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
    }
    .card-body {
        text-align: center;
    }

    .product-image {
        width: 100%; /* Full width of the card */
        height: 200px; /* Fixed height */
        overflow: hidden; /* Hide overflow to maintain fixed size */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        width: 100%; /* Adjust image to fit container width */
        height: 100%; /* Adjust image to fit container height */
        object-fit: cover; /* Cover the container, keeping aspect ratio */
        display: block; /* Remove extra space below image */
    }

    @media (max-width: 767.98px) {
        .card-img-top {
            height: 150px; /* Adjust height on small screens */
        }
    }
    </style>


    
      
       
<style>
        #search-form {
            position: relative; /* Make sure the form is positioned relative */
        }

        #search-results {
            position: absolute;
            top: 100%; /* Align to the bottom of the input */
            left: 0; /* Align to the left of the input */
            width: 100%; /* Match the width of the input */
            background-color: #ffffff;
            border: 1px solid #ddd;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none; /* Initially hidden */
        }

        .dropdown-menu {
            padding: 0; /* Remove padding to fit results better */
            min-width: 100%; /* Ensure the dropdown is as wide as the input */
        }

        .dropdown-item {
            padding: 8px 12px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>
      
        
    



    <form id="logoutForm" action="/logout" method="POST" style="display: none;">
        <input type="hidden" name="action" value="logout">
    </form>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="../assets/images/brand.png" alt="Store Logo" style="height: 60px; width: auto;">
            </a>        
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/frontPage">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop">Shop</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Accounts
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($isAuthenticated): ?>
                                <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="#" onclick="document.getElementById('logoutForm').submit();">Logout</a>
                                </li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/sign-up">Create Account</a></li>
                                <li><a class="dropdown-item" href="/login">Login</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php if ($isAuthenticated): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/order_status">Order Status</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <form id="search-form" class="d-flex" role="search">
                    <input id="search-input" class="form-control me-2" type="search" placeholder="Go to..." aria-label="Search">
                    <div id="search-results" class="dropdown-menu"></div>
                </form>
                



            </div>
        </div>
    </nav>  

    

    <?php echo $content; ?>

   
    <footer
            class="text-center text-lg-start text-white"
            style="background-color: #3e4551"
            id="aboutus"
            >
      <!-- Grid container -->
      <div class="container p-4 pb-0">
        <!-- Section: Links -->
        <section class="">
          <!--Grid row-->
          <div class="row">
            <!--Grid column-->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase">ChronoLuxe</h5>

              <p>
                Experience unparalleled craftsmanship and precision with Rolex, the epitome of luxury watchmaking. Whether you're seeking a classic timepiece or the latest innovation, our collection embodies sophistication and excellence. Visit us to explore our exclusive range and find the perfect Rolex to elevate your style.
              </p>
            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase">Account Management</h5>

              <ul class="list-unstyled mb-0">
                <li>
                  <a href="/profile" class="text-white">Edit Account</a>
                </li>
                <li>
                  <a href="/sign-up" class="text-white">Create Account</a>
                </li>
              </ul>
            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase">Shop</h5>

              <ul class="list-unstyled mb-0">
                
                <li>
                  <a href="/cart" class="text-white">Cart</a>
                </li>
                <li>
                  <a href="/order_status" class="text-white">Orders</a>
                </li>
              </ul>
            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase">Stay Connected</h5>

              <ul class="list-unstyled mb-0">
                <li>
                  <a href="#!" class="text-white">Facebook</a>
                </li>
                <li>
                  <a href="#!" class="text-white">Instagram</a>
                </li>
                <li>
                  <a href="#!" class="text-white">Twitter</a>
                </li>
              </ul>
            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase">Contact us</h5>

              <ul class="list-unstyled mb-0">
                <li>
                  <a href="#!" class="text-white">Email: support@chronoluxe.com</a>
                </li>
                <li>
                  <a href="#!" class="text-white">Phone: +1 (800) 123-4567</a>
                </li>
              </ul>
            </div>
            <!--Grid column-->
          </div>
          <!--Grid row-->
        </section>
        <!-- Section: Links -->

        <hr class="mb-4" />

        <!-- Section: CTA -->
        <section class="">
          <p class="d-flex justify-content-center align-items-center">
            <span class="me-3">Register for free</span>
            <a href="/sign-up" class="btn btn-outline-light btn-rounded">
              Sign up!
            </a>
          </p>
        </section>
        <!-- Section: CTA -->

        <hr class="mb-4" />

        <!-- Section: Social media -->
        <section class="mb-4 text-center">
          <!-- Facebook -->
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="#!"
            role="button"
            ><i class="fab fa-facebook-f"></i
            ></a>

          <!-- Twitter -->
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="#!"
            role="button"
            ><i class="fab fa-twitter"></i
            ></a>

          <!-- Google -->
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="#!"
            role="button"
            ><i class="fab fa-google"></i
            ></a>

          <!-- Instagram -->
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="#!"
            role="button"
            ><i class="fab fa-instagram"></i
            ></a>

          <!-- Linkedin -->
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="#!"
            role="button"
            ><i class="fab fa-linkedin-in"></i
            ></a>

          <!-- Github -->
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="#!"
            role="button"
            ><i class="fab fa-github"></i
            ></a>
        </section>
        <!-- Section: Social media -->
      </div>
      <!-- Grid container -->

      <!-- Copyright -->
      <div
          class="text-center p-3"
          style="background-color: rgba(0, 0, 0, 0.2)"
          >
        © 2024 Copyright:
        <a class="text-white" href=""
          >All rights Reserved.</a
          >
      </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>

    
     
    
    <script>
    document.getElementById('search-input').addEventListener('input', function() {
        var query = this.value.trim();

        if (query.length === 0) {
            document.getElementById('search-results').innerHTML = '';
            document.getElementById('search-results').style.display = 'none';
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/search?query=' + encodeURIComponent(query), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var results = JSON.parse(xhr.responseText);
                var resultsContainer = document.getElementById('search-results');
                
                resultsContainer.innerHTML = '';

                if (results.length > 0) {
                    results.forEach(function(result) {
                        var resultItem = document.createElement('a');
                        resultItem.className = 'dropdown-item';
                        resultItem.href = result.url;
                        resultItem.innerHTML = result.title + '<p class="text-muted" style="font-size: 0.8rem;">' + result.description + '</p>';
                        resultsContainer.appendChild(resultItem);
                    });
                    resultsContainer.style.display = 'block'; // Show results container
                } else {
                    resultsContainer.innerHTML = '<p class="dropdown-item text-center">No results found.</p>';
                    resultsContainer.style.display = 'block'; // Show results container
                }
            }
        };
        xhr.onerror = function() {
            console.error('An error occurred during the AJAX request.');
        };
        xhr.send();
    });
</script>
    

    


    

    <script>
        function showSpinner(message) {
            document.getElementById('spinner-message').textContent = message;
            document.getElementById('spinner').style.display = 'block';
        }

        function hideSpinner() {
            document.getElementById('spinner').style.display = 'none';
        }

        function handleFormSubmission(formId) {
            var message;

            switch (formId) {
                case 'logoutForm':
                    message = 'Logging out...';
                    break;
                case 'loginForm':
                    message = 'Logging you in...';
                    break;
                case 'signUpForm':
                    message = 'Redirecting to sign-in page...';
                    break;
                default:
                    message = 'Processing...';
                    break;
            }

            showSpinner(message);
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Attach event listeners to forms
            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function () {
                    handleFormSubmission(form.id);
                });
            });

            // Optional: Hide spinner after a delay for emphasis, adjust time as needed
            setTimeout(function () {
              hideSpinner();
            }, 3000);
        });

        // Show spinner when page starts loading
        window.addEventListener("beforeunload", function () {
            showSpinner('Loading...');
        });

        // Hide spinner once page has fully loaded
        window.addEventListener("load", function () {
            hideSpinner();
        });
    </script>

    <script src="../assets/js/mdb5.js"></script>
</body>
</html>
