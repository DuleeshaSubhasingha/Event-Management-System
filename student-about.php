<?php
session_start();
if (isset($_POST['signOut'])) {
    session_unset();
    session_destroy();
    header('Location: login-signup.php');
    exit();
}

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['currentUser'])) {
    header('Location: login-signup.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Event Management System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-image: url('bg2.jpeg'); /* Replace with your image */
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
            color: #333;
            line-height: 1.6;
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            padding: 10px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        nav img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            margin-right: 20px;
        }
        nav a {
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 500;
        }
        nav a:hover {
            background-color: #0056b3;
        }

        /* Logout Button Styling */
        form button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 18px;
            padding: 12px 18px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #dc3545;
        }

        /* About Us Container */
        .about-container {
            width: 90%;
            max-width: 900px;
            background-color: #ffffff;
            padding: 40px;
            margin: 30px auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Heading Styles */
        h2 {
            color: #007bff;
            font-size: 2.4em;
            margin-bottom: 20px;
        }
        h3 {
            color: #0056b3;
            font-size: 1.6em;
            margin-bottom: 15px;
        }

        /* Paragraph Styles */
        p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 20px;
            text-align: justify;
        }

        /* Decorative Horizontal Line */
        hr {
            margin: 20px 0;
            border: none;
            border-top: 2px solid #f0f2f5;
            width: 80%;
        }

        /* Footer Link */
        .footer-link {
            display: block;
            margin-top: 30px;
            font-size: 1em;
            color: #007bff;
            text-decoration: none;
        }
        .footer-link:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <nav>
        <div style="display: flex; align-items: center;">
            <img src="image/images.png" alt="Logo"> <!-- Replace with your logo path -->
            <a href="student-dashboard.php">Dashboard</a>
        </div>
        <div>
            <a href="student-about.php">About Us</a>
            <a href="student-profile.php">Profile</a>
            <form method="POST" style="display:inline;">
                <button type="submit" name="signOut">Log Out</button>
            </form>
        </div>
    </nav>

    <div class="about-container">
        <h2>About Our Event Management System</h2>
        <p>Welcome to our Event Management System (EMS), designed to streamline the process of organizing and managing events efficiently. Our platform offers a range of features for organizers and participants, ensuring a smooth experience from event creation to registration and attendance.</p>
        
        <hr>

        <h3>Our Vision</h3>
        <p>To be a leading platform in event management, revolutionizing how events are organized and experienced worldwide.</p>

        <hr>

        <h3>Our Mission</h3>
        <p>Our mission is to provide an intuitive and user-friendly system that enhances collaboration among organizers, fosters community engagement, and facilitates seamless event participation for everyone involved.</p>
    </div>
</body>
</html>
