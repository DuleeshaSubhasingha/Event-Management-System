<?php
session_start();

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['type'] !== 'organizer') {
    header('Location: login-signup.php'); // Redirect to login if not logged in
    exit();
}

// Function to save events to a JSON file
function saveEvent($event) {
    $file = 'events.json';
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    
    $events = json_decode(file_get_contents($file), true);
    $events[] = $event;
    file_put_contents($file, json_encode($events));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $eventName = $_POST['eventName'];
    $organizingCommittee = $_POST['organizingCommittee'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $venue = $_POST['venue'];
    $speakerName = $_POST['speakerName'];

    // Generate a unique event ID
    $eventID = 'E' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 9));
  
    // Create event array
    $eventData = [
        'id' => $eventID,
        'name' => $eventName,
        'committee' => $organizingCommittee,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'startTime' => $startTime,
        'endTime' => $endTime,
        'venue' => $venue,
        'speaker' => $speakerName,
    ];

    // Save event to the file
    saveEvent($eventData);
    $message = "Event created successfully!";

    // Redirect back to organizer dashboard
    header('Location: organizer-dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('bg3.jpeg'); /* Replace with your image */
            background-size: cover; /* Adjust as necessary */
            background-repeat: no-repeat;
            background-position: center;
            color: #333;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .event-container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin: 10px 0 5px;
            text-align: left;
        }

        input[type="text"], input[type="date"], input[type="time"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: green;
            margin-bottom: 15px;
            font-weight: 600;
        }

        #backButton {
            background-color: #6c757d;
        }

        #backButton:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

    <div class="event-container">
        <h2>Create Event</h2>
        
        <!-- Display success message -->
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($eventID); ?>">

            <label for="eventName">Event Name:</label>
            <input type="text" id="eventName" name="eventName" required>
            
            <label for="organizingCommittee">Organizing Committee:</label>
            <input type="text" id="organizingCommittee" name="organizingCommittee" required>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" required>

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate" required>

            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime" required>

            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime" required>

            <label for="venue">Venue:</label>
            <select id="venue" name="venue" required>
                <option value="">Select Venue</option>
                <option value="DCS Auditorium">DCS Auditorium</option>
                <option value="CSL 1 & 2">CSL 1 & 2</option>
                <option value="CSL 3 & 4">CSL 3 & 4</option>
            </select>

            <label for="speakerName">Speaker Name:</label>
            <input type="text" id="speakerName" name="speakerName" required>

            <button type="submit">Create Event</button>
        </form>
        <button id="backButton" onclick="goBack()">Back to Events</button>
    </div>

    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page
        }
    </script>
    
</body>
</html>
