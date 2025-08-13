<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .logout-container {
            text-align: center;
            padding: 50px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            animation: slideInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
            transform: translateY(50px);
            opacity: 0;
        }
        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 15px;
            animation: fadeIn 1.5s ease-in-out forwards;
        }
        p {
            color: #555;
            font-size: 1.2em;
        }
        .countdown-wrapper {
            margin: 20px 0;
        }
        .countdown {
            font-size: 2em;
            color: #007bff;
            font-weight: bold;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        .redirect-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .redirect-link a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .logout-container {
                padding: 30px;
            }
            h1 {
                font-size: 2em;
            }
            p {
                font-size: 1em;
            }
            .countdown {
                font-size: 1.5em;
            }
        }
        @media (max-width: 480px) {
            .logout-container {
                padding: 20px;
                margin: 20px;
            }
            h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>Successfully Logged Out</h1>
        <p>You are being redirected to the login page.</p>
        <div class="countdown-wrapper">
            <p>Redirecting in <span id="countdown" class="countdown">5</span> seconds...</p>
        </div>
        <div class="redirect-link">
            <p>If you are not redirected, <a href="index.php">click here</a>.</p>
        </div>
    </div>

    <script>
        (function() {
            let countdown = 5;
            const countdownElement = document.getElementById('countdown');

            const timer = setInterval(() => {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
                if (countdown <= 0) {
                    clearInterval(timer);
                    window.location.href = 'index.php';
                }
            }, 1000);
        })();
    </script>
</body>
</html>