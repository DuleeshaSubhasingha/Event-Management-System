<?php
session_start();

if (isset($_POST['signOut'])) {
    session_unset();
    session_destroy();
    header('Location: login-signup.php');
    exit();
}

function loadEvents() {
    $file = 'events.json';
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    return json_decode(file_get_contents($file), true);
}

// Check if the user is a student, otherwise redirect to login page
if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['type'] !== 'student') {
    echo "<script>alert('You must be a student to access this page.'); window.location.href = 'login-signup.php';</script>";
    exit();
}

$currentUser = $_SESSION['currentUser'];
$events = loadEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
            background-image: url('bg2.jpeg'); /* Replace with your image */
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
            color: #333;
        }

        /* Dark overlay for background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Darken background for readability */
            z-index: -1;
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(0, 123, 255, 0.8); /* Semi-transparent blue */
            padding: 10px 20px;
            border-radius: 0 0 10px 10px;
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
            max-width: 1000px;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
            padding: 30px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        /* Header Styling */
        h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #007bff;
        }

        /* Event List Styling */
        #eventList {
            margin-top: 20px;
        }

        /* Event Item Styling */
        .event-item {
            background-color: #f9fafb;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .event-item h3 {
            font-size: 1.6em;
            color: #333;
        }

        .event-item p {
            font-size: 1em;
            color: #555;
            margin: 5px 0;
        }

        /* Button Styling */
        .auth-container a,
        .auth-container button {
            display: inline-block;
            margin: 5px;
            padding: 10px 20px;
            font-size: 1em;
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
            <a href="student-dashboard.php">Dashboard</a>
        </div>
        <div>
            <a href="student-about.php">About Us</a>
            <a href="student-profile.php">Profile</a>
            <form method="POST" style="display:inline;">
                <button type="submit" name="signOut" 
                        style="background: none; border: none; color: white; cursor: pointer; 
                               padding: 14px 20px; text-decoration: none; margin: 0 10px; 
                               border-radius: 14px; font-size: 20px; transition: background-color 0.3s ease;" 
                        onmouseover="this.style.backgroundColor='#dc3545'; this.style.color = 'white';" 
                        onmouseout="this.style.backgroundColor='#0056b3'; this.style.color = 'white';">
                    LogOut
                </button>
            </form>
        </div>
    </nav>

    <div class="auth-container">
        <h2>Welcome, Student!</h2>

        <div id="eventList">
            <h4>Available Events are here.</h4><br>
            <?php if (empty($events)): ?>
                <p>No events created yet.</p>
            <?php else: ?>
                <?php foreach ($events as $index => $event): ?>
                    <div class="event-item">
                        <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                        <p>Organizing Committee: <?php echo htmlspecialchars($event['committee']); ?></p>
                        <p>Date: <?php echo htmlspecialchars($event['startDate']); ?> to <?php echo htmlspecialchars($event['endDate']); ?></p>
                        <p>Time: <?php echo htmlspecialchars($event['startTime']); ?> to <?php echo htmlspecialchars($event['endTime']); ?></p>
                        <p>Venue: <?php echo htmlspecialchars($event['venue']); ?></p>
                        <p>Speaker: <?php echo htmlspecialchars($event['speaker']); ?></p>
                        <a href="student-registration.php?id=<?php echo $event['id']; ?>">Register</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
