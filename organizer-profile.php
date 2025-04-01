<?php
session_start();
if (isset($_POST['signOut'])) {
    session_unset();
    session_destroy();
    header('Location: login-signup.php');
    exit();
}

// Check if the user is an organizer; otherwise, redirect to login page
if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['type'] !== 'organizer') {
    echo "<script>alert('You must be an organizer to access this page.'); window.location.href = 'login-signup.php';</script>";
    exit();
}

// Handle sign-out
if (isset($_POST['signOut'])) {
    // Unset and destroy session
    session_unset();
    session_destroy();
    header('Location: login-signup.php');
    exit();
}

// Fetch current user's details from session
$currentUser = $_SESSION['currentUser'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Profile</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            background-image: url('bg3.jpeg') ; /* Replace with your image */
            background-size: cover; /* Adjust as necessary */
            background-position: center;
            
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            padding: 10px 20px;
        }
        
        nav img {
            height: 40px; /* Adjust logo height */
            margin-right: 20px; /* Space between logo and links */
            border-radius: 10px;
            width: 100px;
            height: 70px;
        }

        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            margin: 0 10px;
            border-radius: 14px;
            font-size: 20px;
        }
       
        nav a:hover {
            background-color: #0056b3;
        }

        /* Container Styling */
        .auth-container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Header Styling */
        h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #007bff;
        }

        /* Profile Information Styling */
        .profile-info {
            text-align: left;
            margin-top: 20px;
        }

        .profile-info p {
            font-size: 1.1em;
            margin: 10px 0;
        }
       

        /* Button Styling */
        .auth-container a,
        .auth-container button {
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            font-size: 0.9em;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .auth-container a:hover,
        .auth-container button:hover {
            background-color: #0056b3;
        }

        /* Sign Out Button Styling */
        .auth-container button[name="signOut"] {
            background-color: #dc3545;
        }

        .auth-container button[name="signOut"]:hover {
            background-color: #b02a37;
        }
    </style>
</head>
<body>
    <nav>
        <div style="display: flex; align-items: center;">
            <img src="image/images.png" alt="Logo"> <!-- Replace with your logo path -->
            <a href="organizer-dashboard.php">Dashboard</a>
        </div>
        <div>
            <a href="organizer-about.php">About Us</a>
            <a href="organizer-profile.php">Profile</a>
            <form method="POST" style="display:inline;">
                <button type="submit" name="signOut" 
                    style="background: none; border: none; color: white; cursor: pointer; 
                    padding: 14px 20px; text-decoration: none; margin: 0 10px; 
                    border-radius: 14px; font-size: 20px; transition: background-color 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#dc3545'; this.style.color = 'white';" 
                    onmouseout="this.style.backgroundColor=' #0056b3'; this.style.color = 'white';">Log Out
                </button>
            </form>
        </div>
    </nav>

    <div class="auth-container">
        <h2>Organizer Profile</h2>
        
        <div class="profile-info">
            <p><strong>Organizing Committee Name:</strong> <?php echo htmlspecialchars($currentUser['committee']); ?></p>
            <p><strong>Chair Person Name:</strong> <?php echo htmlspecialchars($currentUser['chairPerson']); ?></p>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($currentUser['organizer_email']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($currentUser['username']); ?></p>
        </div>
    </div>
</body>
</html>
