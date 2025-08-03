<?php
// index.php (formerly login.php)
require_once __DIR__ . '/auth_config.php'; // Fix: Using __DIR__ for absolute path
redirectToDashboardIfLoggedIn(); // Redirect to dashboard if already logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d7878; /* Updated primary color */
            --primary-dark: #065e5e; /* New dark primary color */
            --primary-light: #16a6a6; /* New light primary color */
            --secondary-color: #2C3E50; /* New secondary color */
            --accent-color: #08821c; /* New accent color */
            --success-color: #27AE60; /* New success color */
            --warning-color: #F39C12; /* New warning color */
            --light-bg-color: #f8f9fa; /* Light background for the overall page */
            --card-bg-color: #ffffff; /* White background for the form card */
            --input-bg-color: #eef3f8; /* Light blue-grey for input fields */
            --text-color: #343a40; /* Dark text color */
            --secondary-text-color: #6c757d; /* Lighter text for labels/hints */
        }

        body {
            background-color: var(--light-bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Inter', sans-serif; /* Using Inter as per instructions */
        }

        .form-container {
            background-color: var(--card-bg-color);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        .logo-section {
            margin-bottom: 30px;
        }

        .logo-section img {
            max-width: 180px;
            height: auto;
            margin-bottom: 15px;
        }

        .logo-section h2 {
            font-size: 1.5rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .logo-section p {
            font-size: 0.9rem;
            color: var(--secondary-text-color);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .panel-title {
            font-size: 1.1rem;
            color: var(--secondary-text-color);
            margin-bottom: 30px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom .form-control {
            background-color: var(--input-bg-color);
            border: none;
            border-radius: 10px;
            padding: 15px 15px 15px 50px; /* Adjust padding for icon */
            height: 55px; /* Consistent height */
            color: var(--text-color);
        }

        .input-group-custom .form-control::placeholder {
            color: var(--secondary-text-color);
            opacity: 0.7;
        }

        .input-group-custom .input-group-text {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            background-color: transparent;
            border: none;
            padding: 0 15px;
            display: flex;
            align-items: center;
            color: var(--secondary-text-color);
            z-index: 10; /* Ensure icon is above input */
        }

        .form-check {
            text-align: left;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-check-input {
            border-radius: 5px; /* Slightly rounded checkbox */
            margin-right: 8px;
            flex-shrink: 0; /* Prevent checkbox from shrinking */
        }

        .form-check-label {
            color: var(--secondary-text-color);
            font-size: 0.95rem;
            cursor: pointer;
            margin-bottom: 0; /* Remove default margin */
        }

        .forgot-password-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .forgot-password-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 15px 0;
            font-size: 1.1rem;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-login:hover {
            background-color: var(--primary-dark); /* Darken on hover */
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-login .fas {
            margin-right: 10px;
        }

        .customer-login-link {
            color: var(--primary-color);
            text-decoration: none;
            margin-top: 25px;
            display: inline-block;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .customer-login-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .form-container {
                padding: 30px 20px;
                margin: 20px;
            }
            .logo-section img {
                max-width: 150px;
            }
            .panel-title {
                font-size: 1rem;
            }
            .form-check {
                flex-direction: column;
                align-items: flex-start;
            }
            .forgot-password-link {
                margin-top: 10px;
            }
        }

        /* Message box styling */
        .message-box {
            background-color: #f8d7da; /* Light red for errors */
            color: #721c24; /* Dark red text */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none; /* Hidden by default */
            border: 1px solid #f5c6cb;
        }
        .message-box.success {
            background-color: #d4edda; /* Light green for success */
            color: #155724; /* Dark green text */
            border: 1px solid #c3e6cb;
        }
        .message-box.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="logo-section">
            <!-- Placeholder for your logo -->
            <img src="assets/img/Yusi_Books_Logo.png" alt="YusiTech Logo">
            
        </div>

        <div class="panel-title">Login</div>

        <!-- Message Box for Login Feedback -->
        <div id="messageBox" class="message-box"></div>

        <form id="loginForm">
            <div class="input-group-custom">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com" required>
            </div>

            <div class="input-group-custom">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>

            <div class="form-check">
                <div>
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Remember Me
                    </label>
                </div>
                <a href="#" class="forgot-password-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

         <!--<a href="#" class="customer-login-link">Customer Login</a>-->
    </div>

    <!-- Bootstrap JS (optional, for certain components like dropdowns, tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const messageBox = document.getElementById('messageBox');

            function showMessage(message, type = 'error') {
                messageBox.textContent = message;
                messageBox.className = 'message-box show'; // Reset classes
                if (type === 'success') {
                    messageBox.classList.add('success');
                } else {
                    messageBox.classList.remove('success'); // Ensure 'error' is default or explicitly added
                }
                setTimeout(() => {
                    messageBox.classList.remove('show');
                }, 3000); // Hide after 3 seconds
            }

            loginForm.addEventListener('submit', async function(event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(loginForm);

                try {
                    const response = await fetch('login_handler.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showMessage(result.message, 'success');
                        // Redirect to dashboard after a short delay for message visibility
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 1000); // Redirect after 1 second
                    } else {
                        showMessage(result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showMessage('An unexpected error occurred. Please try again.', 'error');
                }
            });
        });
    </script>
</body>
</html>
